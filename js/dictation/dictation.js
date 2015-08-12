soundManager.setup({});
//soundManager.setup({useConsole:false});

var sm;
var sc = [];
var sa = []; // isCorr 와 같은 기능
var arr = [];

function init() {
	sc = [];
	sa = [];
	arr = [];

	document.getElementById("count").innerHTML = "[ 0 / " + max + " ]";
	document.getElementById("progress").innerHTML = "[ " + (curr+1) + " / " + sum + " ]";
	document.getElementById("fld").innerHTML = "";
	if (mode == "full") {
		document.getElementById("put").style.display = "";
		document.getElementById("put").value = "";
		document.getElementById("put").disabled = '';
		document.getElementById("full").style.display = "";
		document.getElementById("chars").style.display = "";
	} else {
		document.getElementById("put").style.display = "none";
		document.getElementById("full").style.display = "none";
		document.getElementById("chars").style.display = "none";
	}

	// book mark
	/*
	if (data.mark == 1) {
		document.getElementById("btnMark").style.backgroundColor = colorBlue;
	} else {
		document.getElementById("btnMark").style.backgroundColor = "";
	}

	// auto play
	if (autoplay) {
		document.getElementById("btnAuto").style.backgroundColor = colorBlue;
	}
	*/

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

	// view script hidden
	sc = data.script.split(" ");
	var result = "";
//	if (data.is_try) {
//		result = getResultText(data.script, data.answer, "red");
//		document.getElementById("put").disabled = 'disabled';
//	} else {
		for(var i=0; i < sc.length ; i++) {
			sa[i] = 0;
			result += stringFill("_", sc[i].length) + " ";
			if (mode == "word") {
				arr[i] = { count:0, pass:0, correct:0, seq:i, obj:undefined, text: specialCharRemove(sc[i]) };
				var btn = document.createElement("a");
				btn.href = "#";
				btn.setAttribute("id", "btn"+i);
				// btn.setAttribute("seq", i);
				btn.setAttribute("class","ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini");
				var value = specialCharRemove(sc[i], "innerHTML");
				btn.innerHTML = value;
				arr[i].obj = btn;
			}
		}
//	}
	document.getElementById("result").innerHTML = result;

	// attach word
	if (mode == "word") {
		data.correct = 1;
		arr.sort(function(a,b) { return a.text < b.text ? -1 : a.text > b.text ? 1 : 0; });
		for(var i=0; i < arr.length ; i++) {
			$(arr[i].obj).click(function() { check_word(this); });
			$("#fld").append(arr[i].obj);
		}
	}

	// slider
	var width = Math.round(((curr+1) / sum) * 100);
	$("#bar").animate({width:width+"%"},400);

	if (!sm) setSM();
	if (sm && autoplay) play();
}

function setSM() {
//	var isexurl = /^http/i;
//	if (isexurl.test(data.fn)) {
		sm = soundManager.createSound({id: data.code , url: data.fn});
//	} else {
//		sm = soundManager.createSound({id: data.code , url: path + "mp3/" + data.fn + ".mp3"});
//	}
}

function refresh() {
	data.is_try = 0;
	data.pass = 0;
	data.correct = 0;
	data.timestamp = 0;
	init();
}

function play(o) {
	var res = document.getElementById("result");
	sm.stop();

	if (typeof(o) == "undefined") o = { from: 0 };
	if (data.from) o.from = data.from;
	if (data.to) o.to = data.to;
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

// mode 가 word 일 때 값 비교
function check_word(o) {
	var correct = false;
	var pass = false;

	// arr 의 count 증가
	arr[data.word_no].count++;
	document.getElementById("count").innerHTML = "[ " + arr[data.word_no].count + " / " + max + " ]";

	// seq 는 다르지만 text 가 같은 경우도 있으므로 text 비교
	if (specialCharRemove(sc[data.word_no], "check") == specialCharRemove(o.innerText, "check")) {
		correct = true;
	} else if (arr[data.word_no].count >= max) { // arr 의 count 체크
		pass = true;
		data.correct = 0;
	}

	if (pass || correct) {
		$(document.getElementById('btn'+data.word_no)).remove();
		sa[data.word_no] = correct;
		data.word_no++;
		
		var result = "";
		for(var i=0; i < sc.length; i++) {
			if (i < data.word_no) {
				if (sa[i]) result += sc[i] + " ";
				else result += "<span>" + sc[i] + "</span> ";
			}
			else result += stringFill("_", sc[i].length) + " ";
		}
		document.getElementById("result").innerHTML = result;

		if (o.innerText != undefined) data.answer += o.innerText + " ";

		if (data.word_no < sc.length) { // 미완료
			document.getElementById("count").innerHTML = "[ 0 / " + max + " ]";
		} else { // 완료
			var dt = new Date();
			data.is_try = 1;
			data.timestamp = dt.getTime();
			document.getElementById("list"+curr).style.backgroundColor = (data.correct ? colorBlue : colorRed);

//			var o = (autopass ? {onfinish : function() { if ((curr+1) < sum) { curr++; init(); } } } : {});
			log();
			play(o);
		}
	}

}

// mode 가 full 일 때 mobile 여부 판단하여 check
function check(e, o) {  // 값 비교
	if (e.which == 13 || e.keyCode == 13) {
		if (isMobile) {
			play();
			e.focus();
		} else {
			check_full();
//			if (data.count >= max || data.correct == 1) {
			if (data.is_try == 1) {
				log();
			}
		}
	}
}

function check_full() {
//	if (data.is_try) return;
	if (data.correct || data.pass) return;
	var o = document.getElementById("put");
	var correct = false;
	var pass = false;

	if (! o) return;
	data.count++;
	data.answer = o.value.trim();
	document.getElementById("count").innerHTML = "[ " + data.count + " / " + max + " ]";

	var isCorr = [];
	var va = o.value.trim().split(" ").filter(function(v){ return (v != undefined && v != "") });
	if (!pass) {
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

	if (data.count > max || (data.count == max && !correct)){
		pass = true;
	}

	if (correct || pass) {
		document.getElementById("put").disabled = 'disabled';
		var dt = new Date();
		data.is_try = 1;
		data.timestamp = dt.getTime();
		data.correct = (pass ? 0 : 1);

		var e_list = document.getElementById("list"+curr);
		if (e_list) e_list.style.backgroundColor = (data.correct ? colorBlue : colorRed);

		var e_result = document.getElementById("result");
		if (e_result) e_result.innerHTML = getResultText(data.script, data.answer, "red");

//		var o = (autopass ? { onfinish : function() {	if ((curr+1) < sum) { curr++; init(); }	} } : {});
		play(o);
	}
}

function getResultText(script, answer, type) {
	var result = "";
	var va = answer.trim().split(" ").filter(function(v){ return (v != undefined && v != "") });
	var sa = script.trim().split(" ");

	for(var i=0; i < sa.length ; i++) {
		if (specialCharRemove(sa[i], "check") == specialCharRemove(va[i], "check")) {
			result += sa[i] + " ";
		} else {
			if (type == "red") {
				result += "<span>" + sa[i] + "</span> ";
			} else {
				result += stringFill("_", sa[i].length) + " ";
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
		mode = "word";
		max = maxWord;
	}
	init();
	changeListBgColor(mode);
}




///////////////////////////////////////////////////////////////

function changeMark() {
	var o = document.getElementById("btnMark");
	if (data.mark) {
		data.mark = 0;
		if (o) o.style.backgroundColor = "";
		$("#list"+data.seq).removeClass("listbtn-marked");
	} else {
		data.mark = 1;
		if (o) o.style.backgroundColor = colorBlue;
		$("#list"+data.seq).addClass("listbtn-marked");
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


