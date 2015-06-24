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
var sum = 0;
var arr = [];

function row_remove(no) {
//	if (no > 0) {
	var tr = document.getElementById('tr'+no);
	if (tr) {
		tr.remove();
		arr[no] = undefined;
	}
}

function row_add(obj) {
	var list = document.getElementById('list');
	var html = '';
	if (list) {
		if (typeof(obj) == "undefined") {
			obj = {no:sum, from:0, to:0, speaker:'', mp3:'', script:'', trans:''};
		}
		var tr = document.createElement("tr");
		tr.setAttribute('id', "tr"+obj.no);
		html += '<td>';
		html += '<a href="#" onclick="row_add();">+</a>&nbsp;';
		html += '<a href="#" onclick="row_remove('+obj.no+');">-</a>&nbsp;&nbsp;';
		html += '<input type="text" name="no" value="'+obj.no+'" size="1" onchange="arr['+obj.no+'].no =parseInt(this.value);" />&nbsp;';
		html += '</td>';
		html += '<td><a href="#" onclick="play('+obj.no+');">[PLAY]</a>';
		html += '<td><input type="text" name="from" value="'+obj.from+'" size="5" style="text-align:right;" onchange="arr['+obj.no+'].from = parseInt(this.value);" /></td>';
		html += '<td><input type="text" name="to" value="'+obj.to+'" size="5" style="text-align:right;" onchange="arr['+obj.no+'].to = parseInt(this.value);" /></td>';
		html += '<td><input type="text" name="speaker" value="'+obj.speaker+'" size="3" onchange="arr['+obj.no+'].speaker = this.value;" /></td>';
		html += '<td><input type="text" name="mp3" value="'+obj.mp3+'" onchange="arr['+obj.no+'].mp3 = this.value;" /></td>';
		html += '<td><textarea name="script" rows="1" onchange="arr['+obj.no+'].script = this.value;" >'+obj.script+'</textarea></td>';
		html += '<td><textarea name="trans" rows="1" onchange="arr['+obj.no+'].trans = this.value;" >'+obj.trans+'</textarea></td>';
		tr.innerHTML = html;
		list.appendChild(tr);
		arr[obj.no] = obj;
		sum++;
	}
}

function play(no)
{
	if (sm) sm.stop();
	if (arr[no].mp3 == "") return;

	var o = {from : 0};
	if (sm) {
		sm.url = arr[no].mp3;
		o.from = arr[no].from;
		o.to = arr[no].to;
	} else {
		var obj = {id: "sm", from: 0, url: arr[no].mp3};
		if (arr[no].from) {
			obj.from = arr[no].from;
			o.from = arr[no].from;
		}
		if (arr[no].to) {
			obj.to = arr[no].to;
			o.to = arr[no].to;
		}
		sm = soundManager.createSound(obj);
	}
	sm.play(o);
}

function sort() {
	var list = document.getElementById("list");
	if (list && arr.length > 0) {
		list.innerHTML = "";
		var n = 0;
		var tmp = arr;
		tmp.sort(function(a,b) { return a.no - b.no; });
		arr = [];
		for (var i=0; i<tmp.length; i++) {
			if (typeof(tmp[i]) == "undefined") continue;
			arr[n] = tmp[i];
			arr[n].no = n;
			n++;
		}
		sum = n;
		for (var i=0; i<arr.length; i++) {
			row_add(arr[i]);
		}
	}
}

function edit(seq) {
	window.open("", "wEdit", "width=300,height=200");
	var f = document.fm;
	// is_change?
	f.action = "<?=base_url("c_dictation/ad_script_edit");?>";
	f.target = "wEdit";
	f.submit();
}

function to_tr() {
	var e = document.getElementById("deco");
	if (!e) { console.log("no el"); return; }
	var text = e.value;
	if (text == "") { console.log("no text"); return; }
	var tmp = [];
	var rows = text.split("\n");
	var n = 0;
	for (var i=0; i<rows.length; i++) {
		var row = rows[i].split("\t");
		//  no / mp3 / speaker / from / to / script / trans
		if (row.length != 7) continue;
		if (row[0] == "no" || row[0] == "") continue;
		tmp[n] = {no:n, mp3:row[1], speaker:row[2], from:row[3], to:row[4], script:row[5], trans:row[6]};
		n++;
	}
	var list = document.getElementById("list");
	if (list && tmp.length > 0) {
		list.innerHTML = "";
		arr = tmp;
		for (var i=0;i<tmp.length;i++) {
			row_add(tmp[i]);
		}
		sum = arr.length;
		e.innerHTML = "";
	}
}

function to_textarea() {
	var e = document.getElementById("deco");
	if (!e) return;
	sort();
	var txt = "";
	txt += "no\tmp3\tspeaker\tfrom\tto\tscript\ttrans\n";
	for (var i=0; i<arr.length; i++) {
		// dbseq / mp3 / speaker / from / to / script / trans
		var r = arr[i];
		txt += r.no + "\t";
		txt+= r.mp3  + "\t";
		txt += r.speaker + "\t";
		txt += r.from + "\t";
		txt += r.to + "\t";
		txt += r.script + "\t";
		txt += r.trans  + "\n";
	}
	e.innerHTML = txt;
}
</script>
<style>
body, table, td { font-size:10pt }
table {
	border-collapse: separate;
	border-spacing:0;
}
.script td {
	padding : 0.1em 0.5em;
	border : 1px solid #bbb;
	border-left : 0px none;
	border-top : 0px none;
}
</style>
</head>
<body onload="row_add();">
<form name="fm" method="post">
<table width="100%">
	<tr><td colspan="2"><?//debug($info);?></td></tr>
	<tr>
		<td width="130px" valign="top" rowspan="2" style="border-right:1px solid #aaa;"><!-- tables -->
        <? require_once("./app/views/dictation/admin/left.php"); ?>
		</td>
		<td valign="top" height="1em" style="border-bottom:1px solid #aaa;">
			code : <input type="text" id="code" value="" /><br />
			<input type="button" value="To textarea" onclick="to_textarea();" />
			<input type="button" value="stop" onclick="sm.stop();" />
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table class="script">
				<thead>
					<tr style="background-color:#ccc;font-weight:bold;text-align:center;">
						<td width="100px"><a href="#" onclick="sort();">order</a></td>
						<td>play</td>
						<td>from</td>
						<td>to</td>
						<td>speaker</td>
						<td>mp3</td>
						<td>script</td>
						<td>trans</td>
					</tr>
				</thead>
				<tbody id="list"></tbody>
			</table>
		</td>
	</tr>
</table>
</form>
<center>
	<textarea id="deco" cols="100"></textarea><br>
	<input type="button" value="To tr" onclick="to_tr();" />
</center>
</body>
</html>
</html>
