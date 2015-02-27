<?
	require_once(getcwd()."/common.php");
	checkSession("uid");

	if ($_SESSION['uid'] != $conf['admin']) {
		echo "<script>document.location.replace('summary.php');</script>";
		exit();
	}

	$dir = (isset($_REQUEST['dir']) ? htmlspecialchars($_REQUEST['dir']) : "");
	$file = (isset($_REQUEST['file']) ? htmlspecialchars($_REQUEST['file']) : "");

	$is_edit = false;
	$is_exist = false;
	if (! empty($dir)) {
		if (is_dir("data/{$dir}")) {
			$files = getFileListInDir($dir."/mp3", "mp3");
			if (sizeof($files) > 0) {
				$is_exist = true;
				$is_edit = in_array($file, $files);
			}
		}
	}
	if (! $is_exist) {
		echo "<script>document.location.replace('formating.php');</script>";
		exit();
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FORMATING : <?=$dir?></title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<style>
.header .ui-btn { top: .35em; margin-top:0; }
#edit table { width : 100%; border: 0px none; }
#rightpanel table { width : 100%; border: 0px none; }
#rightpanel td { border-bottom: 1px dotted #aaa; padding: .5em 0; }
	</style>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
<script>
soundManager.setup({});

var sm;
var md = 0; // round(sm.duration/100) // max duration
var list = [];
var count_changing_file = 0;
dir = "<?=$dir?>";
path = "/data/<?=$dir?>/";

// 단위를 100 msec 로 해야할듯
function changeRangeMax() {
	md = Math.round(sm.duration/100);
	var rg1 = document.getElementById("range1");
	var rg2 = document.getElementById("range2");
	if (rg1) rg1.max = md;
	if (rg2) {
		rg2.max = md;
		rg2.value = md;
	}
	$("#range1").val(0).slider("refresh");
	$("#range2").val(md).slider("refresh");
}

function changeFile(v) {
	if (v.trim() == "") return;

	count_changing_file++;
	var e_fn = document.getElementById("filename");
	if (e_fn) e_fn.value = v;

	sm = soundManager.createSound({id:"sm_"+v , url: path+"mp3/"+v+".mp3", autoLoad: true});
	sm.load({onload : function() {
		if (this.readyState == 3) {
			changeRangeMax();
		}
	}});
}

function play() {
	if (! sm) return;

	sm.stop();

	var rg1 = document.getElementById("range1");
	var rg2 = document.getElementById("range2");
	if (!rg1 || !rg2) return;

	sm.play({
		from: (parseInt(rg1.value) * 100),
		to: (parseInt(rg2.value) * 100)
	});
}

function add() {
	var select = document.getElementById("select");
	if (! select) return;
	if (select.value.trim() == "") return;

	var e_script = document.getElementById("script");
	if (! e_script) return;

	var script = e_script.value.trim();
	if (script == "") return;
	e_script.value = "";

	var trans = "";
	var e_trans = document.getElementById("trans");
	if (e_trans) {
		trans = e_trans.value.trim();
		e_trans.value = "";
	}

	// 가공 특수문자 처리
	re = /[\{\}\[\]\/;|*~^\-_+┼<>\#&\\=]/g;
	script = script.replace(re, "");
	script = script.replace(/\"/g, "\\\"");
	script = script.replace(/[`\']/g, "\\\'");
	
	trans = trans.replace(re, "");
	trans = trans.replace(/\"/g, "\\\"");
	trans = trans.replace(/[`\']/g, "\\\'");

	// 만들고
	var obj = {};
	obj.fn	 = select.value;
	obj.from = parseInt($("#range1").val()) * 100;
	obj.to	 = parseInt($("#range2").val()) * 100;
	obj.script = script;
	obj.trans  = trans;

	if (obj.from >= 0 && obj.to >= 0) return;

	// list 에 같은 from 이 있는 지 확인하고
	var bool = false;
	for (var i=0; i < list.length; i++) {
		if (list[i].from == obj.from) {
			bool = true;
			break;
		}
	}
	if (bool) return;

	// list 에 넣고
	list.push(obj);

	// right pannel 에 붙이고
	var tr = document.createElement("tr");
	tr.innerHTML = "<td>" + list.length + "</td><td>" + script + "</td><td>X</td><td>E</td>";
	$("#list").append(tr);

	// 시작위치를 끝위치로 바꾸고 끝위치는 임의의 위치로
	var rg2_v = (obj.to/100);
	$("#range1").val(rg2_v).slider("refresh");
	$("#range2").val(rg2_v + 40).slider("refresh");
}

function save() {
	// 정렬 여부 묻는 게 좋을듯
	// list[] 정렬 : fn > from (from sort 후 fn sort 하면 된다)
	list.sort(function(a,b) { return a.from - b.from; });
	if (count_changing_file != 1) {
		list.sort(function(a,b) { return a.fn < b.fn ? -1 : a.fn > b.fn ? 1 : 0; });
	}

	// form 에 추가
	var e_list = document.getElementById("lists");
	if (! e_list) return;
	var html = "";
	for (var i=0; i<list.length; i++) {
		var text = "";
		text += list[i].fn + "\t";
		text += list[i].from + "\t";
		text += list[i].to + "\t";
		text += list[i].script + "\t";
		text += list[i].trans;
		html += "<input name=\"data[]\" type=\"hidden\" value=\"" + text + "\" />";
	}
	e_list.innerHTML = html;

	var f = document.edit;
	if (count_changing_file != 1) { f.filename.value = ""; }
	f.submit();
}
function init() {
	<? if ($is_edit) {
		// 파일을 읽어서 
	?>
	<? } ?>
}
</script>
</head>
<body>
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header" data-position="fixed">
		<h1>FORMATING</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-grid ui-nodisc-icon ui-alt-icon ui-btn-right">List</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	<? // js 파일이 이미 있으면 읽어오기 해야하나.. ?>
		<form name="edit" id="edit" method="post" action="formating_save.php">
			<input type="hidden" name="dir" id="dir" value="<?=$dir?>" />
			<input type="hidden" name="filename" id="filename" value="" />
			<select name="select" id="select" data-native-menu="false" data-mini="true" onchange="changeFile(this.value);">
				<option value="" selected="selected">FILE</option>
				<? foreach ($files as $f) { echo "<option value=\"{$f}\">{$f}.mp3</option>"; } ?>
			</select>
			<div data-role="rangeslider" data-mini="true">
				<input type="range" name="range1" id="range1" min="0" max="100" value="0">
				<input type="range" name="range2" id="range2" min="0" max="100" value="100">
			</div>
			<table>
				<tr>
					<td><label for="script">SCRIPT: </label></td>
					<td><textarea cols="40" rows="8" name="script" id="script"></textarea></td>
				</tr>
				<tr>
					<td><label for="trans">TRANS: </label></td>
					<td><textarea cols="40" rows="8" name="trans" id="trans"></textarea></td>
				</tr>
			</table>
			<table style="margin-bottom:4em;">
				<tr>
					<td><input type="button" value="ADD" onclick="add();" /><td/>
					<td><input type="button" value="PLAY" onclick="play();" /></td>
				</tr>
			</table>
			<div id="lists"></div>
			<input type="button" value="SAVE" onclick="save();" />
		</form>
	</div><!-- /content -->

	<!--
	<div data-role="footer" data-tap-toggle="false" class="footer">
	</div> /footer -->

	<? require_once(getcwd()."/left.php"); ?>

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay">
		<table id="list">
			<tr>
				<td>no</td>
				<td>script</td>
				<td>Del</td>
				<td>Edit</td>
			</tr>
		</table>
	</div><!-- /rightpanel -->

</div><!-- /page -->

</body>
</html>
