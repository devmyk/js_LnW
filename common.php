<?
session_start();

function checkSession($k, $url = "index.php") {
	$bool = !(isset($_SESSION) && isset($_SESSION[$k]));
	if ($bool) {
		printf("<script>document.location.replace('%s');</script>", $url);
		exit();
	}
}

// data 폴더 내 *.ext 파일들을 리턴
function getFileListInDir($dir, $ext = "js") {
	if (! is_dir("./data/".$dir)) return;

	$files = Array();
	if ($dh = opendir("./data/".$dir)) {
		while(($file = readdir($dh)) !== false) {
			if (filetype("./data/".$dir."/".$file) == "file" && substr($file,0,1) != "." && substr(strrchr($file, "."),1) == $ext) {
				$files[] = substr($file,0,strpos($file, "."));
			}
		}
		closedir($dh);
	}
	return $files;
}

// data 폴더 내 폴더들을 리턴
function getDirList($dir = "") {
	$path = "./data".$dir;
	if (! is_dir($path)) return;

	$dirs = Array();
	if ($dh = opendir($path)) {
		while(($file = readdir($dh)) !== false) {
			if (filetype($path."/".$file) == "dir" && substr($file,0,1) != ".") {
				$dirs[] = $file;
			}
		}
		closedir($dh);
	}
	return $dirs;
}

function debug($v) {
	$args = func_get_args();
	print "<xmp>";
	foreach ($args as $arg) var_dump($arg);
	print "</xmp>";
}

?>
