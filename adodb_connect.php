<?php
	include_once ('adodb/adodb.inc.php');
	$dsn = 'mysql://' . MYSQL_USER . ':' . MYSQL_PASS . '@' . MYSQL_HOST . ':' . MYSQL_PORT . '/' . MYSQL_DB; 
	$conn = NewADOConnection($dsn);
	if (!$conn) die("Connection failed");
?>
