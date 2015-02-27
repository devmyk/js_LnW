<div data-role="panel" id="leftpanel" data-position="left" data-display="overlay" class="navmenu-panel">
	<!-- left panel menu -->
	<ul data-role="listview" data-divider-theme="b" style="margin-bottom:-0.5em;">
		<li><a href="summary.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-home">Home</a></li>
		<li><a href="setting.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-gear">Setting</a></li>
		<? if ($_SESSION['uid'] == $conf['admin']) { ?>
		<li><a href="formating.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-edit">Edit</a></li>
		<? } ?>
		<li><a href="logout.php" rel="external" class="ui-btn ui-btn-icon-left ui-icon-power">Logout</a></li>
		<li data-role="list-divider">English</li>
	</ul>

	<!-- English collapsibleset -->
	<div id="collapsivlesetE" data-role="collapsibleset" data-theme="b" data-content-theme="b" data-inset="false">
	<?
	foreach ($_SESSION['dir'] as $d) {
		$files = getFileListInDir($d);
		if (sizeof($files) <= 0) continue;
	?>
		<div data-role="collapsible">
		<h2><?=$d?></h2>
		<ul data-role="listview" data-divider-theme="b">
			<? foreach($files as $f) { ?>
			<li><a href="summary.php?dir=<?=$d?>&file=<?=$f?>" rel="external" class="ui-btn ui-btn-icon-right ui-icon-carat-r ui-mini ui-nodisc-icon"><?=$f?></a></li>
			<? } ?>
		</ul>
		</div>
	<? } // foreach ?>
	</div><!-- English collapsibleset -->
</div><!-- left panel menu -->
