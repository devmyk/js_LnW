<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>2015 rooms</title>

	<style type="text/css">
body {
	background-color: #fff;
	margin: 4px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}


a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

td {
	border-bottom:1px solid #AAA;
	border-right:1px solid #AAA;
	padding : 4px;
}
.td_first {
	border-left:1px solid #AAA;
}

.c {
	text-align: center;
}
.r {
	text-align: right;
}
.btn {
	background-color:#000;
	color:white;
	font-weight:bold;
	cursor: pointer;
	text-align: center;
	line-height:1.3em;
	font-size:1em;
    text-decoration: none;
	padding:0 0.3em;
}
#btnSend, .btnDel {
	background-color:#f22;
}
#btnAdd {
	background-color:#22f;
	padding:0 0.5em;
}
.ipt {
	width:95%;
}
	</style>
	<script type="text/javascript" src="js/common.js"></script>
	<script>
var els = ['app', 'image', 'no', 'url', 'addr', 'floors', 'lean', 'amount', 'manage', 'm_tv', 'm_internet', 'm_water', 'm_elec', 'm_gas', 'm_etc', 'forms', 'market'];
if (isMobile()) {
	document.location = "<?=base_url("/Room2015/m")?>";
}

function add() {
	var f = document.frm;
	f.submit();
}

function mod(seq) {
	var idx = 'frm'+seq;
	var f = document.forms[idx];
	f.action = "<?=base_url("Room2015/mod");?>";
	togRow(seq);
}

function del(seq) {
	var idx = 'frm'+seq;
	var f = document.forms[idx];
	f.action = "<?=base_url("Room2015/del");?>";
}

function togRow(seq) {
	var idx = 'frm'+seq;
	var f = document.forms[idx];
	var new_disabled = "disabled";
	if (f['app'].disabled) {
		new_disabled = "";
		f['image'].type = "text";
		f['btnDel'].style.display = "";
	} else {
		f['image'].type = "hidden";
		f['btnDel'].style.display = "none";
	}
	for (var i=0; i < els.length; i++) {
//		console.log(f[els[i]]);
		f[els[i]].disabled = new_disabled;
	}
}
function togMap() {
	var map = document.getElementById("imgMap");
	try {
		if (map.width <= 100) {
			map.width = "560";
		} else {
			map.width = "0";
		}
	} catch(e) {}
}
function togAdd() {
	var ebtnAdd = document.getElementById("btnAdd");
	var ebtnSend = document.getElementById("btnSend");
	var einput = document.getElementById("input");
	try {
		if (einput.style.display == "none") {
			einput.style.display = "";
			ebtnSend.style.display = "";
			ebtnAdd.innerHTML = "-";
		} else {
			einput.style.display = "none";
			ebtnSend.style.display = "none";
			ebtnAdd.innerHTML = "+";
		}
	} catch(e) {}
}
	</script>
</head>
<body>
<img id="imgMap" src="<?=base_url("images/map.png");?>" width="0" /><br />
<table width="1300px" cellspacing="0" cellpadding="0">
	<thead>
	<tr style="background-color:#ccc;font-weight:bold;">
		<td row_no="1" width="45" class="td_first c">
			<a href="#" id="btnAdd" class="btn" onclick="togAdd();">-</a>
			<a href="#" id="btnMap" class="btn" onclick="togMap();">M</a>
		</td>
		<td  row_no="2" width="70">app</td>
		<td  row_no="3" width="90">image</td>
		<td  row_no="4" width="140">no / url</td>
		<td  row_no="6" width="*">위치 / title</td>
		<td  row_no="7" width="50">층/전체</td>
		<td  row_no="8" width="30">대출</td>
		<td  row_no="9" width="50">전세금</td>
		<td row_no="10" width="40">관리비</td>
		<td row_no="11" width="250">TV/인터넷/수도/전기/가스/그외</td>
		<td row_no="12" width="60">㎡ / 평</td>
		<td row_no="13" width="40">방구조</td>
		<td row_no="14" width="100">중개소</td>
	</tr>
	</thead>
<tbody>
<form name="frm" method="post" action="<?=base_url("Room2015/add");?>">
<tr id="input" style="background-color:#EEB;">
	<td class="td_first c">
		<a href="#" id="btnSend" class="btn" onclick="add();">ADD</a>
	</td>
	<td>
		<select name="app">
			<option value="다방">다방</option>
			<option value="직방">직방</option>
			<option value="네이버">네이버</option>
			<option value="오프라인">오프라인</option>
		</select>
	</td>
	<td><input type="text" class="ipt" name="image"</td>
	<td><input type="text" class="ipt" name="no" /><br /><input type="text" class="ipt" name="url" /></td>
	<td>
		<input type="text" class="ipt" name="addr" />
		<br /><input type="text" class="ipt" name="title" />
	</td>
	<td><input type="text" class="ipt" name="floors" /></td>
	<td>
		<select name="lean">
			<option value="-">-</option>
			<option value="N">N</option>
			<option value="Y">Y</option>
		</select>
	</td>
	<td><input type="text" class="ipt" name="amount" /></td>
	<td><input type="text" class="ipt" name="manage" /></td>
	<td>
		<input type="checkbox" name="m_tv" />/
		<input type="checkbox" name="m_internet" />/
		<input type="checkbox" name="m_water" />/
		<input type="checkbox" name="m_elec" />/
		<input type="checkbox" name="m_gas" />/
		<input type="text" name="m_etc" size="8" value="" />
	</td>
	<td><input type="text" class="ipt" name="extent" /></td>
	<td>
		<select name="forms">
			<option value="오픈">오픈</option>
			<option value="분리">분리</option>
		</select>
	</td>
	<td><input type="text" class="ipt" name="market" /></td>

</tr>
</form>
<?
if (!empty($list)) {
	foreach($list as $row) {
		$is_out = ($row['lean'] == 'N' || $row['is_end'] == 'Y');
		$bgcolor = "";
		if ($row['lean'] == 'N') $bgcolor = "background-color:#DDD;";
		else if ($row['lean'] == 'Y') $bgcolor = "background-color:#CEF;";

		if ($row['is_end'] == 'Y') $bgcolor = "background-color:#AAA";
?>
<form name="frm<?=$row['seq']?>" method="post">
<tr style="<?=$bgcolor?>">
	<td class="td_first c">
		<input type="hidden" class="ipt" name="seq" value="<?=$row['seq']?>" />
		<?=$row['seq']?><br />
		<? if (empty($is_out)) { ?>
		<a name="btnEdit" class="btn" onclick="mod(<?=$row['seq']?>);">Edit</a>
		<? } ?>
	</td>
	<td>
		<select name="app" disabled="disabled">
			<option value="다방" <?=($row['app'] == "다방" ? "selected" : "");?>>다방</option>
			<option value="직방" <?=($row['app'] == "직방" ? "selected" : "");?>>직방</option>
			<option value="네이버" <?=($row['app'] == "네이버" ? "selected" : "");?>>네이버</option>
			<option value="오프라인" <?=($row['app'] == "오프라인" ? "selected" : "");?>>오프라인</option>
		</select>
		<br />
		<button name="btnDel" class="btn btnDel" style="display:none;" onclick="del(<?=$row['seq']?>);">del</button>
	</td>
	<td>
		<input type="hidden" class="ipt" name="image" disabled="disabled" value="<?=$row['image']?>" />
		<a href="<?=$row['url']?>" target="_blank">
		<img src="<?=$row['image']?>" height="50" />
		</a>
	</td>
	<td>
		<input type="text" class="ipt" name="no" disabled="disabled" value="<?=$row['no']?>" />
		<br /><input type="text" class="ipt" name="url" disabled="disabled" value="<?=$row['url']?>" />
	</td>
	<td>
		<input type="text" class="ipt" name="addr" disabled="disabled" value="<?=$row['addr']?>" />
		<br /><input type="text" class="ipt" name="title" disabled="disabled" value="<?=$row['title']?>" />
	</td>
	<td><input type="text" class="ipt" name="floors" disabled="disabled" value="<?=$row['floors']?>" /></td>
	<td>
		<select name="lean" disabled="disabled">
			<option value="-" <?=($row['lean'] == '-' ? "selected" : "");?>>-</option>
			<option value="N" <?=($row['lean'] == 'N' ? "selected" : "");?>>N</option>
			<option value="Y" <?=($row['lean'] == 'Y' ? "selected" : "");?>>Y</option>
		</select>
	</td>
	<td><input type="text" class="ipt" name="amount" disabled="disabled" value="<?=$row['amount']?>"/></td>
	<td><input type="text" class="ipt" name="manage" disabled="disabled" value="<?=$row['manage']?>"/></td>
	<td>
		<input type="checkbox" name="m_tv" disabled="disabled" <?=(empty($row['m_tv']) ? "" : "checked")?> />/
		<input type="checkbox" name="m_internet" disabled="disabled" <?=(empty($row['m_internet']) ? "" : "checked")?> />/
		<input type="checkbox" name="m_water" disabled="disabled" <?=(empty($row['m_water']) ? "" : "checked")?> />/
		<input type="checkbox" name="m_elec" disabled="disabled" <?=(empty($row['m_elec']) ? "" : "checked")?> />/
		<input type="checkbox" name="m_gas" disabled="disabled" <?=(empty($row['m_gas']) ? "" : "checked")?> />/
		<input type="text" name="m_etc" size="8" value="<?=$row['m_etc']?>" disabled="disabled" />
	</td>
	<td><input type="text" class="ipt" name="extent" disabled="disabled" value="<?=$row['extent']?>"/></td>
	<td>
		<select name="forms" disabled="disabled">
			<option value="오픈" <?=($row['forms'] == '오픈' ? "selected" : "");?>>오픈</option>
			<option value="분리" <?=($row['forms'] == '분리' ? "selected" : "");?>>분리</option>
		</select>
	</td>
	<td><input type="text" class="ipt" name="market" disabled="disabled" value="<?=$row['market']?>"/></td>
</tr>
</form>
<?
	}
}
?>
</tbody>
</table>
</body>
</html>
