<? defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DICTATION : admin</title>
	<link rel="shortcut icon" href="/images/icon.ico">
	<script language="javascript" src="/js/jquery.js"></script>
<script>
function query(type) {
	var e = document.getElementById('q');
	var html = "";
	if (type == "ins_category") {
		html = "insert into category(depth,pcode,code,name,start_no,dir,js) values('','','','','','','');";
	} else if (type == "cnt_code") {
		html = "SELECT s.code, c.name, count(*), c.code FROM script s left join category c on c.code = s.code where c.code is null group by s.code order by s.code";
	}

	if (html != "") {
		e.innerHTML = html;
	}
}
</script>
</head>
<body>
<table width="100%">
	<tr><td colspan="2"><?//debug($info);?></td></tr>
	<tr>
		<td width="130px" valign="top" style="border-right:1px solid #aaa;"><!-- tables -->
        <? require_once("./app/views/dictation/admin/left.php"); ?>
		</td>
		<td valign="top">
<form name="fm" method="post" action="<?=base_url("c_dictation/ad_sql");?>">
	insert :
	&nbsp;<input type="button" value="insert_category" onclick="query('ins_category');" />
	&nbsp;<input type="button" value="cnt_code" onclick="query('cnt_code');" />
	<br>
	<textarea id="q" name="q" cols="100" rows="8"></textarea><br>
	<input type="submit" value="send" />
	<div><? debug($sql); ?></div>
	<div id="result">
		<? if (isset($res)) debug($res)?>
	</div>
</form>
		</td>
	</tr>
</table>
</body>
</html>
</html>
