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
	<tr><td class="td_li"><?=htmlspecialchars($t)?></td></tr>
	<? } ?>
	<tr><td class="td_ttl"><a href="/c_dictation/ad_sql">SQL</a></td></tr>
	<tr><td class="td_ttl"><a href="/c_dictation/ad_script">scripts</a></tr></td>
	<tr><td class="td_li"><a href="/c_dictation/ad_script_frm">new</a></td></tr>
<!--
	<tr><td class="td_li">show
		<div>
			en
		</div>
		<div>
			de
		</div>
	</td></tr>
	-->
	<tr><td class="td_ttl"><a href="/c_dictation/logout">LOGOUT</a></td></tr>
</table>
