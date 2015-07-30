<?php
/*
	Scryed Labs Punchout!
	Timecard System
	Copyright (c) 2005-2007, Scryed Labs
	http://www.scryedlabs.com
	
	Punchout! is copyrighted free software by Waheed Ayubi <wayubi@gmail.com>.
	You can redistribute it and/or modify it under either the terms of the GPL
	(see COPYING.txt), or the terms of the Artistic License (see README.txt).
	
	http://sourceforge.net/projects/punchout/
*/
?>
<style>
	body {
		margin: 0px;
	}
	div#siteHeader {
		background-color: #0099CC;
		padding: 15px;
		margin-bottom: 20px;
	}
	div#siteHeader span#companyName {
		font-family: Georgia, "Times New Roman", Times, serif;
		font-size: xx-large;
		display: block;
	}
	div#siteHeader span#companyList {
		font-family: Georgia, "Times New Roman", Times, serif;
		font-size: small; 
	}
	div#siteHeader span#companyList a:visited {
		color: #FFFFFF;
		text-decoration: none;
	}
	div#siteHeader span#companyList a:hover {
		color: #003399;
	}
</style>

<body>


<div id="siteHeader">
	<span id="companyName"><?php
		for ($i = 0; $i < CUSTOMER_COUNT; $i++) {
			if ($_COOKIE['CompanyId'] == constant('CUSTOMER_' . ($i+1) . '_ID')) {
				print constant('CUSTOMER_' . ($i+1) . '_NAME');
			}
		}
	?></span>
	<?php
		for ($i = 0; $i < CUSTOMER_COUNT; $i++) {
			print '<span id="companyList"><a href="setCookie.php?CompanyId='.constant('CUSTOMER_' . ($i+1) . '_ID').'">'.constant('CUSTOMER_' . ($i+1) . '_NAME').'</a></span>';
			if ( ($i+1) != CUSTOMER_COUNT ) { print " | "; }
		}
	?>
</div>
