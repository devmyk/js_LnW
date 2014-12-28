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

var data = [];
function setData() {
	var tmp = list.split("/");
	var j = 0;
	for (var i=0; i<tmp.length; i++) {
		if (tmp[i].trim() == "") continue;
		else {		//	시도,	시도회수,	정답여부,	테스트한시간,	북마크여부,	failname,	시작시간,	끝시간,		스크립트
			data[j] = {	try:0,	count:0,	correct:0,	timestamp:0,	mark:0,		fn:"",		from:0,		to:0,		script:"" };
			var tmp2 = tmp[i].trim().split("\t");
			data[j].fn		= tmp2[0].trim();
			data[j].from	= parseInt(tmp2[1]);
			data[j].to		= parseInt(tmp2[2]);
			data[j].script	= tmp2[3].trim();
			j++;
		}
	}
}
