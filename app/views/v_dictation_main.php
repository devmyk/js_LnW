<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : main</title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="/css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<div data-role="page" data-theme="b" class="ui-page-theme-b">
	<div data-role="header" class="header">
		<h1>MAIN</h1>
		<a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a>
	</div><!-- /header -->

	<div role="main" class="ui-content">
    	<h1>summary</h1>
	</div><!-- content -->

	<?
        require_once("./app/views/v_dictation_left.php");
    ?>
</div><!-- page -->
</body>
</html>
</html>