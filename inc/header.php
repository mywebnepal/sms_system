<!DOCTYPE html>
<?php 
    if(!isset($_SESSION)) {@session_start();}  
    @session_cache_limiter('private, must-revalidate');
    @session_cache_expire(60); 
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<title><?php echo isset($_GET['title'])?$_GET['title']:'SMS system'; ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="css/pikaday.css?v=2"/>
	<script src="js/moment.js"></script>
	<script src="js/pikaday.js"></script>
	<style>
		#calendarHere1 {
			position: relative;
			height: 0px;
			width: 200px;
		}
			#calendarHere2 {
			position: relative;
			height: 0px;
			width: 200px;
		}
			#calendarHere3 {
			position: relative;
			height: 0px;
			width: 200px;
		}
	</style>
</head>
<body>
<div class="row">