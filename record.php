<?
/*
(type == get)	REQUEST = {dir, file, type, seq}
				result = "seq/mark/cnt_correct/cnt_try"
(type == set)	REQUEST = {dir, file, type, seq, mark, correct}
				result = ""
}
*/
require_once(getcwd()."/common.php");
checkSession("uid");
checkSession("dir", "logout.php");

$dir = $_REQUEST['dir'];
$file = $_REQUEST['file'];
$type = $_REQUEST['type']; // get, set

if (! is_dir(getcwd()."/data/{$dir}") || ! is_file(getcwd()."/data/{$dir}/{$file}.js")) {
	echo "error";
	exit();
}

if ($type == "set") {
	echo setMark($_REQUEST);
} else {
	echo getMark($_REQUEST);
}


function getMark($req) {
	$res = "";
	$d = sprintf("%s/logs/%s",getcwd(),$_SESSION['uid']);
	if (! is_dir($d)) {
		return $res;
	}
	$fn = sprintf("%s/%s_%s",$d,$req['dir'],$req['file']);
	if (! file_exists($fn)) {
		return $res;
	}

	if ($fp = fopen($fn, "r")) {
		while(! feof($fp)) {
			$line = trim(fgets($fp, 1024));
			if (empty($line)) {
				continue;
			}
			$ld = explode("\t",$line);
			if ($ld[0] == $req['seq']) {
				$res = $line;
				break;
			}
		}
		fclose($fp);
	}
	return $res;
}

function setMark($req) {
	$d = sprintf("%s/logs/%s",getcwd(),$_SESSION['uid']);
	if (! is_dir($d)) {
		@mkdir($d, 0777);
	}
	$fn = sprintf("%s/%s_%s",$d,$req['dir'],$req['file']);

	if (! isset($req['seq'])) {
		return "error";
	}
	
	$new_line = "";
	if (! file_exists($fn)) {
		$new_line = sprintf("%s\t%s\t%s\t%s",$req['seq'],$req['mark'],$req['correct'],"1");
	} else {
		$old_list = array();
		// 파일 읽어서 old_list 로
		if ($fp = fopen($fn, "r")) {
			while(! feof($fp)) {
				$line = trim(fgets($fp, 1024));
				if (! empty($line)) {
					$old_list[] = $line;
				}
			}
			fclose($fp);
		}
		$is_add = true;
		// 있으면 덮어쓰기
		foreach($old_list as $k=>&$v) {
			// seq/mark/cnt_correct/cnt_try
			$la = explode("\t",$v);
			if ($la[0] == $req['seq']) {
				$v = sprintf("%s\t%s\t%d\t%d"
						,$req['seq']
						,$req['mark']
						,((int)$la[2]) + ((int)$req['correct'])
						,((int)$la[3]) + 1);
				$is_add = false;
				break;
			}
		}
		// 없으면 추가
		if ($is_add) {
			$old_list[] = sprintf("%s\t%s\t%s\t%s",$req['seq'],$req['mark'],$req['correct'],"1");
		}
	}
	// 파일 다시 쓰기
	if (isset($old_list)) {
		sort($old_list); // seq 로 정렬
		$list = implode("\n", $old_list);
	} else {
		$list = $new_line;
	}

	$fp = @fopen($fn, "w");
	if (! $fp) {
		return "error";
	}
	fwrite($fp, $list);
	fclose($fp);
	return "";
}

?>
