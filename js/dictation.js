soundManager.setup({});

var sum = 0;
var curr = 0;
var max = 3;
var sm;
var timeout;
var sc = [];
var arr = [];
var mode = "full"; // full, words
var order = "asc";

function init() {
	sum = data.length;
	data[curr].curr = 0;
	data[curr].sum = 0;
	sc = [];
	arr = [];
	clearTimeout(timeout);

	document.getElementById("count").innerHTML = "[ 0 / " + max + " ]";
	document.getElementById("progress").innerHTML = "[ " + (curr+1) + " / " + sum + " ]";
	document.getElementById("fld").innerHTML = "";
	if (mode == "full") {
		document.getElementById("put").style.display = "";
		document.getElementById("put").value = "";
		document.getElementById("put").disabled = '';
//		document.getElementById("put").focus();
		document.getElementById("full").style.display = "";
	} else {
		document.getElementById("put").style.display = "none";
		document.getElementById("full").style.display = "none";
	}

	// book mark
	if (data[curr].mark == 1) {
		document.getElementById("btnMark").style.backgroundColor = "#38c";
	} else {
		document.getElementById("btnMark").style.backgroundColor = "";
	}

	// auto play
	if (autoplay) {
		document.getElementById("btnAuto").style.backgroundColor = "#38c";
	}

	// button
	if (sum <= 1) {
		$("#btnPre").addClass("ui-state-disabled");
		$("#btnNext").addClass("ui-state-disabled");
	} else if (curr==0) {
		$("#btnPre").addClass("ui-state-disabled");
		$("#btnNext").removeClass("ui-state-disabled");
	} else if (curr+1==sum) {
		$("#btnPre").removeClass("ui-state-disabled");
		$("#btnNext").addClass("ui-state-disabled");
	} else {
		$("#btnPre").removeClass("ui-state-disabled");
		$("#btnNext").removeClass("ui-state-disabled");
	}

	// 원래 sm 의 파일명과 같으면 생략
	// 이 부분 때문에 오류 나는 것 같기도 하고..?
	if (!sm) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: path + "mp3/" + data[curr].fn + ".mp3"});
	} else if (sm.id != "sm_" + data[curr].fn) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: path + "mp3/" + data[curr].fn + ".mp3"});
	}

	sc = data[curr].script.split(" ");
	var result = "";
	// view script hidden
	if (data[curr].try) {
		result = data[curr].script;
		document.getElementById("put").disabled = 'disabled';
	} else {
		for(var i=0; i < sc.length ; i++) {
			result += stringFill("_", sc[i].length) + " ";
			if (mode == "words") {
				arr[i] = { count:0, pass:0, correct:0, seq:i, obj:undefined, text: specialCharRemove(sc[i]) };
				var btn = document.createElement("a");
				btn.href = "#";
				btn.setAttribute("id", "btn"+i);
				btn.setAttribute("class","ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini");
				var value = specialCharRemove(sc[i], "innerHTML");
				btn.innerHTML = value;
				arr[i].obj = btn;
			}
		}
	}
	document.getElementById("result").innerHTML = result;
	document.getElementById("result").style.backgroundRepeat = "no-repeat";
	document.getElementById("result").style.backgroundSize = "contain";
	document.getElementById("result").style.backgroundPosition = "center";
	document.getElementById("result").style.backgroundImage = "url('/images/icons-svg/sm_play.svg')";

	// attach words
	if (mode == "words") {
		data[curr].sum = arr.length;
		data[curr].correct = true;
		arr.sort(function(a,b) {
			return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
		});
		for(var i=0; i < arr.length ; i++) {
			$(arr[i].obj).click(function() { check2(this); });
			$("#fld").append(arr[i].obj);
		}
	}

	// slider
	var width = Math.round((curr / sum) * 100);
	//document.getElementById("bar").style.width = width + "%";
	$("#bar").animate({width:width+"%"},400);

	if (sm && autoplay) play();
}

function refresh() {
	data[curr].try = 0;
	data[curr].pass = 0;
	data[curr].correct = 0;
	data[curr].timestamp = 0;
	init();
}

function play(o) {
//	if (sm.playState != 0) return;

	var res = document.getElementById("result");
	sm.stop("sm_" + data[curr].fn);

	if (typeof(o) == "undefined") o = {};
	if (data[curr].from) o.from = data[curr].from;
	if (data[curr].to) o.to = data[curr].to;
	if (! o.onfinish) {
		o.onfinish = function() {
			res.style.backgroundImage = "url('/images/icons-svg/sm_play.svg')";
		};
	}

	res.style.backgroundImage = "";
	sm.play(o);
}

function specialCharRemove(v, type) {
	if (typeof(v) != "string") return "";

	var re = /[ \{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\'\"\\(\=]/gi;

	if (type == "isHTML") {
		re = /[ \{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\"\\(\=]/gi;
	} else if (type == "check") {
		v = v.toLowerCase();
	}

	return v.replace(re, "");
}

function stringFill(x, n) {
	var s = ''; 
	while (s.length < n) s += x; 
	return s; 
}


function check2(o) {  // 값 비교
	var correct = false;
	var pass = false;

	// arr 의 count 증가
	arr[data[curr].curr].count++;
	document.getElementById("count").innerHTML = "[ " + arr[data[curr].curr].count + " / " + max + " ]";
	// arr 의 count 체크
	if (arr[data[curr].curr].count >= max) {
		pass = true;
		data[curr].correct = false;
	} else if (specialCharRemove(sc[data[curr].curr]) == specialCharRemove(o.innerText)) {
		// seq 는 다르지만 text 가 같은 경우도 있으므로 text 비교
		correct = true;
	}

	if (pass || correct) {
		$(document.getElementById('btn'+data[curr].curr)).remove();
		data[curr].curr++;
		if (data[curr].curr < data[curr].sum) { // 미완료
			var result = "";
			for(var i=0; i < sc.length ; i++) {
				if (i < data[curr].curr) result += sc[i] + " ";
				else result += stringFill("_", sc[i].length) + " ";
			}
			document.getElementById("count").innerHTML = "[ 0 / " + max + " ]";
			document.getElementById("result").innerHTML = result;
		} else { // 완료
			var dt = new Date();
			data[curr].try = 1;
			data[curr].timestamp = dt.getTime();
			document.getElementById("result").innerHTML = data[curr].script;
			document.getElementById("list"+curr).style.backgroundColor = (data[curr].correct ? "#38c" : "#c33");

			var o = (autopass ? {onfinish : function() { if ((curr+1) < sum) { curr++; init(); } } } : {});
			play(o);
		}
	}

}

function check_full() {
	if (data[curr].try) return;
	if (data[curr].correct || data[curr].pass) return;
	var o = document.getElementById("put");
	var correct = false;
	var pass = false;

	data[curr].count++;
	document.getElementById("count").innerHTML = "[ " + data[curr].count + " / " + max + "] ";

	var isCorr = [];
	var va = o.value.trim().split(" ").filter(function(v){ return (v != undefined && v != "") });
	if (!pass) {
		correct = true;
		var result = "";
		data[curr].answer = o.value.trim();

		for(var i=0; i < sc.length ; i++) {
			if (specialCharRemove(sc[i], "check") == specialCharRemove(va[i], "check")) {
				result += sc[i] + " ";
				isCorr[i] = 1;
			} else {
				result += stringFill("_", sc[i].length) + " ";
				isCorr[i] = 0;
				correct = false;
			}
		}
		document.getElementById("result").innerHTML = result;
	}

	if (data[curr].count > max || (data[curr].count == max && !correct)){
		pass = true;
	}


	if (correct || pass) {
		document.getElementById("put").disabled = 'disabled';
		var dt = new Date();
		data[curr].try = 1;
		data[curr].timestamp = dt.getTime();
		data[curr].correct = (pass ? 0 : 1);
		document.getElementById("list"+curr).style.backgroundColor = (data[curr].correct ? "#38c" : "#c33");

		var html = "";
		if (correct) {
			html = data[curr].script;
		} else {
			for(var i=0; i < sc.length ; i++) {
				if (isCorr[i]) html += sc[i] + " ";
				else html += "<span>" + sc[i] + "</span> ";
			}
		}
		document.getElementById("result").innerHTML = html;

		var o = (autopass ? { onfinish : function() {	if ((curr+1) < sum) { curr++; init(); }	} } : {});
		play(o);
	}
}

function check(e, o) {  // 값 비교
	if (e.which == 13 || e.keyCode == 13) {
		if (isMobile) play();
		else check_full();
	}
}

function changeMode(o) {
	if (o.checked) {
		mode = "full";
	} else {
		mode = "words";
	}
	init();
}

function changeMark() {
	var o = document.getElementById("btnMark");
	if (data[curr].mark) {
		data[curr].mark = 0;
		if (o) o.style.backgroundColor = "";
		$("#list"+data[curr].seq).removeClass("listbtn-marked");
	} else {
		data[curr].mark = 1;
		if (o) o.style.backgroundColor = "#38c";
		$("#list"+data[curr].seq).addClass("listbtn-marked");
	}
}

function changeAuto() {
	var o = document.getElementById("btnAuto");
	if (autoplay) {
		autoplay = 0;
		if (o) o.style.backgroundColor = "";
	} else {
		autoplay = 1;
		if (o) o.style.backgroundColor = "#38c";
	}
}

function changeAutoPass() {
	if (autopass) {
		autopass = 0;
	} else {
		autopass = 1;
	}
}

function changeRecycle() {
	var o = document.getElementById("btnRecycle");
	if (isRecycle) {
		isRecycle = 0;
		if (o) o.style.backgroundColor = "";
	} else {
		isRecycle = 1;
		if (o) o.style.backgroundColor = "#38c";
	}
}

function changeCurr(to) {
	if (to < 0 || to > sum-1) return;
	curr = to;
	init();
}

// asc, marked, incorrected, shuffle
function changeSort(v) {
	console.log(v);
	if (v == "asc" && order != "asc") {
		order = v;
		data.sort(function(a,b) {
			return a.seq < b.seq ? -1 : a.seq > b.seq ? 1 : 0;
		});
	} else if (v == "marked" && order != "marked") {
		order = v;
		var t1 = new Array();
		var t2 = new Array();
		for (var i = 0; i < data.length; i++) {
			if (data[i].mark) t1.push(data[i]);
			else t2.push(data[i]);
		}
		data = t1.concat(t2);
	} else if (v == "incorrected" && order != "incorrected") {
		order = v;
		var t1 = new Array();
		var t2 = new Array();
		var t3 = new Array();
		for (var i = 0; i < data.length; i++) {
			if (!data[i].correct) {
				if (data[i].timestamp) t1.push(data[i]);
				else t2.push(data[i]);
			} else t3.push(data[i]);
		}
		data = t1.concat(t2).concat(t3);
	} else if (v == "shuffle" && order != "shuffle") {
		order = v;
		data.shuffle();
	} else {
		return;
	}
	attachRightList();
	changeCurr(0);
}

function attachRightList() {
	document.getElementById("list").innerHTML = "";
	for(var i=0; i < data.length ; i++) {
		var btn = document.createElement("a");
		var cls = "ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini listbtn";
		if (data[i].mark) cls += " listbtn-marked";
		if (data[i].timestamp != 0) {
			btn.style.backgroundColor = (data[i].correct ? "#38c" : "#c33");
		}
		btn.href = "#";
		btn.setAttribute("id", "list"+data[i].seq);
		btn.setAttribute("data-rel", "close");
		btn.setAttribute("class",cls);
		btn.setAttribute("onclick", "changeCurr("+i+");$('[data-role=panel]').panel('close');");
		btn.innerHTML = (data[i].seq+1+startSeq);
		$("#list").append(btn);
	}
}

