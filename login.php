<?
session_start();

$url = "index.php";
$uid = (isset($_POST['id']) ? trim(htmlspecialchars($_POST['id'])) : "");
$upw = (isset($_POST['pw']) ? trim(htmlspecialchars($_POST['pw'])) : "");

if ($uid == "guest") { $upw = "guest"; }
if (empty($uid) || empty($upw)) {
	session_unset();
} else {
	$path = getcwd()."/data/.user.dat";

	if (! is_file($path)) {
		session_unset();
	} else {
		if($fp = fopen($path, "r")) {
			$users = array();
			while (!feof($fp)) {
				$line = fgets($fp, 1024);
				if(! empty($line)) {
					$users[] = trim($line);
				}
			}
			fclose($fp);
			foreach ($users as $v) {
				$ud = explode(",",$v);
				if ($uid == $ud[0] && $upw == $ud[1]) {
					$_SESSION['uid'] = $uid;
					$_SESSION['permit'] = $ud[2];
					$_SESSION['set'] = explode("/",$ud[3]);
					$_SESSION['dir'] = explode("/",$ud[4]);
				}
			}
		}
		if (empty($_SESSION['uid'])) {
			session_unset();
		} else {
			$url = "summary.php";
		}
	}
}
?>
<html>
<head>
<script>
document.location.replace("<?=$url?>");
</script>
</head>
<body></body>
</html>
