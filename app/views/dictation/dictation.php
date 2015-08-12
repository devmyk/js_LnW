<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : [<?=$info['pname']?>] <?=$info['name']?></title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/common.js"></script>
	<script language="javascript" src="/js/dictation/common.js"></script>
	<script language="javascript" src="/js/dictation/dictation.js"></script>
<script>
var sum = <?=$scr_info['sum']?>;
var curr = <?=$scr_info['curr']?>;
var mode = "<?=($u['defaultmode']=="full") ? "full" : "word";?>";
var autoplay = <?=empty($u['autoplay']) ? 0 : 1;?>;
var autopass = <?=empty($u['autopass']) ? 0 : 1;?>;
var playCount = <?=empty($u['autoplaycount']) ? 3 : $u['autoplaycount'];?>;
var maxFull = <?=empty($u['maxfull']) ? 3 : $u['maxfull'];?>;
var maxWord = <?=empty($u['maxword']) ? 3 : $u['maxword'];?>;
var max = <?=($u['defaultmode']=='full' ? $u['maxfull'] : $u['maxword'])?>;
var data = {
	db_seq : '<?=$scr_info['seq']?>'
	,code : '<?=$scr_info['code']?>'
	,fn : "<?=trim($scr_info['mp3'])?>"
	,from : <?=$scr_info['from']?>
	,to : <?=$scr_info['to']?>
	,script : "<?=trim($scr_info['script'])?>"
	,trans : "<?=trim($scr_info['trans'])?>"
	,word_no : 0
	,full_no : 0
	,answer : ""
	,is_try : 0
	,count : 0
	,correct : 0
};
var mark_v = "<?=$mark?>";
var mark = mark_v.split(",");
var log_word_v = "<?=$log_word?>";
var log_word = log_word_v.split(",");
var log_full_v = "<?=$log_full?>";
var log_full = log_full_v.split(",");
var validity_v = "<?=$validity?>";
var validity = validity_v.split(",");

function inputChar(e) {
	var put = document.getElementById("put");
	var c = e.innerText;
	if (put && trim(c) != "") {
		put.value = put.value + c;
	}
}
function log() {
	var f = document.result;
	f.l_mode.value = mode;
	f.l_answer.value = trim(data.answer);
	f.l_correct.value = data.correct;
	f.target = "iframe";
	f.action = "<?=base_url('c_dictation/set_log/')?>";
	f.submit();
}
function changeCurr(to) {
	if (to < 0 || to > sum-1) return;
	var url = "<?=base_url('c_dictation/dictation/'.$scr_info['code'])?>/"+to
	document.location = url;
}

function changeListBgColor(mode) {
	for (var i=0; i<sum; i++) {
		var btn = document.getElementById("list"+i);
		if (btn) {
			var is_corr = (mode == "full" ? log_full[i] : log_word[i]);
			if (is_corr === "1") {
				btn.style.backgroundColor = colorBlue;
			} else if (is_corr === "0") {
				btn.style.backgroundColor = colorRed;
			} else {
				btn.style.backgroundColor = "";
			}
		}
	}
}

function attachRightList() {
	document.getElementById("list").innerHTML = "";
	for (var i=0; i<sum; i++) {
		var btn = document.createElement("a");
		var cls = "ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini listbtn";
		for (var j=0; j<mark.length; j++) {
			if (i == curr) {
				cls += " shadow";
			}
			if (mark[j] == (i+1)) {
				cls += " listbtn-marked";
				break;
			}
			if (mark[j] > (i+1)) break;
		}
		if (validity[i] != "1") cls += " ui-state-disabled";
		btn.href = "#";
		btn.setAttribute("id", "list"+i);
		btn.setAttribute("data-rel", "close");
		btn.setAttribute("class",cls);
		btn.setAttribute("onclick", "changeCurr("+i+");$('[data-role=panel]').panel('close');");
		btn.innerHTML = (i+1);
		$("#list").append(btn);
	}
	changeListBgColor(mode);
}
</script>
</head>
<body onload="init();attachRightList();">
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1><span>[<?=$info['pname']?>]</span> <?=$info['name']?></h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-grid ui-nodisc-icon ui-alt-icon ui-btn-right">Search</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<iframe name="iframe" width="100%" height="150px" frameborder="0" style="margin:0;background:#888;<?=($u['permit']==9 ? "" : "display:none;")?>"></iframe>
	<form name="result" method="post" target="_self" onsubmit="return false;">
		<input type="hidden" name="l_code" value="<?=$scr_info['code']?>" />
		<input type="hidden" name="l_db_seq" value="<?=$scr_info['seq']?>" />
		<input type="hidden" name="l_no" value="<?=$scr_info['curr']?>" />
		<input type="hidden" name="l_mode" value="" />
		<input type="hidden" name="l_answer" value="" />
		<input type="hidden" name="l_correct" value="" />
		<table id="container" style="text-align:center;width:100%;min-height:50%;">
			<tr>
				<td style="vertical-align:top;text-align:left;">
					<input type="checkbox" data-role="flipswitch" name="mode" id="mode" data-on-text="full" data-off-text="word" data-wrapper-class="custom-label-flipswitch" data-mini="true" onchange="changeMode(this);" <?=($u['defaultmode'] == "full" ? "checked=\"checked\"" : "")?>/>
				</td>
				<td style="vertical-align:top;text-align:right;" class="ui-nodisc-icon">
					<!--
					<a id="btnAuto" class="ui-btn ui-icon-audio ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeAuto();">autoplay</a>
					<a id="btnMark" class="ui-btn ui-icon-star ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeMark();">mark</a>
					<a id="btnRecycle" class="ui-btn ui-icon-recycle ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="changeRecycle();" alt="recyle">recycle</a>
					-->
					<a id="btnRefresh" class="ui-btn ui-icon-refresh ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin" onclick="refresh();" alt="refresh">refresh</a>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%"><tr>
						<td style="width:30px;">
							<a href="#" id="btnPre" class="ui-btn ui-btn-icon-notext ui-icon-carat-l ui-nodisc-icon ui-btn-inline ui-btn-nomargin" style="height:90%;min-height:5em;" onclick="changeCurr(curr-1);">pre</a>
						</td>
						<td width="*">
							<div id="result" style="text-align:center;color:white;padding:1em 0.5em;" onclick="play();"></div>
						</td>
						<td style="width:30px;">
							<a href="#" id="btnNext" class="ui-btn ui-btn-icon-notext ui-icon-carat-r ui-nodisc-icon ui-btn-inline ui-btn-nomargin" style="height:90%;min-height:5em;" onclick="changeCurr(curr+1);">next</a>
						</td>
					</tr></table>
				</td>
			</tr>
			<tr>
				<td style="vertical-align:bottom;">
					<div id="count" style="text-align:left;color:#999;"></div>
				</td>
				<td style="vertical-align:bottom;">
					<div id="chars" style="text-align:right;">
					<?
						$lang = substr($scr_info['code'],0,2);
						if ($lang == "de") {
					?>
						<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-btn-char" onclick="inputChar(this);">ä</a>
						<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-btn-char" onclick="inputChar(this);">ö</a>
						<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-btn-char" onclick="inputChar(this);">ü</a>
						<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-btn-char" onclick="inputChar(this);">ß</a>
					<? } else if ($lang == "it") { ?>
					<? } ?>
					</div>
				</td>
			</tr>
			<tr><td colspan="2">
				<div id="full">
					<a href="#" id="btnSubmit" class="ui-btn ui-btn-icon-notext ui-icon-action ui-nodisc-icon ui-btn-inline" style="width:2.5em;height:2em;float:right;margin:.55em 0;" onclick="check_full();">enter</a>
					<div style="overflow:hidden; padding-right:.1em;">
					<!--input id="put" name="put" type="text" data-clear-btn="false" placeholder="" style="font-size:11pt;" onkeydown="check(event, this);" value="" / -->
					<textarea id="put" name="put" style="font-size:11pt;" onkeydown="check(event, this);"></textarea>
					</div>
				</div>
				<div id="fld"></div>
			</td></tr>
		</table>
		<?
			if ($u['permit'] == 9) {
				echo "<div>";
//				debug($scr_info, $u);
//				debug($mark, $log_word, $log_full);
//				debug($validity ,$sess);
				debug($sess);
				echo "</div>";
			}
		?>
	</form>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer">
		<div id="bar" style="position:absolute; background-color:#38c; height:100%; width:1px; top:0; left:0; z-index: -1;"></div>
		<p id="progress" style="text-align:center;"></p>
	</div><!-- /footer -->

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" data-position-fixed="true" data-theme="b" data-dismissible="true">
		<div id="list" style="text-align:left;"></div>
	</div><!-- /rightpanel -->

	<? require_once("./app/views/dictation/left.php"); ?>
</div><!-- /page -->

</body>
</html>
