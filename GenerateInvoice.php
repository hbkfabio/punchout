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
	
	include_once( 'adodb_connect.php' );
	
	for ($i = 0; $i < CUSTOMER_COUNT; $i++) {
		if ($_COOKIE['CompanyId'] == constant("CUSTOMER_".($i+1)."_ID")) {
			$CompanyName = constant("CUSTOMER_".($i+1)."_NAME");
			$CompanyAddress = constant("CUSTOMER_".($i+1)."_ADDRESS");
			$CompanyRegion = constant("CUSTOMER_".($i+1)."_REGION");
			$HourlyRate = constant("CUSTOMER_".($i+1)."_RATE");
		}
	}
	

	## INCLUDES
	require('fpdf153/fpdf.php');


	## EXTEND CLASS
	class PDF extends FPDF {
		//Page header
		function Header() {
		    //Logo
		    #$this->Image('logo.png',10,8,60);
		    //Arial bold 15
			
			
			global $CompanyName;
			global $CompanyAddress;
			global $CompanyRegion;
			global $HourlyRate;
			global $TotalHours;
			
		    $this->SetFont('Times','B',15);
		    $this->Cell(10);
		    $this->Cell(170,10, COMPANY_NAME.' Invoice',0,1,'C');
			
			$this->Line(200,18,10,18);
			
			$this->SetFont('Times','',8);
			$this->Cell(10);
			$this->Cell(170,1, COMPANY_ATTN.'  -  '.COMPANY_PHONE.'  -  '.COMPANY_EMAIL.' ',0,1,'C');
			
			$this->Ln(12);
			
			$this->SetFont('Times','B',14);
			$this->Cell(90,0,$CompanyName,0,0,'L');
			$this->Cell(0,0,'Summary',0,1,'L');
			$this->Ln(6);
			$this->SetFont('Times','',10);
			$this->Cell(90,0,$CompanyAddress,0,0,'L');
			$this->Cell(0,0,'Hours: '.$TotalHours,0,1,'L');
			$this->Ln(4);
			$this->SetFont('Times','',10);
			$this->Cell(90,0,$CompanyRegion,0,0,'L');
			$this->Cell(0,0,'Hourly Rate: $'.number_format($HourlyRate, 2),0,1,'L');

			$this->Ln(8);
			
			$this->SetFont('Times','B',10);
			$this->Cell(90,0,'',0,0,'L');
			$this->Cell(0,0,'Total: $'.number_format($HourlyRate * $TotalHours, 2),0,1,'L');
			
			$this->Ln(15);
			
			global $col1;
			global $col2;
			global $col3;
			global $col4;
			
			$this->SetFont('Times','B',12);
			$this->Cell($col1,0,'Start',0,0);
			$this->Cell($col2,0,'Stop',0,0);
			$this->Cell($col3,0,'Hours',0,0);
			$this->Cell($col4,0,'Comment',0,0);
			$this->Line(200,69,10,69);
			
			
		    //Line break
		    $this->Ln(10);
		}

		//Page footer
		function Footer() {
		    //Position at 1.5 cm from bottom
		    $this->SetY(-15);
		    //Arial italic 8
		    $this->SetFont('Times','',8);
		    //Page number
		    $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
		}
	}





	## FUNCTIONS
	function formatDateForTimeclock($timestamp) {
		if ($timestamp == "0000-00-00 00:00:00") return '<span id="open">Open</span>';
		else return date("M j 'y - g:i a", strtotime($timestamp));
	}
	
	function getDurationClockedIn($start, $stop) {
		if ($stop < $start) return "";
		else return number_format( ( ( strtotime($stop) - strtotime($start) ) / 3600 ), 2);
	}
	
	function stripPDFComment($text) {
		$text = str_replace("<p>", "", $text);
		$text = str_replace("</p>", "", $text);
		$text = str_replace("<br />", "\n", $text);
		$text = str_replace("<br>", "\n", $text);
		$text = str_replace("<em>", "", $text);
		$text = str_replace("</em>", "", $text);
		$text = str_replace("<strong>", "", $text);
		$text = str_replace("</strong>", "", $text);
		$text = str_replace("&nbsp;", " ", $text);
		return $text;
	}



	$fromTimestamp = strtotime($_POST['fromMonth']." ".$_POST['fromDay']." ".$_POST['fromYear']);
	$toTimestamp = strtotime($_POST['toMonth']." ".$_POST['toDay']." ".$_POST['toYear']." 23:59:59");
	
	$sql = "
		select * from timetable 
		where compid = " . $_COOKIE['CompanyId'] . " 
		and start >= '" . date( "Y-m-d H:i:s", $fromTimestamp ) . "' 
		and start <= '" . date( "Y-m-d H:i:s", $toTimestamp ). "' 
		order by timeid desc
		";
	$rs = $conn->Execute( $sql );
		
	/*
	$stmt = $dbh->prepare('select * from timetable where compid='.$_COOKIE['CompanyId'].' and start >= "'.date("Y-m-d H:i:s", $fromTimestamp).'" and start <= "'.date("Y-m-d H:i:s", $toTimestamp).'" order by timeid desc');
	$stmt->execute();
	*/
	
	## GET MAX HOURS
	while ( !$rs->EOF )
	{
		$TotalHours = number_format( $TotalHours + getDurationClockedIn( $rs->fields['start'], $rs->fields['stop'] ), 2 );
		$rs->MoveNext();
	}
	
	/*
	while ($row = $stmt->fetch()) {
		$TotalHours = number_format($TotalHours + getDurationClockedIn($row['start'], $row['stop']), 2);
	}
	*/
	

	
	## PDF SETTINGS
	$col1 = 35;
	$col2 = 35;
	$col3 = 20;
	$col4 = 90;
	
	
	$pdf=new PDF('P','mm','A4');
	$pdf->AliasNbPages();
	$pdf->AddPage();



	## RERUN SQL FOR PDF
	$rs = $conn->Execute( $sql );
	/*
	$stmt->execute();
	*/

	while ( !$rs->EOF )
	{
	
	//while ($row = $stmt->fetch()) {

		#$TotalHours = $TotalHours + getDurationClockedIn($row['start'], $row['stop']);
		
		$pdf->SetFont('Times','',9);
		$pdf->Cell($col1,4,formatDateForTimeclock($rs->fields['start']),0,0);
		$pdf->Cell($col2,4,formatDateForTimeclock($rs->fields['stop']),0,0);
		$pdf->Cell($col3,4,getDurationClockedIn($rs->fields['start'], $rs->fields['stop']),0,0);
		#$pdf->MultiCell(100, 0, formatDateForTimeclock($row['stop']), 0);
		#$pdf->Cell($col4,0,$row['comment'],0,1);
		$pdf->MultiCell(0, 4, stripPDFComment($rs->fields['comment']), 0);
		
		$pdf->Ln(5);
		
		$rs->MoveNext();
	}
	
	
	## DO PDF
	$pdf->Output();


	include_once( 'adodb_disconnect.php' );

?>
