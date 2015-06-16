/*
var data = [];
var arr = [];
var sc = [];
var mode = "words"; // full, words
var list = "";
var sum = 0;
var curr = 0;
var max = 10;
var sm;
var timeout;
*/

var isRecycle = 0;
var startSeq = 0;
var path = "";
var _js = "data/1001/ch01.js";
var isMobile = isMobile();
var autoplay = (isMobile ? 0 : 1);
var data = [];

function save_db()
{
	var f = document.f2;
	// code
	var em = document.getElementById('mode');
	if (! em) f.md.value = 'words';
	else if (em.checked) f.md.value = 'full';
	else f.md.value = 'words';

	// send data
	var e = document.getElementById('send_data');
	var sends = "";
	if (data.length == 0 || code == "") {
		document.iframe.document.body.innerHTML = "empty data";
		return;
	} else {
		f.code.value = code;
		// seq, corr, answer
		for(var i=0; i < data.length; i++){
			if (data[i].try == 1) {
				var cr = (data[i].correct ? "1" : "0");
				var as = data[i].answer.trim();
				var tmp = data[i].seq + "/" + cr + "/" + as; 
				sends += tmp.trim();
				sends += "\n";
			}
		}
	}
	if (e) {
		f.txt.value = sends.trim();
		if (f.txt.value == "") {
			document.iframe.document.body.innerHTML = "empty data";
		} else {
			f.submit();
		}
		document.iframe.document.body.style.color = "#fff";
	}
}

function setData() {
	var tmp = list.split("^n"); // filename-url / speaker / from / to / script / trans
	var j = 0;
	for (var i=0; i<tmp.length; i++) {
		if (tmp[i].trim() == "") continue;
		else {		//	순번,	시도,	시도회수,	정답여부,	테스트한시간,	북마크여부,	failname,	시작시간,	끝시간,		스크립트,	해석,		코드	,dbseq
			data[j] = {	seq:j,	try:0,	count:0,	correct:0,	timestamp:0,	mark:0,		fn:"",		from:0,		to:0,		script:"",	trans:"",	code:"", dbseq:""};
			var tmp2 = trim_f(tmp[i]).split("\t");
			// dbseq / mp3 / from / to / script / trans
			data[j].dbseq	= tmp2[0];
			data[j].fn		= trim(tmp2[1]);
			if (trim(tmp2[2]) != "") data[j].speaker = trim(tmp2[2]);
			if (tmp2[3] != "") data[j].from = parseInt(tmp2[3]);
			if (tmp2[4] != "") data[j].to = parseInt(tmp2[4]);
			data[j].script	= trim(tmp2[5]);
			if (trim(tmp2[6]) != "") data[j].trans = trim(tmp2[6]);
			data[j].code = code + "_" + j;
			j++;
		}
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

function isMobile() {
	ua = window.navigator.userAgent;
	isMobile = (/lgtelecom/i.test(ua) 
			|| /Android/i.test(ua) 
			|| /blackberry/i.test(ua) 
			|| /iPhone/i.test(ua) 
			|| /iPad/i.test(ua) 
			|| /samsung/i.test(ua) 
			|| /symbian/i.test(ua) 
			|| /sony/i.test(ua) 
			|| /SCH-/i.test(ua) 
			|| /SPH-/i.test(ua) 
			|| /nokia/i.test(ua) 
			|| /bada/i.test(ua) 
			|| /semc/i.test(ua) 
			|| /IEMobile/i.test(ua) 
			|| /Mobile/i.test(ua) 
			|| /PPC/i.test(ua) 
			|| /Windows CE/i.test(ua) 
			|| /Windows Phone/i.test(ua) 
			|| /webOS/i.test(ua) 
			|| /Opera Mini/i.test(ua) 
			|| /Opera Mobi/i.test(ua) 
			|| /POLARIS/i.test(ua) 
			|| /SonyEricsson/i.test(ua) 
			|| /symbos/i.test(ua));
	return isMobile;
}

Array.prototype.shuffle = function () {
	var i = this.length, j, temp;
	if (i <= 0) return;
	while (--i) {
		j = Math.floor( Math.random() * (i + 1) );
		temp = this[i];
		this[i] = this[j];
		this[j] = temp;
	}
};

// document.getElementById("id").remove();
Element.prototype.remove = function() {
	this.parentElement.removeChild(this);
}
// document.getElementsByClassName("class").remove();
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
	for(var i = 0, len = this.length; i < len; i++) {
		if(this[i] && this[i].parentElement) {
			this[i].parentElement.removeChild(this[i]);
		}
	}
}
