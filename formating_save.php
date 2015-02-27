<?
	require_once(getcwd()."/common.php");
	checkSession("uid");

	if ($_SESSION['uid'] != $conf['admin']) {
		echo "<script>document.location.replace('summary.php');</script>";
		exit();
	}

	$dir = $_POST['dir'];
	$path = getcwd()."/data/{$dir}/";

	// 폴더 없음
	if (! is_dir($path)) {
		echo "ERROR";
		exit();
	}

	$data = $_POST['data'];
	$fn = "{$dir}.js";
	if (! empty($_POST['filename'])) $fn = "{$_POST['filename']}.js";

	// 파일 유무 확인
	// 이미 있으면 기존 파일명을 dir.날짜.js 로 변경
	if (file_exists("{$path}/{$fn}")) {
		rename("{$path}/{$fn}", "{$path}/.{$dir}.".date("Ymd").".js");
	}

	// 스크립트 파일 만들기
	if ($fp = fopen("{$path}/{$fn}", "w")) {
		echo "write...<br>";
		// 가공
		$msg = implode("/\\\n", $data);
		$msg = sprintf("var list = \"%s\";\n\nsetData();",$msg);
		// file \t from \t to \t script \t translation \n
		fwrite($fp, $msg);
		fclose($fp);
	}
	echo "done<br>";
	debug($path, $data, $msg);
	exit();
?>
