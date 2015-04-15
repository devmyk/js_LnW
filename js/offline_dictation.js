soundManager.setup({useConsole:false});

var sum = 0;
var curr = 0;
var max = 3;
var maxWord = 3;
var maxFull = 1;
var sm;
var timeout;
var sc = [];
var sa = []; // isCorr 와 같은 기능
var arr = [];
var mode = "words"; // full, words
var order = "asc";
var colorBlue = "#38c";
var colorRed = "#c33";
var autopass = false;
var autoplay = true;

// dir, js file name
function set(p, js) {
	if (p) path = p;
	if (js) _js = path + js + ".js";
	data = [];

	var oe = document.getElementById("scriptJs");
	if (oe) {
		oe.remove();
	}
	var e = document.createElement('script');
	e.setAttribute("type","text/javascript");
	e.setAttribute("src", _js);
	e.setAttribute("id", "scriptJs");
	document.getElementsByTagName("head")[0].appendChild(e);
	$("#scriptJs").load(function() {
		var ec = document.getElementById("container");
		if (ec) ec.style.display = "";
		attachRightList();
		curr = 0;
		var ttl = document.getElementById("title");
		if (ttl) ttl.innerHTML = js;
		init();
	});
}

function init() {
	sum = data.length;
	data[curr].curr = 0;
	data[curr].sum = 0;
	sc = [];
	sa = [];
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
		document.getElementById("mode").checked = "checked";
	} else {
		document.getElementById("put").style.display = "none";
		document.getElementById("full").style.display = "none";
		document.getElementById("mode").checked = "";
	}

	// book mark
	if (data[curr].mark == 1) {
		document.getElementById("btnMark").style.backgroundColor = colorBlue;
	} else {
		document.getElementById("btnMark").style.backgroundColor = "";
	}

	// auto play
	if (autoplay) {
		document.getElementById("btnAuto").style.backgroundColor = colorBlue;
	}

	// button
	if (sum <= 1) {
		$("#btnPre").addClass("ui-state-disabled");
		$("#btnNext").addClass("ui-state-disabled");
	} else if (curr == 0) {
		$("#btnPre").addClass("ui-state-disabled");
		$("#btnNext").removeClass("ui-state-disabled");
	} else if (curr+1 == sum) {
		$("#btnPre").removeClass("ui-state-disabled");
		$("#btnNext").addClass("ui-state-disabled");
	} else {
		$("#btnPre").removeClass("ui-state-disabled");
		$("#btnNext").removeClass("ui-state-disabled");
	}

	// 원래 sm 의 파일명과 같으면 생략
	if (!sm) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: path + "mp3/" + data[curr].fn + ".mp3"});
	} else if (sm.id != "sm_" + data[curr].fn) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: path + "mp3/" + data[curr].fn + ".mp3"});
	}

	sc = data[curr].script.split(" ");
	var result = "";
	// view script hidden
	if (data[curr].try) {
		result = getResultText(data[curr].script, data[curr].answer, "red");
		document.getElementById("put").disabled = 'disabled';
	} else {
		for(var i=0; i < sc.length ; i++) {
			sa[i] = 0;
			result += stringFill("_", sc[i].length) + " ";
			if (mode == "words") {
				arr[i] = { count:0, pass:0, correct:0, seq:i, obj:undefined, text: specialCharRemove(sc[i]) };
				var btn = document.createElement("a");
				btn.href = "#";
				btn.setAttribute("id", "btn"+i);
//				btn.setAttribute("seq", i);
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

	// attach words
	if (mode == "words") {
		data[curr].sum = arr.length;
		data[curr].correct = 0;
		arr.sort(function(a,b) { return a.text < b.text ? -1 : a.text > b.text ? 1 : 0; });
		for(var i=0; i < arr.length ; i++) {
//			arr[i].obj.setAttribute("ak", i);
			$(arr[i].obj).click(function() { check_words(this); });
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
	data[curr].answer = "";
	init();
}

function play(o) {
//	if (sm.playState != 0) return;

	var res = document.getElementById("result");
	sm.stop("sm_" + data[curr].fn);

	if (typeof(o) == "undefined") o = { from: 0 };
	if (data[curr].from) o.from = data[curr].from;
	if (data[curr].to) o.to = data[curr].to;

	res.style.backgroundImage = "";
	sm.play(o);
}

function specialCharRemove(v, type) {
	if (typeof(v) == "undefined") return "";
	else if (typeof(v) != "string") v = String(v);

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

// mode 가 words 일 때 값 비교
function check_words(o) {
	var correct = false;
	var pass = false;
	var ov = o.innerHTML.trim();

	// arr 의 count 증가
	arr[data[curr].curr].count++;
	document.getElementById("count").innerHTML = "[ " + arr[data[curr].curr].count + " / " + max + " ]";
	// arr 의 count 체크
	// seq 는 다르지만 text 가 같은 경우도 있으므로 text 비교
	var chk = (specialCharRemove(sc[data[curr].curr], "check") == specialCharRemove(ov, "check"));
	if (arr[data[curr].curr].count > max) {
		pass = true;
		data[curr].correct = 0;
	} else if (arr[data[curr].curr].count == max) {
		pass = !(chk);
		correct = chk;
	} else if (chk) {
		correct = true;
	}

	if (pass || correct) {
		$(document.getElementById('btn'+data[curr].curr)).remove();
		sa[data[curr].curr] = correct;
		data[curr].answer += ov + " ";
		data[curr].curr++;
		
		var result = "";
		for(var i=0; i < sc.length; i++) {
			if (i < data[curr].curr) {
				if (sa[i] == true) result += sc[i] + " ";
				else result += "<span>" + sc[i] + "</span> ";
			}
			else result += stringFill("_", sc[i].length) + " ";
		}
		document.getElementById("result").innerHTML = result;

		if (data[curr].curr < data[curr].sum) { // 미완료
			document.getElementById("count").innerHTML = "[ 0 / " + max + " ]";
		} else { // 완료
			var dt = new Date();
			data[curr].try = 1;
			data[curr].timestamp = dt.getTime();
			data[curr].answer = data[curr].answer.trim();
			data[curr].correct = (specialCharRemove(data[curr].answer, "check") == specialCharRemove(data[curr].script, "check")) ? 1 : 0;
			document.getElementById("list"+data[curr].seq).style.backgroundColor = (data[curr].correct ? colorBlue : colorRed);

			var o = (autopass ? {onfinish : function() { if ((curr+1) < sum) { curr++; init(); } } } : {});
			play(o);
		}
	}

}

// mode 가 full 일 때 mobile 여부 판단하여 check
function check(e, o) {  // 값 비교
	if (e.which == 13 || e.keyCode == 13) {
		if (isMobile) play();
		else check_full();
	}
}

function check_full() {
	if (data[curr].try) return;
	if (data[curr].correct || data[curr].pass) return;
	var o = document.getElementById("put");
	var correct = false;
	var pass = false;

	if (! o) return;
	data[curr].count++;
	data[curr].answer = o.value.trim();
	document.getElementById("count").innerHTML = "[ " + data[curr].count + " / " + max + " ]";

	var isCorr = [];
	var va = o.value.trim().split(" ").filter(function(v){ return (v != undefined && v != "") });
	if (! pass) {
		correct = true;
		var result = "";
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

		var e_list = document.getElementById("list"+data[curr].seq);
		if (e_list) e_list.style.backgroundColor = (data[curr].correct ? colorBlue : colorRed);

		var e_result = document.getElementById("result");
		if (e_result) e_result.innerHTML = getResultText(data[curr].script, data[curr].answer, "red");


		var o = (autopass ? { onfinish : function() {	if ((curr+1) < sum) { curr++; init(); }	} } : {});
		play(o);
	}
}

function getResultText(script, answer, type) {
	var result = "";
	var arr_a = answer.trim().split(" ").filter(function(v){ return (v != undefined && v != "") });
	var arr_s = script.trim().split(" ");

	for(var i=0; i < arr_s.length ; i++) {
		if (specialCharRemove(arr_s[i], "check") == specialCharRemove(arr_a[i], "check")) {
			result += arr_s[i] + " ";
		} else {
			if (type == "red") {
				result += "<span>" + arr_s[i] + "</span> ";
			} else {
				result += stringFill("_", arr_s[i].length) + " ";
			}
		}
	}
	return result;
}

function changeMode(o) {
	if (o.checked) {
		mode = "full";
		max = maxFull;
	} else {
		mode = "words";
		max = maxWord;
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
		if (o) o.style.backgroundColor = colorBlue;
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
		if (o) o.style.backgroundColor = colorBlue;
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
		sm.pause();
		sm.stop();
	} else {
		isRecycle = 1;
		if (o) o.style.backgroundColor = colorBlue;
		playloop();
	}
}

function playloop() {
	var func = function() {
		playloop();
	}
	var o = {onfinish: func};
	play(o);
}

function changeCurr(to) {
	if (to < 0 || to > sum-1) return;
	curr = to;
	init();
}

// asc, desc, marked, incorrected, shuffle
function changeSort(v) {
	console.log(v);
	sm.stop();
	if (v == "asc" && order != "asc") {
		order = v;
		data.sort(function(a,b) { return a.seq - b.seq; });
	} else if (v == "desc" && order != "desc") {
		order = v;
		data.sort(function(a,b) { return b.seq - a.seq; });
	} else if (v == "marked" && order != "marked") {
		order = v;
		// 오답 > 안함 > 정답
		data.sort(function(a,b) { return b.mark - a.mark; });
	} else if (v == "incorrected" && order != "incorrected") { 
		order = v;
		var t1 = new Array();
		var t2 = new Array();
		var t3 = new Array();
		for (var i = 0; i < data.length; i++) {
			if (! data[i].correct) {
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
			btn.style.backgroundColor = (data[i].correct ? colorBlue : colorRed);
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

function attachLeftList() {
	var leftpanel = document.getElementById("leftpanel");
	if (!leftpanel) return;
	if (document.getElementById("leftListView")) return;
	document.getElementById("defaultListView").remove();

	$.get("left.html", function(template) {
		$(template).prependTo("#leftpanel");
		$("[data-role=listview]").listview();
		$("[data-role=collapsible]").collapsible();
	}, "html");
}

