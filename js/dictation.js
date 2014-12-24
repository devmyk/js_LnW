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

	document.getElementById("count").innerHTML = "[0/" + max + "]";
	document.getElementById("progress").innerHTML = "[" + (curr+1) + "/" + sum + "]";
	document.getElementById("fld").innerHTML = "";
	if (mode == "full") {
		document.getElementById("put").style.display = "";
		document.getElementById("put").value = "";
		document.getElementById("put").disabled = '';
		document.getElementById("put").style.backgroundColor = '';
		document.getElementById("put").focus();
	} else {
		document.getElementById("put").style.display = "none";
	}

	clearTimeout(timeout);
	sm = soundManager.createSound({id:data[curr].fn , url: "mp3/" + data[curr].fn + ".mp3"});
	
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
			btn.innerHTML = specialCharRemove(sc[i]);
			arr[i].obj = btn;
		}
	}
	document.getElementById("result").innerHTML = result;
	if (sm) play();
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
}

function play() {
	sm.stop(data[curr].fn);
	sm.play();
}

function specialCharRemove(v) { 
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
	console.log('check2......');
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
			sm.play({ onfinish : function() { if (curr <= sum){ curr++; init(); }}});
		}
	}

}

function check(e, va) {  // 값 비교
	if (e.which == 13 || e.keyCode == 13) {
		console.log('check......');
		var correct = true;
		var pass = false;

		data[curr].count++;
		console.log(data[curr].script);
		console.log(data[curr].count);
		if (data[curr].count >= max){
			pass = true;
			document.getElementById("put").disabled = 'disabled';
			document.getElementById("put").style.backgroundColor = '#000';
		}

		var sc = data[curr].script.split(" ");
		if (sc.length != va.length && !pass) {
			correct = false;
			return;
		}

		var result = "";
		for(var i=0; i < sc.length ; i++) {
			if (specialCharRemove(sc[i]) == specialCharRemove(va[i])) result += sc[i] + " ";
			else {
				result += stringFill("_", sc[i].length) + " ";
				correct = false;
			}
		}
		document.getElementById("result").innerHTML = result;
		if (correct || pass) {
			var dt = new Date();
			data[curr].timestamp = dt.getTime();
			data[curr].correct = (pass ? 0 : 1);
			document.getElementById("result").innerHTML = data[curr].script;
			sm.stop();
			sm.play({ onfinish : function() { if (curr <= sum){ curr++; init(); }}});
		}
	}
}

function changemode(o) {
	if (o.checked) {
		mode = "full";
	} else {
		mode = "words";
	}
	init();
}

function changestar(o) {
	console.log(data[curr].star);
	if (data[curr].star) {
		data[curr].star = 0;
		o.style.backgroundColor = "#551";
	} else {
		data[curr].star = 1;
		o.style.backgroundColor = "";
	}
}
