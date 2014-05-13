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

<h1>Create Report</h1>
<div class="logbook">
<?php



	$kw      = htmlspecialchars(rex_request("kw","string",""));
	$year    = htmlspecialchars(rex_request("year","string",""));
	$clientS = htmlspecialchars(rex_request("client","string",""));
	$catS    = htmlspecialchars(rex_request("cat","string",""));
	$func    = htmlspecialchars(rex_request("submit","string",""));

    $user = $logbook->rexUser;

	// Die Filter Pulldown Menues zusammen stellen
	$sqlRef = new rex_sql;

    // Kalenderwochen Filter erstellen
	$kwPullDown = $logbook->getPullDownKw($kw);
        
    // Jahres Filter erstellen
	$yearPullDown = $logbook->getPullDownYear($year);

    // Client Filter erstellen
    $clientPullDown = $logbook->getPullDownClient($clientS);
	
    // Kattegorie Filter erstellen
    $catPullDown = $logbook->getPullDownCategories($catS);

	// Die ID's der Clients und Kategorien in Text umsetzten
    $client = $logbook->getCategories();
	$cat = $logbook->getClients();


?>

<div class="rex-area">
<div class="rex-area-content">
<div id="rex-xform" class="xform">

<form name="report" action="" method="post">

  <p class="formselect">
    <label class="select " for="year">Year:</label>
      <select name="year" size="1" id="year" class="select"> <?php echo $yearPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select " for="kw">Kw:</label>
    <select name="kw" size="1" id="kw" class="select"> <?php echo $kwPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select " for="client">Client:</label>
    <select name="client" size="1" id="client" class="select"> <?php echo $clientPullDown; ?></select>
  </p>

  <p class="formselect">
    <label class="select " for="cat">Cat:</label>
    <select name="cat" size="1" id="cat" class="select"> <?php echo $catPullDown; ?></select>
  </p>

  <p class="formsubmit">
    <input type="submit" name="submit" value="create" class="submit" />
  </p>

<hr/>

<table border="0" width="100%">
  <thead>
  </thead>
<tbody>

<?php

  // Filter in SQL setzen
  $sql = "";
  $sqlRef = new rex_sql;
  if ($func == "create") {   
    if ($kw) {
      $sql .= "and kw = '$kw'";
    }

    if ($year) {
      $sql .= "and year = '$year'";
    }

    if ($catS) {
      $sql .= "and cat_id = '$catS'";
    }

    if ($clientS) {
      $sql .= "and client_id = '$clientS'";
    }

  $sqlRef->setQuery("select * from $logbook->table where user = '$user'  $sql ");
  foreach($sqlRef->getArray() as $key => $value) {
    print '<br/><br/><p>';
	  if ($date <> $value['update']) { 
			$date = $value['update'];
			print '<b><i>'.$date.'</i></b><br/><br/>';
                        $i = 1;
		}
    $subject = $value['subject'];
    $description = $value['description'];
    $description = preg_replace("/\n/","<br/>",$description);
    print '- '.$subject.'</br>';
    print '</p>';
    $i++;
  }
  }
?>

</table>

</div>
</div>
</div>
</div>

