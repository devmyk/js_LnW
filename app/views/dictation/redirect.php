<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
<? if (!empty($err)) { ?>
alert("<?=$err?>");
<? } ?>
document.location = "<?=$url?>";
</script>
</head>
<body>
</body>
</html>
