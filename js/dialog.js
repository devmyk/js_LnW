soundManager.setup({});

var sum = 0;
var curr = 0;
var max = 3;
var sm;
var order = "asc";

function init() {
	attachDialogList();
}

function attachDialogList() {
	if (data.length <= 0) return;
	var table = document.getElementById("contain");
	if (!table) return;
	for (var i=0; i<data.length; i++) {
		var tr = document.createElement("tr");
		var no = parseInt(data[i].seq) + startSeq + 1;
		tr.innerHTML = '<td><a id="btnMark'+data[i].seq+'" class="ui-btn ui-icon-star ui-corner-all ui-btn-b ui-mini ui-btn-icon-notext ui-btn-inline ui-btn-nomargin ui-nodisc-icon" onclick="changeMark('+data[i].seq+');">mark</a></td>';
		tr.innerHTML += '<td>'+no+'</td>';
		tr.innerHTML += '<td style="text-align:left;padding-top:0.5em;">'+data[i].script+'</td>';
		$("#contain").append(tr);
	}
}

function changeMark(i) {
	var ol = document.getElementById("btnMark"+i);
	var o = document.getElementById("btnMark");
	if (data[curr].mark) {
		data[curr].mark = 0;
		if (o) o.style.backgroundColor = "";
		if (ol) ol.style.backgroundColor = "";
		$("#list"+data[curr].seq).removeClass("listbtn-marked");
	} else {
		data[curr].mark = 1;
		if (o) o.style.backgroundColor = "#38c";
		if (ol) ol.style.backgroundColor = "#38c";
		$("#list"+data[curr].seq).addClass("listbtn-marked");
	}
}
