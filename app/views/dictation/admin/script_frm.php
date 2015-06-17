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
var no = 0;

function row_remove(no) {
//	if (no > 0) {
	var tr = document.getElementById('tr'+no);
	if (tr) {
		tr.remove();
	}
}
function row_add() {
	var table = document.getElementById('list');
	var html = '';
	if (table) {
		console.log(no);
		html = '<tr id="tr'+no+'">';
		html += '<td><input type="checkbox" name="chk" value="'+no+'"/></td>';
		html += '<td>';
		html += '<a href="#" onclick="" >up</a>&nbsp;';
		html += '<a href="#" onclick="">down</a>&nbsp;';
		html += '<a href="#" onclick="row_add();">+</a>&nbsp;';
		html += '<a href="#" onclick="row_remove('+no+');">-</a>&nbsp;';
		html += '</td>';
		html += '<td><a href="#" onclick="play('+no+');">[PLAY]</a>';
		html += '<td><input name="from" value="0" size="5" /></td>';
		html += '<td><input name="to" value="" size="5" /></td>';
		html += '<td><input name="speaker" value="" size="3" /></td>';
		html += '<td><input name="mp3" value="" /></td>';
		html += '<td><textarea name="script"></textarea></td>';
		html += '<td><textarea name="trans"></textarea></td>';
		html += '</tr>';
		table.innerHTML += html;
		no++;
	}
}

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

</script>
<style>
body, table, td { font-size:10pt }
</style>
</head>
<body onload="row_add();">
<form name="fm" method="post">
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
			<input type="text" id="code" value="" />
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table id="list">
				<tr style="background-color:#ccc;font-weight:bold;">
					<td><input type="checkbox" id="chk_all" /></td>
					<td>order</td>
					<td>play</td>
					<td>from</td>
					<td>to</td>
					<td>speaker</td>
					<td>mp3</td>
					<td>script</td>
					<td>trans</td>
				</tr>
				<!--
				<tr>
					<td><input type="checkbox" name="chk" value="0"/></td>
					<td>
						<a href="#" onclick="" >[up]</a>
						<a href="#" onclick="">[down]</a>
						<a href="#" onclick="">[+]</a>
						<a href="#" onclick="">[-]</a>
					</td>
					<td><a href="#" onclick="play('0');">[PLAY]</a>
					<td><input name="from" value="0" size="5" /></td>
					<td><input name="to" value="" size="5" /></td>
					<td><input name="speaker" value="" size="5" /></td>
					<td><input name="mp3" value="" /></td>
					<td><textarea name="script"><textarea></td>
					<td><textarea name="trans"><textarea></td>
				</tr>
				-->
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
</html>
