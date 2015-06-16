<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$w_total	= 100;
$w_corr		= round((40 / $w_total) * 100);
$w_incorr	= round((30 / $w_total) * 100);
$w_pass		= round((30 / $w_total) * 100);

$is_nologs = empty($logs['total']);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : stat</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/Chart.min.js"></script>
<script>
function init() {
var w = $("#chart").width();
var h = Math.round(w*0.6);
if (h > 300 && ! isMobile) h = 300;
$("#myChart").width(w+"px");
$("#myChart").height(h+"px");
$("#cart").height(h+"px");

var corr_color = "34,170,221";
var incorr_color = "204,51,51";
var pass_color = "110,110,110";
var data = {
labels: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"],
datasets: [
{	label: "CORR",
	fillColor: "rgba("+corr_color+",0.2)",
	strokeColor: "rgba("+corr_color+",1)",
	pointColor: "rgba("+corr_color+",1)",
	pointStrokeColor: "rgba("+corr_color+",1)",
	pointHighlightFill: "#000",
	pointHighlightStroke: "rgba("+corr_color+",1)",
	data: [65, 59, 80, 81, 56, 55, 40]
},
{	label: "INCORR",
	fillColor: "rgba("+incorr_color+",0.2)",
	strokeColor: "rgba("+incorr_color+",1)",
	pointColor: "rgba("+incorr_color+",1)",
	pointStrokeColor: "rgba("+incorr_color+",1)",
	pointHighlightFill: "#000",
	pointHighlightStroke: "rgba("+incorr_color+",1)",
	data: [28, 48, 40, 19, 86, 27, 90]
},
{	label: "PASS",
	fillColor: "rgba("+pass_color+",0.2)",
	strokeColor: "rgba("+pass_color+",1)",
	pointColor: "rgba("+pass_color+",1)",
	pointStrokeColor: "rgba("+pass_color+",1)",
	pointHighlightFill: "#000",
	pointHighlightStroke: "rgba("+pass_color+",1)",
	data: [5, 8, 4, 0, 18, 17, 10]
}
]};
var option = {
	responsive: true,
    animation: false,
    scaleFontColor: "#aaa",
    scaleGridLineColor : "rgba(0,0,0,.5)",
//	barValueSpacing : 5,
//	barDatasetSpacing : 1,
	tooltipFontSize: 12,
	multiTooltipTemplate: "<%=datasetLabel%> : <%=value%>"
};
var ctx = $("#myChart").get(0).getContext("2d");
var myLineChart = new Chart(ctx).Line(data,option);

/*
var data = [
 {value: 300, color: "#F7464A", highlight: "#FF5A5E", label: "Red"}
,{value:  50, color: "#46BFBD", highlight: "#5AD3D1", label: "Green"}
,{value: 100, color: "#FDB45C", highlight: "#FFC870", label: "Yellow"}
];

$("#myChart").width(w+"px");
$("#myChart").height(w+"px");

var ctx = $("#myChart").get(0).getContext("2d");
var myChart = new Chart(ctx).Doughnut(data);
*/
}
</script>
</head>
<body onload="init();">
<div data-role="page" data-theme="b" data-content-theme="b">
	<div data-role="header" class="header">
		<h1>STAT</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
	</div><!-- /header -->
	<div role="main" class="ui-content">
		<div id="chart" style="text-align:center;margin:0.5em;<?=($is_nologs ? "display:none;" : "")?>">
			<canvas id="myChart" width="100" height="300"></canvas>
		</div>
		<a href="/c_dictation/dialog/<?=$code?>" rel="external" class="ui-btn ui-corner-all">DIALOG</a>
		<a href="/c_dictation/dictation/<?=$code?>" rel="external" class="ui-btn ui-corner-all">DICTATION</a>
		<? if($permit == 9) {
		debug($logs, $is_nologs);
			?>
		<a href="/c_dictation/edit/<?=$code?>" rel="external" class="ui-btn ui-corner-all">EDIT</a>	
		<? } ?>
	</div><!-- /main -->
	<? require_once("./app/views/dictation/left.php"); ?>
</div><!-- /page -->
</body>
</html>
