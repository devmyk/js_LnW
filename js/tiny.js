soundManager.setup({});

var sum = 0;
var curr = 0;
var max = 10;
var sm;
var timeout;
var arr = [];

function init() {
	sum = data.length;
	data[curr].curr = 0;
	arr = [];

	document.getElementById("count").innerHTML = "[" + data[curr].count + "/" + max + "]";
	document.getElementById("progress").innerHTML = "[" + (curr+1) + "/" + sum + "]";
	document.getElementById("put").style.display = "none";

	clearTimeout(timeout);
	sm = soundManager.createSound({id:data[curr].fn , url: "mp3/" + data[curr].fn + ".mp3"});
	
	var sc = data[curr].script.split(" ");
	var result = "";
	for(var i=0; i < sc.length ; i++) {
		result += stringFill("_", sc[i].length) + " ";
		arr[i] = { count:0, pass:0, correct:0, seq:i, obj:undefined, text: specialCharRemove(sc[i]) };
		var btn = document.createElement("a");
		btn.href = "#";
		btn.setAttribute("class","ui-btn ui-corner-all ui-btn-inline ui-btn-b ui-mini");
		btn.setAttribute("seq",i);
		btn.innerHTML = sc[i];
		arr[i].obj = btn;
	}
	document.getElementById("result").innerHTML = result;
	if (sm) play();
	arr.sort(function(a,b) {
		return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
	});
	for(var i=0; i < arr.length ; i++) {
		var seq = arr[i].seq;
		$(arr[i].obj).click(function() { check2($(this).attr("seq")); });
		$("#fld").append(arr[i].obj);
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

function check2(seq) {  // 값 비교
	console.log('check2......');
	var correct = false;
	var pass = false;

	// arr 의 count 증가
	arr[seq].count++;
	// arr 의 count 체크
	if (arr[seq].count >= max) {
		pass = true;
	}
	
	if (data[curr].curr == seq) {
	} else {
		
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
