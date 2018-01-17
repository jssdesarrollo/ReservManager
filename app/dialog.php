<?php
?>
<!DOCTYPE html>
<?php
$mensaje1 = $_GET['m1'];
$mensaje2 = $_GET['m2'];
?>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Error</title>
	<link rel="stylesheet" href="../css/themes/jquery.mobile-1.4.5.min.css">
	<script src="scripts/jquery.min.js"></script>
	<script src="scripts/jquery.mobile-1.4.5.min.js"></script>
</head>
<body>

<div data-role="page" data-close-btn="none" data-dialog="true">
		<div data-role="header">
		<h1>Error</h1>
		</div>

		<div role="main" class="ui-content">
		<h1><?php echo $mensaje1 ?></h1>
		<p><?php echo $mensaje2 ?> </p>
			<a href="index.html" data-rel="back" class="ui-btn ui-shadow ui-corner-all ui-btn-b">OK</a>
		</div>
	</div>

</body>
</html>

