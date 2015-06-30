<?
	$conn = mysql_connect('localhost:13306', 'root', 'spamsniper');
	mysql_set_charset('utf8');
	mysql_select_db('devmyk', $conn);
	$res = mysql_query("show databases", $conn);
	print_r($res);
	mysql_close($conn);
?>
