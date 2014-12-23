soundManager.setup({});

var sum = 0;
var curr = 0;
var max = 15;
var sm;
var timeout;

function init() {
	sum = data.length;

	document.getElementById("count").innerHTML = "[" + data[curr].count + "/" + max + "]";
	document.getElementById("progress").innerHTML = "[" + (curr+1) + "/" + sum + "]";
	document.getElementById("put").value = "";
	document.getElementById("put").disabled = '';
	document.getElementById("put").style.backgroundColor = '';
	document.getElementById("put").focus();

	clearTimeout(timeout);
	sm = soundManager.createSound({id:data[curr].fn , url: "mp3/" + data[curr].fn + ".mp3"});
	
	var sc = data[curr].script.split(" ");
	var result = "";
	for(var i=0; i < sc.length ; i++) {
		result += stringFill("_", sc[i].length) + " ";
	}
	document.getElementById("result").innerHTML = result;
	if (sm) play();
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
