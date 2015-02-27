<?
require_once(getcwd()."/common.php");
checkSession("uid");
checkSession("dir", "logout.php");

$err = array();
if(! empty($_POST)) {
	// 세션만 수정하는 일회용 세팅변경
	// 0max/1autoplay/2autopass/3defaultmode/4playCount/5maxFull/6maxWord
	$post = $_POST;
	$post['wordsMax'] = (isset($post['wordsMax']) ? intval($post['wordsMax']) : 3);
	$post['fullMax'] = (isset($post['fullMax']) ? intval($post['fullMax']) : 3);
	$post['playCount'] = (isset($post['playCount']) ? intval($post['playCount']) : 3);

	$_set['maxword']	= ($post['wordsMax'] > 0	? $post['wordsMax'] : 3);
	$_set['maxfull']	= ($post['fullMax']	> 0		? $post['fullMax'] : 3);
	$_set['playcount']	= ($post['playCount'] > 0	? $post['playCount'] : 3);
	$_set['autoplay']	= (isset($post['autoPlay']) ? 1 : 0);
	$_set['autopass']	= (isset($post['autoPass']) ? 1 : 0);
	$_set['defaultmode'] = (isset($post['defaultMode']) ? "full" : "words");
	$_set['max'] = (isset($post['defaultMode']) ? $_set['maxfull'] : $_set['maxword']);

	$_SESSION['set'] = $_set;
}
debug($post, $_SESSION['set']);
exit();
?>
<html>
<head>
<script>
document.location.replace("index.php");
</script>
</head>
<body></body>
</html>
