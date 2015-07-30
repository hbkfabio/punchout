<?php
/*
	Scryed Labs Punchout!
	Timecard System
	Copyright (c) 2005-2007, Scryed Labs
	http://www.scryedlabs.com
	
	Punchout! is copyrighted free software by Waheed Ayubi <wayubi@gmail.com>.
	You can redistribute it and/or modify it under either the terms of the GPL
	(see COPYING.txt), or the terms of the Artistic License (see LICENSE.txt).
	
	http://sourceforge.net/projects/punchout/
*/
?>
<?php

	require_once ('settings.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>

	<h3>Select a Company</h3>
	
	<?php
		for ($i = 0; $i < CUSTOMER_COUNT; $i++) {
			print '<span id="companyList"><a href="setCookie.php?CompanyId='.constant('CUSTOMER_' . ($i+1) . '_ID').'">'.constant('CUSTOMER_' . ($i+1) . '_NAME').'</a></span>';
			if ( ($i+1) != CUSTOMER_COUNT ) { print " <br> "; }
		}
	?>

</body>
</html>
