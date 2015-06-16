<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?
$tables = $this->db->list_tables();
?>
<style>
#tbl {
	font-size : 10pt;
}
#tbl .td_ttl {
	font-weight: bold;
	padding-top : 1em;
}
#tbl .td_li {
	font-size: 9pt;
	padding-left : 1em;
}
</style>
<table id="tbl">
	<tr><td style="font-weight:bold;">database tables</td></tr>
	<? foreach($tables as $t) { ?>
	<tr><td class="td_li"> <?=htmlspecialchars($t)?></td></tr>
	<? } ?>
	<tr><td class="td_ttl">scripts</tr></td>
	<tr><td class="td_li">new</td></tr>
	<tr><td class="td_li">show
		<div>
			en
		</div>
		<div>
			de
		</div>
	</td></tr>
</table>
