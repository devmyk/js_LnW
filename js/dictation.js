soundManager.setup({});

var sum = 0;
var curr = 0;
var max = 10;
var sm;
var timeout;
var sc = [];
var arr = [];
var mode = "words"; // full, tiny

function init() {
	sum = data.length;
	data[curr].curr = 0;
	data[curr].sum = 0;
	sc = [];
	arr = [];
	clearTimeout(timeout);

	document.getElementById("count").innerHTML = "[0/" + max + "]";
	document.getElementById("progress").innerHTML = "[" + (curr+1) + "/" + sum + "]";
	document.getElementById("fld").innerHTML = "";
	if (mode == "full") {
		document.getElementById("put").style.display = "";
		document.getElementById("put").value = "";
		document.getElementById("put").disabled = '';
		document.getElementById("put").focus();
	} else {
		document.getElementById("put").style.display = "none";
	}

	if (data[curr].mark == 1) {
		document.getElementById("btnMark").style.backgroundColor = "#38c";
	} else {
		document.getElementById("btnMark").style.backgroundColor = "";
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
	if (!sm) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: "mp3/" + data[curr].fn + ".mp3"});
	} else if (sm.id != "sm_" + data[curr].fn) {
		sm = soundManager.createSound({id:"sm_" + data[curr].fn , url: "mp3/" + data[curr].fn + ".mp3"});
	}

	// view script hidden
	sc = data[curr].script.split(" ");
	var result = "";
	for(var i=0; i < sc.length ; i++) {
		result += stringFill("_", sc[i].length) + " ";
		if (mode == "words") {
			arr[i] = { count:0, pass:0, correct:0, seq:i, obj:undefined, text: specialCharRemove(sc[i]) };
			var btn = document.createElement("a");
			btn.href = "#";
			btn.setAttribute("id", "btn"+i);
			btn.setAttribute("class","ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini");
			btn.setAttribute("seq",i);
			var value = specialCharRemove(sc[i]);
			btn.innerHTML = (value=="i") ? "I" : value;
			arr[i].obj = btn;
		}
	}
	document.getElementById("result").innerHTML = result;

	// attach words
	if (mode == "words") {
		data[curr].sum = arr.length;
		data[curr].correct = true;
		arr.sort(function(a,b) {
			return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
		});
		for(var i=0; i < arr.length ; i++) {
			$(arr[i].obj).click(function() { check2($(this)); });
			$("#fld").append(arr[i].obj);
		}
	}

	if (sm) play();
}

function play() {
	sm.stop("sm_" + data[curr].fn);
	if(data[curr].to) {
		sm.play({from: data[curr].from,	to:data[curr].to});
	} else {
		sm.play();
	}
}

function specialCharRemove(v) {
	if (typeof(v) != "string") return "";
	v = v.toLowerCase();
	var re = /[ \{\}\[\]\/?.,;:|\)*~`!^\-_+┼<>@\#$%&\'\"\\(\=]/gi;
	return v.replace(re, "");
}

function stringFill(x, n) {
	var s = ''; 
	while (s.length < n) s += x; 
	return s; 
}

function check2(o) {  // 값 비교
	var seq = $(o).attr("seq")
	var correct = false;
	var pass = false;

	// arr 의 count 증가
	arr[data[curr].curr].count++;
	document.getElementById("count").innerHTML = "[" + arr[data[curr].curr].count + "/" + max + "]";
	// arr 의 count 체크
	if (arr[data[curr].curr].count >= max) {
		pass = true;
		data[curr].correct = false;
	} else if (data[curr].curr == seq) {
		correct = true;
	} else {
		// seq 는 다르지만 text 가 같은 경우도 있음
		// 같은 텍스트를 배열로 가져와서 비교해야겠구만
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
			document.getElementById("count").innerHTML = "[0/" + max + "]";
			document.getElementById("result").innerHTML = result;
		} else { // 완료
			var dt = new Date();
			data[curr].timestamp = dt.getTime();
			document.getElementById("result").innerHTML = data[curr].script;
			sm.stop();
			sm.play({ onfinish : function() {
				if ((curr+1) < sum){ curr++; init(); }
			}});
		}
	}

}

function check(e, o) {  // 값 비교
	if (e.which == 13 || e.keyCode == 13) {
		var correct = false;
		var pass = false;

		data[curr].count++;
		document.getElementById("count").innerHTML = "[" + data[curr].count + "/" + max + "]";

		if (data[curr].count >= max){
			pass = true;
		}

		var va = o.value.trim().split(" ");
		if (!pass) {
			correct = true;
			var result = "";
			for(var i=0; i < sc.length ; i++) {
				if (specialCharRemove(sc[i]) == specialCharRemove(va[i])) result += sc[i] + " ";
				else {
					result += stringFill("_", sc[i].length) + " ";
					correct = false;
				}
			}
			document.getElementById("result").innerHTML = result;
		}

		if (correct || pass) {
			document.getElementById("put").disabled = 'disabled';
			var dt = new Date();
			data[curr].timestamp = dt.getTime();
			data[curr].correct = (pass ? 0 : 1);
			document.getElementById("result").innerHTML = data[curr].script;
			sm.stop();
			var obj = { onfinish : function() {	if ((curr+1) < sum) { curr++; init(); }	} };
			if (data[curr].to) {
				obj.from = data[curr].from;
				obj.to = data[curr].to;
			}
			sm.play(obj);
		}
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
		o.style.backgroundColor = "";
	} else {
		data[curr].mark = 1;
		o.style.backgroundColor = "#38c";
	}
}

function changeCurr(to) {
	if (to < 0 || to > sum-1) return;
	curr = to;
	init();
}
