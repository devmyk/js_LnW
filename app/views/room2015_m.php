<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Room 2015 mobile</title>
	<link rel="shortcut icon" href="<?=base_url('/images/icon.ico')?>">
	<link rel="stylesheet" href="<?=base_url('css/jquery.mobile-1.4.5.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('css/style.css')?>">
<style>
#detail {
	font-size: 11px;
	font-family: "돋움";
}
#detail .ttl {
	width: 8em;
	font-weight: bold;
}
</style>
	<script type="text/javascript" src="<?=base_url('js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('js/jquery.mobile-1.4.5.min.js')?>"></script>
</head>
<body>
<div data-role="page" data-theme="b" data-content-theme="b">
	<div data-role="header" class="header">
		<h1>Room2015</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	<form name="result" method="post">
	<table id="detail" width="100%">
		<tr>
			<td class="ttl">방문일</td>
			<td colspan="4"><input type="text" name="" id="" value="<?//=$data['']?>" /></td>
		</tr>
		<tr>
			<td class="ttl">전세/관리비</td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" /></td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" /></td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" />만원</td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" />만원</td>
		</tr>
		<tr>
			<td class="ttl">입주일/계약기간</td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" /></td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" /></td>
			<td>1년 / 2년 / etc</td>
			<td><input type="text" name="" id="" value="<?//=$data['']?>" />만원</td>
		</tr>
		<tr>
			<td class="ttl" rowspan="2">주소</td>
			<td colspan="4">
				<fieldset data-role="controlgroup" data-type="horizontal">
				<input type="radio" name="su" id="" value="su" <?//=$data['']?>>
				<label for="su">서울</label>
				<input type="radio" name="kk" id="" value="kk" <?//=$data['']?>>
				<label for="kk">경기</label>
				<input type="radio" name="ic" id="" value="ic" <?//=$data['']?>>
				<label for="ic">인천</label>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td colspan="4"><textarea name="" id=""><?//=$data['']?></textarea></td>
		</tr>
		<tr>
			<td class="ttl">층/실평수</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="ttl" rowspan="2">관리비포함내역</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td class="ttl">채광/창문크기</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl">곰팡이/도배지원</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl">방음(벽노크)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl">난방형태</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl">수압</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl" rowspan="3">옵션가구</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl" rowspan="2">보안</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		<tr>
		<tr>
			<td class="ttl">etc</td>
			<td colspan="4"><textarea name=""><?//=$data['etc']?></textarea></td>
		<tr>


		<tr>
			<td colspan="5">
<!--input type="checkbox" data-role="flipswitch" name="" id="" data-on-text="full" data-off-text="word" data-wrapper-class="custom-label-flipswitch" <?//=($u['defaultmode'] == "word" ? "" : "checked=\"checked\"");?> / -->
				<a href="#" class="ui-btn ui-btn-icon-left ui-corner-all" onclick="save();">SAVE</a>
			</td>
		</tr>
		</form>
	</div><!-- /main -->

	<div data-role="panel" id="leftpanel" data-position="left" data-display="overlay" class="navmenu-panel">
		<ul data-role="listview" style="margin-bottom:-0.5em;">
		<!--<li><a href="/c_dictation" rel="external" class="ui-btn ui-btn-icon-left ui-icon-home">Home</a></li> -->
		</ul>
	</div>

</div><!-- /page -->
</body>
</html>
