<?
session_start();

function checkSession($k, $url = "index.php") {
	$bool = !(isset($_SESSION) && isset($_SESSION[$k]));
	if ($bool) {
		printf("<script>document.location.replace('%s');</script>", $url);
		exit();
	}
}

// data 폴더 내 js 파일들을 리턴
function getFileListInDir($dir) {
	if (! is_dir("./data/".$dir)) return;

	$files = Array();
	if ($dh = opendir("./data/".$dir)) {
		while(($file = readdir($dh)) !== false) {
			if (filetype("./data/".$dir."/".$file) == "file" && substr(strrchr($file, "."),1) == "js") {
				$files[] = substr($file,0,strpos($file, "."));
			}
		}
		closedir($dh);
	}
	return $files;
}

function debug($v) {
	$args = func_get_args();
	print "<xmp>";
	foreach ($args as $arg) var_dump($arg);
	print "</xmp>";
}

?>
