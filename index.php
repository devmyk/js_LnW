<?
	require_once(getcwd()."/common.php");
	if (isset($_SESSION['uid']) && !empty($_SESSION['uid']) ) {	// 로그인 상태니까 리스트 보이도록
		echo "<script>document.location.replace('dictation.php');</script>";
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION</title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
	<script>
		function login() {
			var f = document.result;

			var id = f.inputID.value.trim();
			var pw = f.inputPW.value.trim();
			if (id == "") { alert("input email."); return false; }
			if (pw == "") { alert("input password."); return false; }

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
		<form name="result" method="post" action="login.php" style="padding-top:6em;">
			<label for="inputEmail" class="ui-hidden-accessible">User ID:</label>
			<input type="text" name="id" id="inputID" in="inputID" value="" placeholder="ID">
			<label for="inputPW" class="ui-hidden-accessible">Password:</label>
			<input type="password" name="pw" id="inputPW" in="inputPW" value="" placeholder="password">
			<a href="#" rel="external" class="ui-btn ui-corner-all ui-btn-icon-left ui-icon-power" onclick="login();">LOGIN</a>
		</form>
	</div><!-- /main -->
</div><!-- /page -->
</body>
</html>
