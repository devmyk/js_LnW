<? defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : admin</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
<script>
soundManager.setup({});
var sm;
var ppct = [];
var pct = [];
var ct = [];
<?
foreach($category as $d1) {
	$ppcode = htmlspecialchars($d1['code']);
	$ppname = htmlspecialchars($d1['name']);
//	printf("ppct.%s = {code:\"%s\", name:\"%s\"};\n", $ppcode, $ppcode, $ppname);
	printf("ppct.push({code:\"%s\", name:\"%s\"});\n", $ppcode, $ppname);
	foreach($d1['list'] as $d2) {
		$pcode = htmlspecialchars($d2['code']);
		$pname = htmlspecialchars($d2['name']);
//		printf("pct.%s = {pcode:\"%s\", code:\"%s\", name:\"%s\"};\n", $pcode, $ppcode, $pcode, $pname);
		printf("pct.push({pcode:\"%s\", code:\"%s\", name:\"%s\"});\n", $ppcode, $pcode, $pname);
		foreach($d1['list'][$pcode]['list'] as $d3) {
			$code = htmlspecialchars($d3['code']);
			$name = htmlspecialchars($d3['name']);
//			printf("ct.%s = {ppcode:\"%s\", pcode:\"%s\", code:\"%s\", name:\"%s\"};\n", $code, $ppcode, $pcode, $code, $name);
			printf("ct.push({ppcode:\"%s\", pcode:\"%s\", code:\"%s\", name:\"%s\"});\n", $ppcode, $pcode, $code, $name);
		}
	}
}
?>

function play(seq)
{
	if (sm) sm.stop();
	var ef = document.getElementById("from"+seq);
	var et = document.getElementById("to"+seq);
	var eu = document.getElementById("url"+seq);
	if (! ef | ! et | ! eu) return;
	if (eu.value == "") return;
	var from = parseInt(ef.value);
	var to = parseInt(et.value);
	var obj = {id: "sm"+seq, from: 0, url: eu.value};
	if (from) obj.from = from;
	if (to) obj.to = to;
	sm = soundManager.createSound(obj);
	sm.play();
}

function changeCategory(depth ,code) {
	try {
		if (depth == 1) {
			$("#ppcode").val(code);
			var html = "";
			for(var i=0; i<pct.length; i++) {
				if (pct[i].pcode == code) {
					html += "<option value=\""+pct[i].code+"\">"+pct[i].name+"</option>";
				}
			}
			document.getElementById("pcode").innerHTML = html;
			scode = $("#pcode option:selected").val(); // document.getElementById("pcode").selectedOptions[0].value
			html = "";
			for(var i=0; i<ct.length; i++) {
				if (ct[i].pcode == scode) {
					html += "<option value=\""+ct[i].code+"\">"+ct[i].name+"</option>";
				}
			}
			document.getElementById("ccode").innerHTML = html;
		} else if (depth == 2) {
			$("#pcode").val(code);
			var html = "";
			for(var i=0; i<ct.length; i++) {
				if (ct[i].pcode == code) {
					html += "<option value=\""+ct[i].code+"\">"+ct[i].name+"</option>";
				}
			}
			document.getElementById("ccode").innerHTML = html;
		} else if (depth == 3) {
			$("#ccode").val(code);
		}
	} catch(e) {}
}

function move_category() {
	var f = document.fm;
	f.code.value = $("#ccode option:selected").val();
	f.action = "<?=base_url("c_dictation/ad_script");?>";
	f.target = "_self";
	f.submit();
}

function mk_js() {
	var f = document.fm;
	f.code.value = $("#ccode option:selected").val();
	f.action = "<?=base_url("c_dictation/ad_mk_js");?>";
	f.target = "_self";
	f.submit();
}

function edit(seq) {
	window.open("", "wEdit", "width=300,height=200");
	var f = document.fm;

	// is_change?

	f.seq.value = seq;
	f.mp3.value = document.getElementById("url"+seq).value;
	f.from.value = document.getElementById("from"+seq).value;
	f.to.value = document.getElementById("to"+seq).value;
	f.action = "<?=base_url("c_dictation/ad_script_edit");?>";
	f.target = "wEdit";
	f.submit();
}

function show_mp3(seq) {
	try {
		var e = document.getElementById("url"+seq);
		if (e.type == "text") e.type = "hidden";
		else e.type = "text";
	} catch(e) {}
}

$(document).ready(function() {
	$("#ppcode").val("<?=$info['ppcode']?>");
	$("#pcode").val("<?=$info['pcode']?>");
	$("#ccode").val("<?=$info['code']?>");
});
</script>
<style>
body, table, td { font-size:10pt }
</style>
</head>
<body>
<form name="fm" method="post">
<input type="hidden" id="code" name="code" value="<?=$info['code']?>" />
<input type="hidden" name="seq" />
<input type="hidden" name="from" />
<input type="hidden" name="to" />
<input type="hidden" name="mp3" />
<table width="100%">
	<tr><td colspan="2"><?//debug($info);?></td></tr>
	<tr>
		<td width="130px" valign="top" rowspan="2" style="border-right:1px solid #aaa;"><!-- tables -->
        <? require_once("./app/views/dictation/admin/left.php"); ?>
		</td>
		<td valign="top" height="1em" style="border-bottom:1px solid #aaa;">
			<? //debug($category['en']); ?>
			<select id="ppcode" onchange="changeCategory(1,this.value);">
			<? foreach($category as $d1) { ?>
				<option value="<?=htmlspecialchars($d1['code'])?>"><?=htmlspecialchars($d1['name'])?></option>
			<? } ?>
			</select>-
			<select id="pcode" onchange="changeCategory(2,this.value);">
			<?
				$ppcode = $info['ppcode'];
				$c2 = $category[$ppcode];
				foreach($c2['list'] as $d2) {
			?>
				<option value="<?=htmlspecialchars($d2['code'])?>"><?=htmlspecialchars($d2['name'])?></option>
			<? } ?>
			</select>-
			<select id="ccode" onchange="changeCategory(3,this.value);">
			<?
				$pcode = $info['pcode'];
				$c3 = $category[$ppcode]['list'][$pcode];
				foreach($c3['list'] as $d3) {
			?>
				<option value="<?=htmlspecialchars($d3['code'])?>"><?=htmlspecialchars($d3['name'])?></option>
			<? } ?>
			</select>
			<input type="button" value="MOVE" onclick="move_category();" />&nbsp;&nbsp;
			<input type="button" value="MAKE JS (<?=(empty($js_file) ? "NO" : "EXIST" )?>)" onclick="mk_js();" />
		</td>
	</tr>
	<tr>
		<td valign="top">
		<?	if (! empty($list)) { ?>
			<table id="list">
				<tr style="background-color:#ccc;font-weight:bold;">
					<td>EDIT</td>
					<td>seq</td>
					<td>from</td>
					<td>to</td>
					<td>mp3</td>
					<td>speaker</td>
					<td>script</td>
					<td>trans</td>
				</tr>
				<? foreach($list as $l) {
					$seq = htmlspecialchars($l['seq']);
					$from = (int)htmlspecialchars($l['from']);
					$to = (int)htmlspecialchars($l['to']);
					$sp = htmlspecialchars($l['speaker']);
					$sc = htmlspecialchars($l['script']);
					$tran = htmlspecialchars($l['trans']);
				?>
				<tr>
					<td><a href="#" onclick="edit('<?=$seq?>')" >#</a></td>
					<td><?=$seq?></td>
					<td><input id="from<?=$seq?>" value="<?=$from?>" size="5" /></td>
					<td><input id="to<?=$seq?>" value="<?=$to?>" size="5" /></td>
					<td>
						<a href="#" onclick="play('<?=$seq?>');">[PLAY]</a>
						<a href="#" onclick="show_mp3('<?=$seq?>');" >[+]</a>
						<input type="hidden" id="seq<?=$seq?>" value="<?=$seq?>" />
						<input type="hidden" id="url<?=$seq?>" value="<?=$l['mp3']?>" />
					</td>
					<td style="text-align:center;"><?=$sp?></td>
					<td><?=$sc?></td>
					<td><?=$tran?></td>
				</tr>
				<? } ?>
			</table>
		<? } else echo "<center><b>No Data</b></center>"; ?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
</html>
