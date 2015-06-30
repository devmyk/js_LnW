<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : <?=substr($ci['code'],0,2)?> <?=$ci['name']?></title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/common.js"></script>
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
	<script language="javascript" src="/js/dictation/common.js"></script>
	<script language="javascript" src="<?=$info['dir']?><?=$info['js']?>"></script>
	<script>
var sum = 0;
var curr = 0;
var sm;
var path = "<?=$info['dir']?>";
function setSm(dc) { // dc = data[curr]
	var isexurl = /^http/i;
	var obj = {id:dc.code};
	if (isexurl.test(dc.fn)) obj.url = dc.fn;
	else obj.url =  path + "mp3/" + dc.fn + ".mp3";
	if (typeof(dc['from']) !== "undefined") obj.from = dc['from'];
	if (typeof(dc['to']) !== "undefined") obj.to = dc['to']; 
	sm = soundManager.createSound(obj);
}
function init() {
	attachDialogList();
	sum = data.length;
	setSm(data[curr]);
}

function attachDialogList() {
	var list = document.getElementById("list");
	if (list) {
		var html = "";
		for(var i=0; i<data.length; i++) {
			var style = "";
			if (i%2==0) style="background-color:#111;";
			html += "<tr><td style=\"width:3em;"+style+"\" align=\"center\">";
			html += "<a href=\"#\" onclick=\"play("+i+")\">"+(startSeq + data[i]['seq'] + 1)+"</a></td>";
//			html += "<td style=\""+style+"\"><input id=\"from" + data[i]['seq'] +"\" type=\"text\" value=\"" + data[i].from +"\" size=\"5\" style=\"font-size:9pt;\"/></td>";
//			var to = 0;
//			if (data[i].to) to = data[i].to;
//			html += "<td style=\""+style+"\"><input id=\"to" + data[i]['seq'] +"\" type=\"text\" value=\"" + to +"\" size=\"5\"  style=\"font-size:9pt;\"/></td>";
			html += "<td style=\""+style+"\">"+data[i]['script']+"</td></tr>";
		}
		list.innerHTML = html;
	}
}

function play(seq) {
	sm.stop();
	setSm(data[seq]);
	sm.play();
}
	</script>
<? /*
	<script language="javascript" src="/data/<?=$dir?>/<?=$file?>.js"></script>
	<script language="javascript" src="/js/dictation/dictation.js"></script>
	<script>
dir = "<?=$dir?>";
file = "<?=$file?>";
path = "/data/<?=$dir?>/";
max = <?=$_set['max'];?>;
autoplay = <?=$_set['autoplay'];?>;
autopass = <?=$_set['autopass'];?>;
mode = "<?=$_set['defaultmode'];?>";
playCount = <?=$_set['playcount']?>;
maxFull = <?=$_set['maxfull'];?>;
maxWord = <?=$_set['maxword']?>;
	</script>
 */ ?>
</head>
<body onload="init();"<? /*attachRightList();" */ ?>>
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1><span><?=substr($ci['code'],0,2)?></span> <?=$ci['name']?></h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
		<a href="#rightpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-grid ui-nodisc-icon ui-alt-icon ui-btn-right">Search</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<table id="list" style="width:100%;">
		</table>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false" class="footer">
		<div id="bar" style="position:absolute; background-color:#38c; height:100%; width:1px; top:0; left:0; z-index: -1;"></div>
		<p id="progress" style="text-align:center;"></p>
	</div><!-- /footer -->

	<div data-role="panel" id="rightpanel" data-position="right" data-display="overlay" data-position-fixed="true" data-theme="b" data-dismissible="true">
	</div><!-- /rightpanel -->

	<? require_once("./app/views/dictation/left.php"); ?>
</div><!-- /page -->

</body>
</html>
