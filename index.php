<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>index</title>
	<!-- link rel="shortcut icon" href="demos/favicon.ico" -->
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>
<div data-role="page" data-theme="b" data-content-theme="b">
	<div data-role="header" class="header">
		<h1>DICTATION</h1>
		<!-- a href="#leftpanel" class="ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">Menu</a -->
		<!-- a href="#rightpanel" class="jqm-search-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-search ui-nodisc-icon ui-alt-icon ui-btn-right">Search</a -->
	</div><!-- /header -->

	<div role="main" id="main" class="ui-content">
	<div data-role="collapsibleset">
		<div data-role="collapsible">
			<h2>1001 basic</h2>
			<ul data-role="listview" class="ui-mini">
			<?
			for ($i=1;$i<=18;$i++) {
				$file = "ch". ($i<10 ? "0{$i}" : $i); 
				echo '<li><a href="dictation.php?dir=1001&file='.$file.'" rel="external"';
				echo is_file(getcwd()."/data/1001/{$file}.js") ? '>' : 'class="ui-state-disabled">';
				echo $file.'</a></li>';
			}
			?>
			</ul>
		</div>
		<div data-role="collapsible">
			<h2>TED</h2>
			<ul data-role="listview" class="ui-mini">
				<li><a href="dictation.php?dir=ted&file=30days" rel="external"><span class="by">[Matt Cutts]</span>Try something new for 30 days<span class="ui-li-count">36</span></a></li>
			</ul>
		</div>
	</div>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed" data-tap-toggle="false">
		<p>Copyright 2014 The jQuery Foundation</p>
	</div><!-- /footer -->

</div><!-- /page -->

</body>
</html>
