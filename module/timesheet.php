<?php

/**
 * Logbook Module
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

$logbook = new OOLogbook($REX); 
$logbook = new OOLogbookRecords($REX); 

?>

<div id="logbook_projektbericht"> 
<h1>Projektbericht <?php echo date('M.Y');  ?> </h1>
 
<?php
 

	$func    = htmlspecialchars(rex_request("submit","string",""));
	$subject = htmlspecialchars(rex_request("subject","string",""));    
	$from    = htmlspecialchars(rex_request("from","string",""));    
	$month    = htmlspecialchars(rex_request("month","string",""));    
	$year    = htmlspecialchars(rex_request("year","string",""));    
    $to      = htmlspecialchars(rex_request("to","string",""));    
    $id      = htmlspecialchars(rex_request("id","string",""));    
    $rest    = htmlspecialchars(rex_request("rest","string",""));    
	$clientS = htmlspecialchars(rex_request("client","string",""));
	$date    = preg_split("/\-/",htmlspecialchars(rex_request("date","string","")));
			
    $user = $REX['COM_USER']->getValue('login');
    if (!$month) {
      $month = date('m'); 
    }	
    if (!$year) {
      $year = date('Y'); 
    }	
  	

	$sqlRef = new rex_sql;	

	$totalWorkHours = 0;
	$totalWorkDays  = 0;
	
	$sqlRef->setQuery("select price from $logbook->tableClient where user = '$user' and id = '$clientS' limit 1 ");
	$res = $sqlRef->getArray();
    $price = $res[0]['price'];	

	
	$timeTo = preg_split("/\:/",$to);
	$timeFrom = preg_split("/\:/",$from);
	$timeRest = preg_split("/\:/",$rest);
    $workHours = round((((((int)$timeTo[0]*60)+((int)$timeTo[1]))-(((int)$timeFrom[0]*60)+((int)$timeFrom[1])))-(((int)$timeRest[0]*60)+((int)$timeRest[1])))/60,2);

	if ($func == "save") {	
		$sqlRef = new rex_sql;
		$sqlRef->setTable($logbook->tableHour);
		$sqlRef->setValue("subject", $subject);
		$sqlRef->setValue("day", $date[2]);
		$sqlRef->setValue("month", $date[1]);
		$sqlRef->setValue("year", $date[0]);
		$sqlRef->setValue("user", $user);
		$sqlRef->setValue("from", $from);
		$sqlRef->setValue("client", $clientS);
		$sqlRef->setValue("to", $to);
		$sqlRef->setValue("rest", $rest);
		$sqlRef->setValue("price", ($workHours*$price));
        $sqlRef->insert();
	}

	if ($func == "delete") {
	   $sqlRef->setTable($logbook->tableHour);
	   $sqlRef->setWhere(sprintf("id = '%d'",$id));
	   $sqlRef->delete();
    }

	$sqlRef->setQuery("select distinct(month) from $logbook->tableHour");
    $monthPullDown = '<option value=""></option>';
	foreach($sqlRef->getArray() as $key => $value) {
         $select = "";
        if ($month == $value['month']) { 
            $select = 'selected="selected"';
        }
	    $monthPullDown .= '<option '.$select.' value="'.$value['month'].'">'.$value['month'].'</option>';
	}

	$sqlRef->setQuery("select distinct(year) from $logbook->tableHour");
    $yearPullDown = '<option value=""></option>';
	foreach($sqlRef->getArray() as $key => $value) {
        $select = "";
        if ($year == $value['year']) { 
            $select = 'selected="selected"';
        }
	    $yearPullDown .= '<option '.$select.' value="'.$value['year'].'">'.$value['year'].'</option>';
	}	
	
	
	
	$sqlRef->setQuery("select * from $logbook->tableClient where user = '$user' or user = '0' ");
    $clientPullDown = '<option value=""></option>';
	foreach($sqlRef->getArray() as $key => $value) {
        $select = "";	
        if ($clientS == $value['id']) { 
            $select = 'selected="selected"';
        }
	    $clientPullDown .= '<option '.$select.' value="'.$value['id'].'">'.$value['client'].'</option>';
	}

?>


<div class="rex-area">
<div class="rex-area-content">
<div id="rex-xform" class="xform">

<form action="" method="post">

  <p class="formselect">
    <label class="select " for="year">Year:</label>
    <select name="year" size="1" id="year" class="select"> <?php echo $yearPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select " for="month">Month:</label>
    <select name="month" size="1" id="month" class="select"> <?php echo $monthPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select " for="client">Client:</label>
    <select name="client" size="1" id="client" class="select "> <?php echo $clientPullDown; ?></select>
  </p>    
  
  <p class="formsubmit">
    <input type="submit" name="submit" value="search" class="submit" />
  </p>
  
</form>

<hr/>
<form action="" method="post">

    <p style="display:hidden;"><input type="hidden" id="client" name="client" value="<?php echo $clientS; ?>" /></p>

	<p class="formtext formlabel-title">
	  <label class="text" for="date" >Date:</label>
	  <input readonly="readonly" type="date" class="text" name="date" id="date" value="<?php echo date('Y-m-d');  ?>" />
	</p>

	<p class="formtext formlabel-title">
	  <label class="text" for="from" >Time From:</label>
	  <input readonly="readonly" type="time" class="text" name="from" id="from" value="" />
	</p>
	
    <p class="formtext formlabel-title">
	  <label class="text" for="to" >Time To:</label>
	  <input readonly="readonly" type="time" class="text" name="to" id="to" value="" />
	</p>

	<p class="formtext formlabel-title">
	  <label class="text" for="rest" >Rest:</label>
	  <input type="time" class="text" name="rest" id="rest" value="" />
	</p>
	
	<p class="formtext formlabel-title">
 	  <label class="text" for="subject" >Subject:</label>
	  <input type="text" class="text" name="subject" id="subject" value="" />
	</p>
	
  <p class="formsubmit">
    <input type="submit" name="submit" value="save" class="submit" />
  </p>
	
</form>
<hr />

<table border="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Date</th>
      <th>From</th>
      <th>To</th>
      <th>Rest</th>
      <th>Hours</th>
      <th>Day</th>
      <th>Subject</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>

<?php
	
    $sqlRef->setQuery("select * from $logbook->tableHour where user = '$user' and month = '$month' and year = '$year' and client = '$clientS' order by day asc");	
	foreach ($sqlRef->getArray() as $key => $value) {

	
	  $timeTo = preg_split("/\:/",$value['to']);
	  $timeFrom = preg_split("/\:/",$value['from']);
	  $timeRest = preg_split("/\:/",$value['rest']);
	  $workHours = round((((((int)$timeTo[0]*60)+((int)$timeTo[1]))-(((int)$timeFrom[0]*60)+((int)$timeFrom[1])))-(((int)$timeRest[0]*60)+((int)$timeRest[1])))/60,2);
	  $workDays = round($workHours / 8,2);

	  if ( $value['price'] == "" ) {		
	    $dayPrice = $workHours*$price;
	  } else {
	    $dayPrice = $value['price'];
	  }
		
	
      print '<tr>';
      print '<td>'.$value['day'].'.'.$value['month'].'</td>';
      print '<td>'.$value['from'].'</td>';
      print '<td>'.$value['to'].'</td>';
      print '<td>'.$value['rest'].'</td>';
      print '<td>'.$workHours.'</td>';
      print '<td>'.$workDays.'</td>';
      print '<td>'.$value['subject'].'</td>';
      print '<td>'.$dayPrice.'</td>';
      print '<td><a href="?id='.$value['id'].'&submit=delete" icon="delete"></a></td>';
      print '</tr>';
      
      $totalWorkHours += $workHours;
      $totalWorkDays  += $workDays;
	}

?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="9"><hr/></td>
    </tr>
    <tr>
		  <td colspan="5" style="text-align:right;"><?php echo $totalWorkHours; ?></td>
		  <td style="text-align:right;"><?php echo $totalWorkDays; ?></td>
		  <td></td>
		</tr>
   <tr>
     <td colspan="6" style="text-align:right;">
<?php echo round(($totalWorkHours*$price),2); ?>Euro<br/>
VAT <?php echo round(($totalWorkHours*$price*0.19),2); ?>Euro<br/>
</td>
 <td></td>
</tr>
   </tr>
  </tfoot>
</table>
 
 <script type="text/javascript">
<!--
  $(function() {
     $('#date').click(function() {
	   $('#date').datepicker({ dateFormat: 'yy-mm-dd' });
	 });
     $('#to').click(function() {
	   $('#to').timepicker();
	 }); 
     $('#from').click(function() {
	   $('#from').timepicker();
	 }); 
	 
  });
//-->
</script>
 
</div></div></div></div>
