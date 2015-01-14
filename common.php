<?
session_start();

function checkSession($k, $url) {
	if (! isset($_SESSION[$k])) {
		if (empty($url)) $url = "index.php";
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

?>
