<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
	<script>
function login() {
	var f = document.result;

	var id = f.inputID.value.trim();
	var pw = f.inputPW.value.trim();
	if (id === "") { alert("input email."); return false; }
	if (pw === "") { alert("input password."); return false; }

	f.submit();
}
	</script>
</head>
<body>
<div data-role="page" data-theme="b" data-content-theme="b">
	<div data-role="header" class="header">
		<h1>LOGIN</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<form name="result" method="post" action="index.php/c_dictation/login" style="padding-top:6em;">
			<label for="inputEmail" class="ui-hidden-accessible">Email:</label>
			<input type="text" name="id" id="inputID" in="inputID" value="" placeholder="EMAIL">
			<label for="inputPW" class="ui-hidden-accessible">Password:</label>
			<input type="password" name="pw" id="inputPW" in="inputPW" value="" placeholder="password">
			<a href="#" rel="external" class="ui-btn ui-corner-all ui-btn-icon-left ui-icon-power" onclick="login();">LOGIN</a>
		</form>
	</div><!-- /main -->
</div><!-- /page -->
</body>
</html>