soundManager.setup({});

var sum = 0;
var curr = 0;
var sm;
var order = "asc";

function init() {
	sum = data.length;
//	getRecords();
	attachDialogList();
	setSm(data[curr]);
}

function setSm(dc) // dc = data[curr];
{
	var isexurl = /^http/i;
	var isexurl2 = /\.mp3$/i;
	var obj = {id:dc.code};

	if (isexurl.test(dc.fn)) obj.url = dc.fn;
	else if(isexurl2.test(dc.fn)) obj.url =  path + "mp3/" + dc.fn;
	else obj.url =  path + "mp3/" + dc.fn + ".mp3";

	if (typeof(dc['from']) !== "undefined") obj.from = dc['from'];
	if (typeof(dc['to']) !== "undefined") obj.to = dc['to']; 

	sm = soundManager.createSound(obj);
}

function attachDialogList() {
	if (data.length <= 0) return;
	var table = document.getElementById("list");
	if (!table) return;
	var html = "";
	for(var i=0; i<data.length; i++) {
		var style = "";
		if (i%2==0) style="background-color:#111;";
		html += "<tr><td style=\"width:3em;"+style+"\" align=\"center\"><a href=\"#\" onclick=\"play("+i+")\">"+(startSeq + data[i]['seq'] + 1)+"</a></td>";
		html += "<td style=\""+style+"\">"+data[i]['script']+"</td></tr>";
	}
	table.innerHTML = html;
}

function play(o) {
//	if (sm.playState != 0) return;

	sm.stop(data[curr].code);
	var id = "scripts" + data[curr].seq;
	var e = document.getElementById(id);
	if (e) {
		e.style.backgroundColor = colorBlue;
		var pos = $(e).offset();
//		$("span[name=" + id + "]").css("backgroundColor", colorBlue);
		var wh = $(window).height();
		$('body').scrollTop(pos.top - (wh/3) );
	}

	if (typeof(o) == "undefined") o = { from: 0 };
	if (data[curr].from) o.from = data[curr].from;
	if (data[curr].to) o.to = data[curr].to;
//	if (! o.onfinish) {
//		o.onfinish = function() {
//		};
//	}

	sm.play(o);
}








function changeMark(i) {
	var ol = document.getElementById("btnMark"+i);
	if (data[i].mark) {
		data[i].mark = 0;
		if (ol) ol.style.backgroundColor = "";
		$("#list"+data[i].seq).removeClass("listbtn-marked");
	} else {
		data[i].mark = 1;
		if (ol) ol.style.backgroundColor = colorBlue;
		$("#list"+data[i].seq).addClass("listbtn-marked");
	}
}

function changeMarks() {
	var from = document.getElementById("mark_from").value;
	var to = document.getElementById("mark_to").value;
	// 예외처리 해야 하는데 귀찮네..
	if ((from - startSeq - 1) >= 0) {
		from = from - startSeq - 1;
		to = to - startSeq - 1;
	}
	for (var i=0; i<data.length; i++) {
		var ol = document.getElementById("btnMark"+i);
		if (i >= from && i <= to) {
			data[i].mark = 1;
			if (ol) ol.style.backgroundColor = colorBlue;
			$("#list"+data[i].seq).removeClass("listbtn-marked");
		} else {
			data[i].mark = 0;
			if (ol) ol.style.backgroundColor = "";
			$("#list"+data[i].seq).addClass("listbtn-marked");
		}
	}
}

function changeRecycle() {
	var o = document.getElementById("playRecycle");
	if (isRecycle) {
		isRecycle = 0;
		if (o) o.checked = "";
		sm.pause();
		sm.stop();
	} else {
		isRecycle = 1;
		if (o) o.checked = "checked";
	}
}

var loopcount = 0;
var stop = 0;
var playIdx = 0;
var playArr = new Array();
function playloop(mode, reset) {
	if (reset) {
		stop = sm.playState;
		loopcount = 0;
		playidx = 0;
		playArr = [];
	}
	if (stop) return;
	$(".scripts").css("background-color", "");
	if (playArr.length == 0) {
		if (typeof(mode) == "undefined") {
			playArr.push({key:curr, seq:data[curr].seq});
		} else {
			for (var i=0; i<sum; i++) {
				if (mode == "marked") {
					if (data[i].mark) {
						playArr.push({key:i, seq:data[i].seq});
					}
				} else if (mode == "all" || mode == "shuffle") {
					playArr.push({key:i, seq:data[i].seq});
				}
			}
			if (mode == "shuffle") {
				playArr.shuffle();
			}
		}
	}
	if (playArr.length == 0) {
		playIdx = 0;
		return;
	}

	var n = parseInt(document.getElementById("playCount").value); // 나중에 max 로 변경해야 함
	loopcount++;

	if (curr != playArr[playIdx].key) {
		curr = playArr[playIdx].key;
		setSm(data[curr]);
	}
	var o = {};
	var func = function() {
		if (loopcount < n) { playloop(mode); }
		else {
			loopcount = 0;
			if (playIdx+1 < playArr.length) {
				if (mode) {
						playIdx++;
						curr = playArr[playIdx].key;
//						document.getElementById("currentNo").innerHTML = "No. "+playArr[playIdx].seq;
						setSm(data[curr]);
						playloop(mode);
				}
			} else {
				if (isRecyle) {
					loopcount = 0;
					playidx = 0;
					curr = playArr[playIdx].key;
					setSm(data[curr]);
					playloop(mode);
				} else {
					playArr = [];
					playIdx = 0;
					$(".scripts").css("background-color", "");
				}
			}
		}
	};
	if (data[playArr[playIdx].key].to) o.onstop = func;
	else o.onfinish = func;

	if (stop != 1) {
		play(o);
		stop = 0;
	}
}

function getRecords() {
	for(var i=0; i<sum; i++) {
		getRecord(i);
	}
}

function getRecord(i) {
	$.ajax({
		type: "POST",
		url : "/record.php",
		data : {dir:dir, file:file, type:"get", seq: data[i].seq},
		dataType: "text",
		success : function(result){
			if (result.trim() == "") return;
			var res = result.trim().split("\t"); // 0:seq/1:mark/2:correct/3:try
			data[i].mark = parseInt(res[1]);
			var e = document.getElementById("btnMark"+data[i].seq);
			if (e) {
				if (data[i].mark == 1) {
					e.style.backgroundColor = "#38c";
				} else {
					e.style.backgroundColor = "";
				}
			}
		}
	});
}
