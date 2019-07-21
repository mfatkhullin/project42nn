<!DOCTYPE html>
<html>
    <head>
        <title>Расчет загрузки сервисной компании</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
    </head>
    <body>
	<div style="width: 1000px; margin:10px auto;">

	<div class="uk-column-1-3">
		<form method="POST" action="select.php">
		<p><input type="text" name="service_id" id="ser_id" /></p>
		<p><input type="text" name="service_count" id="ser_count" /></p>

		<p><input type="button" name="submit" id="submit" value="submit" /></p>
		</form>
		<p><div id="result"></div></p>
	</div>
	</div>
	
    </body>
</html>