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

require_once ('settings.php');
include 'siteHeader.php';

include_once( 'adodb_connect.php' );

##############
# Functions
##############


function formatDateForTimeclock($timestamp) {
	if ($timestamp == "0000-00-00 00:00:00") return '<span id="open">Open</span>';
	else 
        return date("M j 'y - g:i a", strtotime($timestamp));
}

function getDurationClockedIn($start, $stop) {
	if ($stop < $start) return "";
	else
//        print_r ($stop);
//        print_r ($start);
        return number_format( ( ( strtotime($stop) - strtotime($start) ) / 3600 ), 2);
}


function printTableRow($rowArray, $cssid = "") {
	print '<tr id="'.$cssid.'">';
	for ($i = 0; $i < sizeof($rowArray); $i++) {
		print '<td>'.$rowArray[$i].'</td>';
	}	
	print '</tr>';
}

function printClock() {
	print '<!--[if IE]><script type="text/javascript" src="./js/excanvas.js"></script><![endif]-->';
	print '<script type="text/javascript" src="coolclock.js"></script>';
	print '<center><canvas id="c1" class="CoolClock"></canvas></center>';
}



##############

	if ( $_GET['stop'] )
	{
        print_r ($_GET['stop']);
		$sql = "
			select * from timetable 
			where stop = '0000-00-00 00:00:00' 
			and compid = " . $_COOKIE['CompanyId'] ."
			";
		$rs = $conn->Execute( $sql );   

		$record = array( 
			'stop' => gmdate( "Y-m-d H:i:s", time() + 3600*(TIMEZONE+date("I" ))), 
			'comment' => $_GET['elm1'],
			);
		$sql = $conn->getUpdateSql( $rs, $record );
		$conn->Execute( $sql );
		
		print "<script>location.href='timeclock.php';</script>";
	}
	
	/*
	if ($_GET['stop']) {
	
		$record = array( 
			'comment' => $_GET['elm1'],
			
			);
	
		$sql = "update timetable set stop=NOW(),"
	
		$dbh->beginTransaction();
		$stmt = $dbh->prepare("update timetable set stop=NOW(), comment=(:comment) where stop='0000-00-00 00:00:00' and compid=".$_COOKIE['CompanyId']);
		$stmt->bindParam(':comment', $_GET['elm1']);
		$stmt->execute();
		$dbh->commit();
		print "<script>location.href='timeclock.php';</script>";
	}
	*/
	
	elseif ( $_GET['start'] )
	{
		$sql = "
			select * from timetable
			";
		$rs = $conn->Execute( $sql );
		
		$record = array( 
			'compid' => $_COOKIE['CompanyId'],
			);
		$sql = $conn->getInsertSql( $rs, $record );
		$conn->Execute( $sql );
		
		print "<script>location.href='timeclock.php';</script>";
	}
	
	/*
	else if ($_GET['start']) {
		$dbh->beginTransaction();
		$stmt = $dbh->prepare("insert into timetable (compid) values (:compid)");
		$stmt->bindParam(':compid', $compid);
		$compid = $_COOKIE['CompanyId'];		
		$stmt->execute();
		$dbh->commit();
		print "<script>location.href='timeclock.php';</script>";
	}
	*/
	
	
	$sql = "
		select * from timetable 
		where compid = " . $_COOKIE['CompanyId'] . " 
		order by timeid desc
		";
	$rs = $conn->Execute( $sql );
	
	/*
	$stmt = $dbh->prepare('select * from timetable where compid='.$_COOKIE['CompanyId'].' order by timeid desc');
	$stmt->execute();
	$row = $stmt->fetch();
	*/
	
	
	if ( ( $rs->fields['start'] != "0000-00-00 00:00:00" ) && ( $rs->fields['stop'] == "0000-00-00 00:00:00" )  )
	{
		$started = true;
		$start_time = $rs->fields['start'];
	}
	
	else
	{
		$started = false;
	}
	
	/*
	if ( ($row['start'] != "0000-00-00 00:00:00") && ($row['stop'] == "0000-00-00 00:00:00") ) {
		$started = true;
		$start_time = $row['start'];
	} else {
		$started = false;
	}
	*/
	
	
	printClock();
	
	/*
	?>
	<br><br>
	<input id="clock" type="text" value="0" style="font-family: Garamond; font-size: xx-large; border: 0px; text-align: center;" readonly>
	<br><br>
	<?php
	*/
	
	
	if ($started) {
	?>
	<br />
	
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
		// Notice: The simple theme does not use all options some of them are limited to the advanced theme
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",			
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,copy,paste,separator,charmap",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : ""
		});
	</script>
	
	
	
	<!-- /tinyMCE -->
	<center>
		<form name="stopclock" style="border: 0px;" action="timeclock.php" method="get">
		<textarea id="elm1" name="elm1" rows="5" cols="40">
		</textarea>
		<input type="hidden" name="stop" value="1">
		</form>
	</center>
	
	
	
	
	<?php
	}
	
	
	print '<center>';
	print '<br>';
	if ($started) {
		print '<input type="button" value="Punch Out !" onClick="document.stopclock.submit();" />';
	} else {
		print '<input type="button" value="Punch In !" onClick="javascript:location.href=(\'timeclock.php?start=1\')" />';
	}
	print '</center>';
	
	
	
	
	
	
	
	
	?>
		<style>
			table#timelist {
				margin-top: 20px;
				width: 90%;
			}
			table#timelist tr#labels {
				background-color: #DDDDDD;
			}
			table#timelist tr td {
				font-family: Georgia, "Times New Roman", Times, serif;
				font-size: small;
				padding: 10px;
			}
			span#open {
				color: #CC0000;
			}
		</style>
	<?php
	
	
	$sql = "
		select * from timetable 
		where compid = " . $_COOKIE['CompanyId'] . " 
		order by timeid desc
		";
	$rs = $conn->Execute( $sql );
	
	/*
	$stmt = $dbh->prepare('select * from timetable where compid='.$_COOKIE['CompanyId'].' order by timeid desc');
	$stmt->execute();
	*/
	
	print '<center>';
	print '<table id="timelist" border=1 bordercolor="#DDDDDD" style="border-collapse: collapse;">';
	
	$rowArray = array (
		0 => "Start Time",
		1 => "Stop Time",
		2 => "Duration",
		3 => "Comment",
	);
	printTableRow($rowArray, "labels");
	
	while ( !$rs->EOF )
	{
		$rowArray = array(
			0 => formatDateForTimeclock( $rs->fields['start'] ),
			1 => formatDateForTimeclock( $rs->fields['stop'] ),
			2 => getDurationClockedIn( $rs->fields['start'], $rs->fields['stop'] ),
			3 => $rs->fields['comment'],
			);
			
		printTableRow( $rowArray );
		
		$rs->MoveNext();
	}
	
	/*
	while ($row = $stmt->fetch()) {
		$rowArray = array (
			0 => formatDateForTimeclock($row['start']),
			1 => formatDateForTimeclock($row['stop']),
			2 => getDurationClockedIn($row['start'], $row['stop']),
			3 => $row['comment'],
		);
		printTableRow($rowArray);
	}
	*/
	
	print '</table>';
	print '</center>';
	
	
	
	
	/*foreach ($dbh->query('SELECT * from timetable order by timeid desc') as $row) {
	print "<pre>";
	print_r ($row);
	print "</pre>";
	}*/
	

?>




	<center>
	<table id="timelist" border=1 bordercolor="#DDDDDD" style="border-collapse: collapse;">
		<tr id="labels">
			<td colspan="3">Generate Invoice</td>
		</tr>
		<form action="GenerateInvoice.php" method="post">
		<tr>
			<td>From&nbsp;&nbsp;
				<select name="fromMonth">
					<option>January</option>
					<option>February</option>
					<option>March</option>
					<option>April</option>
					<option>May</option>
					<option>June</option>
					<option>July</option>
					<option>August</option>
					<option>September</option>
					<option>October</option>
					<option>November</option>
					<option>December</option>
				</select>
				<select name="fromDay"><?php
				for ($i = 1; $i <= 31; $i++) {
					print '<option>'.$i.'</option>';
				}
				?></select>
				<select name="fromYear"><?php
				for ($i = 2000; $i <= date("Y",time()); $i++) {
					print '<option>'.$i.'</option>';
				}
				?></select>&nbsp;&nbsp;
				To&nbsp;&nbsp;
				<select name="toMonth">
					<?php $cur_month = date("F", time()); ?>
					<option<?php if ($cur_month == "January") { print ' selected'; } ?>>January</option>
					<option<?php if ($cur_month == "February") { print ' selected'; } ?>>February</option>
					<option<?php if ($cur_month == "March") { print ' selected'; } ?>>March</option>
					<option<?php if ($cur_month == "April") { print ' selected'; } ?>>April</option>
					<option<?php if ($cur_month == "May") { print ' selected'; } ?>>May</option>
					<option<?php if ($cur_month == "June") { print ' selected'; } ?>>June</option>
					<option<?php if ($cur_month == "July") { print ' selected'; } ?>>July</option>
					<option<?php if ($cur_month == "August") { print ' selected'; } ?>>August</option>
					<option<?php if ($cur_month == "September") { print ' selected'; } ?>>September</option>
					<option<?php if ($cur_month == "October") { print ' selected'; } ?>>October</option>
					<option<?php if ($cur_month == "November") { print ' selected'; } ?>>November</option>
					<option<?php if ($cur_month == "December") { print ' selected'; } ?>>December</option>
				</select>
				<select name="toDay"><?php
				for ($i = 1; $i <= 31; $i++) {
					print '<option';
					if ($i == date("j",time())) { print ' selected'; }
					print '>'.$i.'</option>';
				}
				?></select>
				<select name="toYear"><?php
				for ($i = 2000; $i <= date("Y",time()); $i++) {
					print '<option';
					if ($i == date("Y",time())) { print ' selected'; }
					print '>'.$i.'</option>';
				}
				?></select>
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="GenerateInvoice" value="Generate" />
			</td>
		</tr>
		</form>
	</table>
	</center>



<?php
/*
<script>

	var clock = document.getElementById('clock');
	var clockvalue = clock.value;

	function startstop()
		{
		var startdate = new Date();
		var starttime = startdate.getTime();
		counter(starttime);
	}
	
	function counter(starttime)
		{
		
		//var currenttime = new Date();
		//var timediff = currenttime.getTime() - starttime;
			
			
			clockvalue++;
			clock.value = clockvalue;
			
			//timediff = timediff + stoptime
			//refresh = setTimeout('counter(' + starttime + ');',10);
		
			setTimeout ('counter('+starttime+');', 1);
		
		}
	
	startstop();

</script>
*/

include_once( 'adodb_disconnect.php' );

?>

<br /><br />
