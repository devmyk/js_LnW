soundManager.setup({});

var sum = 0;
var curr = 0;
var sm;
var order = "asc";

function init() {
	sum = data.length;
	getRecords();
	attachDialogList();
	attachDialogList2();
	setSm(data[curr]);
}

function setSm(dc) // dc = data[curr];
{
	var isexurl = /^http/i;
	if (isexurl.test(dc.fn)) {
		sm = soundManager.createSound({id: dc.code , url: dc.fn});
	} else {
		sm = soundManager.createSound({id:dc.code , url: path + "mp3/" + dc.fn + ".mp3"});
	}
}

function attachDialogList() {
	if (data.length <= 0) return;
	var table = document.getElementById("contain");
	if (!table) return;
	for (var i=0; i<data.length; i++) {
		var tr = document.createElement("tr");
		var no = parseInt(data[i].seq) + startSeq + 1;
		tr.innerHTML = '<td><a id="btnMark'+data[i].seq+'" class="ui-btn ui-icon-star ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin ui-nodisc-icon" onclick="changeMark('+data[i].seq+');">mark</a></td>';
		tr.innerHTML += '<td>'+no+'</td>';
		tr.innerHTML += '<td style="text-align:left;padding:0.5em 0.25em;"><span class="scripts" id="scripts'+data[i].seq + '">' + data[i].script + '</span></td>';
		$("#contain").append(tr);
	}
}

function attachDialogList2() {
	if (data.length <= 0) return;
	var table = document.getElementById("justlist");
	if (!table) return;
	for (var i=0; i<data.length; i++) {
		var tr = document.createElement("tr");
		tr.innerHTML += '<td style="text-align:left;padding:0.5em 0.25em;"><span class="scripts" name="scripts'+data[i].seq + '">' + data[i].script + '</span></td>';
		$("#justlist").append(tr);
	}
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
