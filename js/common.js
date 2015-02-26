
var isRecycle = 0;
var isGetRecord = 0;
var startSeq = 0;
var path = "";
var dir = "";
var file = "";
var _js = "data/ted/30days.js";
var isMobile = isMobile();
var autoplay = (isMobile ? 0 : 1);
var autopass = 0;
var data = [];
var logs = [];
var colorBlue = "#38c";
var colorRed = "#c33";

function setData() {
	var tmp = list.split("/");
	var j = 0;
	for (var i=0; i<tmp.length; i++) {
		if (tmp[i].trim() == "") continue;
		else {		//	순번,	시도,	시도회수,	정답여부,	테스트한시간,	북마크여부,	failname,	시작시간,	끝시간,		스크립트
			data[j] = {	seq:j,	try:0,	count:0,	correct:0,	timestamp:0,	mark:0,		fn:"",		from:0,		to:0,		script:"" };
			var tmp2 = tmp[i].trim().split("\t");
			data[j].fn		= tmp2[0].trim();
			if (tmp2[1] != "") data[j].from = parseInt(tmp2[1]);
			if (tmp2[2] != "") data[j].to = parseInt(tmp2[2]);
			data[j].script	= tmp2[3].trim();
			j++;
		}
	}
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

function trim(s) {
	if (typeof(s) == "undefined") return "";
	return s.replace(/^[\r\n\t ]+/, "").replace(/[\r\n\t ]+$/, "");
}

