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
<?php

    ##TIMEZONE
    define ("TIMEZONE", -3);

	## Database settings.
	define ("MYSQL_HOST", "localhost");
	define ("MYSQL_PORT", "3306");
	define ("MYSQL_USER", "root");
	define ("MYSQL_PASS", "hanson");
	define ("MYSQL_DB", "punchout");
	
	
	## Company settings.
	define ("COMPANY_NAME", "Scryed Labs");
	define ("COMPANY_ATTN", "Waheed Ayubi");
	define ("COMPANY_PHONE", "(800) 123-4567");
	define ("COMPANY_EMAIL", "wayubi@scryedlabs.com");
	

	## Customer settings.
	define ("CUSTOMER_COUNT", 2);
	
	define ("CUSTOMER_1_ID", 100);
	define ("CUSTOMER_1_NAME", "Scryed Labs");
	define ("CUSTOMER_1_ADDRESS", "1 Scryed Path");
	define ("CUSTOMER_1_REGION", "Anaheim, CA 92805");
	define ("CUSTOMER_1_RATE", 50);
	
	define ("CUSTOMER_2_ID", 101);
	define ("CUSTOMER_2_NAME", "Microsoft");
	define ("CUSTOMER_2_ADDRESS", "One Microsoft Way");
	define ("CUSTOMER_2_REGION", "Redmond, WA 98052-6399");
	define ("CUSTOMER_2_RATE", 65);
	
	define ("CUSTOMER_3_ID", 102);
	define ("CUSTOMER_3_NAME", "Apple");
	define ("CUSTOMER_3_ADDRESS", "1 Infinite Loop");
	define ("CUSTOMER_3_REGION", "Cupertino, CA 95014");
	define ("CUSTOMER_3_RATE", 70);

?>
