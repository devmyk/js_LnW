<?
	require_once(getcwd()."/common.php");
	checkSession("uid");
	checkSession("dir", "logout.php");

	$dir = $_POST['dir'];
	$file = $_POST['file'];

	if (! is_dir(getcwd()."/data/{$dir}") || ! is_file(getcwd()."/data/{$dir}/{$file}.js")) exit();

	// 시간 / 정답여부 / dir / file / seq / 입력한문구
	$msg = sprintf("%s\t%s\t%s\t%s\t%s\t%s\n",date("H:i:s"),$_REQUEST['correct'], $dir, $file, $_REQUEST['seq'], $_REQUEST['input']);

	$d = sprintf("%s/logs/%s",getcwd(),$_SESSION['uid']);
	if (! is_dir($d)) {
		@mkdir($d, 0777);
	}
	$fn = sprintf("%s/%s",$d,date("Ymd"));
	if (! file_exists($fn)) {
		$fp = fopen($fn, "w");
		if ($fp) {
			fclose($fp);
		}
	}
	if ($fp = fopen($fn, "a")) {
		fwrite($fp, $msg);
		fclose($fp);
	}
?>
