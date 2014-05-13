<?php
/**
 * Logbook Module
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */
?>


<?php

    $logbook        = new OOLogbook($REX);
    $logbookRecords = new OOLogbookRecords($REX);
		
    $search  = htmlspecialchars(rex_request("search","string",""));
    $kw      = htmlspecialchars(rex_request("kw","string",""));
    $id      = htmlspecialchars(rex_request("id","string",""));
    $year    = htmlspecialchars(rex_request("year","string",""));
    $clientS = htmlspecialchars(rex_request("client","string",""));
    $catS    = htmlspecialchars(rex_request("cat","string",""));
    $func    = htmlspecialchars(rex_request("func","string",""));
    $finish  = htmlspecialchars(rex_request("finish","string",""));
    $pos     = htmlspecialchars(rex_request("pos","string",""));
	
    $date = date("Y-m-d");	
	
    $rexValueCat = "REX_VALUE[1]";
    if ($rexValueCat && !$catS) {
      $catS = $rexValueCat;
      $finish = 0;
      $func = "search"; 
    }
  
    $jump = "10000";

    $rexValueUserCheck = "REX_VALUE[2]";
    $sqlRef = new rex_sql();

    // Logbook New Entry URL
    $url = "347-0-Logbook.html";

    // Die Filter Pulldown Menues zusammen stellen
    $finishPullDown = $logbook->getPullDownFinish($finish);
    $clientPullDown = $logbook->getPullDownClient($clientS);
    $catPullDown    = $logbook->getPullDownCategories($catS);

    $client = $logbook->getClients();
    $cat    = $logbook->getCategories();

    if ($func == "delete") {
	  $logbookRecords->deleteRecord($id);
	  $func="search";
    }

?>

<h1><?php echo $logbook->lang->msg('showrecords'); ?></h1>
<div class="logbook">

<div id="searchbox" class="miscBox">

<form action="" method="post" name="searchform">

  <p class="formselect">
    <label class="select" for="Client"><?php echo $logbook->lang->msg('client'); ?>:</label>
    <select name="client" size="1" id="client" class="select"> <?php echo $clientPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select" for="cat"><?php echo $logbook->lang->msg('categorie'); ?>:</label>
    <select name="cat" size="1" id="cat" class="select"> <?php echo $catPullDown; ?></select>
  </p>
  
  <p class="formselect">
    <label class="checkbox" for="finish"><?php echo $logbook->lang->msg('finished'); ?>:</label>
    <input type="checkbox" name="finish" id="finish" value="1" <?php if ($finish) echo "checked"; ?>/>
  </p>
 
   <p class="forminput">
    <label class="input" for="finish"><?php echo $logbook->lang->msg('search'); ?>:</label>
    <input type="text" class="text" name="search" id="search" value="<?php echo $search;  ?>" />
   </p>
 
   <input type="hidden" name="func" value="search" class="func" />

   <div class="buttons">
     <a href="javascript:submitform('searchform');" title="<?php echo $logbook->lang->msg('search'); ?>"><span icon="search"></span><?php echo $logbook->lang->msg('search'); ?></a>
     <a href="#" id="hidesearchbox" title="<?php echo $logbook->lang->msg('close'); ?>"><span icon="close"></span><?php echo $logbook->lang->msg('close'); ?></a>
   </div>
  
</form>
</div>

<div class="buttons" id="searchbutton">
   <a href="#" id="viewsearchbox" title="<?php echo $logbook->lang->msg('search'); ?>"><span icon="search"></span><?php echo $logbook->lang->msg('search'); ?></a>
</div>

<p></p>

<table width="100%" cellspacing="0" border="0" class="tablesorter" id="table">
  <thead>
    <tr>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('date'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('start'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('end'); ?></th>	  
      <th><?php echo $logbook->lang->msg('subject'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('client'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('cat'); ?></th>
      <th rowspan="2" style="text-align:left;"><?php echo $logbook->lang->msg('done'); ?></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('date'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('start'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('end'); ?></th>	  
      <th><?php echo $logbook->lang->msg('subject'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('client'); ?></th>
      <th style="text-align:left;"><?php echo $logbook->lang->msg('cat'); ?></th>
      <th rowspan="2" style="text-align:left;"><?php echo $logbook->lang->msg('done'); ?></th>
    </tr>
  </tfoot>
<tbody>

<?php

   if (!$pos) {
     $limit = 0;
   } else {
     $limit = $pos;
   }

  if (!$clientS) {
    $clientS = $logbook->defaultClient;
  }

  if ($func == "search")
  {
    $ret = $logbookRecords->getRecords($search, $catS, $clientS, $finish, $jump, $limit);    
  } else {
    $ret = $logbookRecords->getRecords($search, "", $logbook->defaultClient, "", $jump, $limit);
  }

  $i = 1;
  foreach($ret as $key => $value) {
  
    // Farben zur Erkennung ob ein Task ueberfaellig ist
    $dayDiff = $logbook->dayDiff($date, $value["scheduledend"]);
	$color = "";	
	if ( $value["finish"] == 0 && $value["scheduledend"] ) {
	  if ( $dayDiff == 2 ) {
	    $color = "style=\"color:#DAA520;\"";
	  }
	  if ( $dayDiff <= 1 ) {
	    $color = "style=\"color:#FF8C00;\"";
	  }	  
	  if ( $dayDiff <= -1 ) {
	    $color = "style=\"color:#DC143C;\"";
	  }	  
	}
	
    $i = $i * (-1);

    if ($value["finish"]) {
      $finish = '<span icon="bullet_green">&nbsp;</span>';      
    } else {
      $finish = '<span icon="bullet_red">&nbsp;</span>';      
    }
    

    print '<tr>';
    if ($value['update']) {
      print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$value["update"].'</a></td>';
    } else {
      print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$value["year"].'-'.$value["months"].'-'.$value["day"].'</a></td>';
    }
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$value["scheduledbegin"].'</a></td>';
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$value["scheduledend"].'</a></td>';	
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$value["subject"].'</a></td>';
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$client[$value["client_id"]]['client'].'</a></td>';
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$cat[$value["cat_id"]]['cat'].'</a></td>';
    print '<td style="text-align:left;" ><a '.$color.' href="'.$url.'?id='.$value["id"].'">'.$finish.'</a></td>';
    print '<td style="text-align:right;" ><a '.$color.' href="?id='.$value['id'].'&cat='.$catS.'&client='.$clientS.'&func=delete" icon="delete"></a></td>';
    print '</tr>';
  }
?>

</tbody>
</table>

<div id="pager" class="pager">
  <img src="/files/addons/logbook/icons/control_start_blue.png" class="first"/>
  <img src="/files/addons/logbook/icons/control_rewind_blue.png" class="prev"/>
  <input type="text" readonly="readonly" class="pagedisplay"/>
  <img src="/files/addons/logbook/icons/control_fastforward_blue.png" class="next"/>
  <img src="/files/addons/logbook/icons/control_end_blue.png" class="last"/>
  <select class="pagesize" id="pagesize">
  <option value="10">10</option>
    <option selected="selected" value="20">20</option>
    <option value="30">30</option>
    <option value="40">40</option>
  </select>
</div>

</div>

