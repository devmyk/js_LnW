<? defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : admin</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<script language="javascript" src="/js/jquery.js"></script>
	<script language="javascript" src="/js/soundmanager2-js.min.js"></script>
<script>
function insert(type)
{
	var e = document.getElementById('q');
	var html = "";
	if (type="category") {
		html = "insert into category(depth,pcode,code,name,start_no,dir,js) values('','','','','','','');";
	}
	if (html != "") {
		e.innerHTML = html;
	}
}
</script>
</head>
<body>
<form name="fm" method="post" action="<?=base_url("c_test/sql");?>">
insert : <input type="button" value="category" onclick="insert('category');" />
<br>
<textarea id="q" name="q" cols="100" rows="8"></textarea><br>
<input type="submit" value="send" />
<div id="result">
<? if (isset($res)) debug($res)?>
</div>
</form>
</body>
</html>
</html>
