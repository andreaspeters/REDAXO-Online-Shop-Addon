<?php

/**
 * Logbook Module
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

$logbook = new OOLogbook($REX); 
$logbook = new OOLogbookRecords($REX); 

// request variables
$func   = htmlspecialchars(rex_request("submit","string",""));
$client = htmlspecialchars(rex_request("client","string",""));
$user   = $logbook->rexUser;

// save the new product item
if ($func == "save") {
  $default = htmlspecialchars(rex_request("default","string",""));
  $price   = htmlspecialchars(rex_request("price","string",""));
  $sqlRef = new rex_sql;
  $sqlRef->setTable($logbook->tableClient);
  $sqlRef->setValue("client", $client);
  $sqlRef->setValue("user", $user);
  $sqlRef->setValue("price", $price);
  $sqlRef->setValue("default", $default);
  $sqlRef->insert();

 }


// delete a item                                                                                                                                                                                                                                                            
if ($func == "del") { 
  $sqlRef = new rex_sql;
  $sqlRef->setTable($logbook->tableClient);
  $sqlRef->setWhere(sprintf("id = '%d' and user = '$user'",$client));
  $sqlRef->delete();
 }
 
 if ($func == "edit") {
   $curClient = $logbook->getClients($client); 
 }


?>


  <div class="rex-area">
    <h3>Add Client</h3>
    <div class="rex-area-content">
      <div id="rex-xform" class="xform">

<?php	  
// create formular for add or edit a item
if ($func == "add" || $func == "edit") {

  if ($default) {
  	$checked = "checked";
  }	

?>
        <form action="" method="post">
        
          <p class="formtext formlabel-name">
            <label class="text" for="name" >Client:</label>
            <input type="text" class="text" name="client" id="client" value="<?php echo $curClient[$client]['client']; ?>" />
          </p>
          
          <p class="formtext formlabel-name">
            <label class="text" for="name" >Price:</label>
            <input type="text" class="text" name="price" id="price" value="<?php echo $curClient[$client]['price']; ?>" />
          </p>
		  
          <p class="formtext formlabel-title">
             <label class="checkbox" for="default" >Default:</label>
             <input type="checkbox" class="checkbox" name="default" value="1" id="default" <?php echo $checked; ?>/>
          </p>		           
          
          <p class="formsubmit">
            <input type="submit" name="submit" value="save" class="submit" />
          </p>
        </form>
        <hr/>

<?php
 }
 
// get out all categories
$ret = $logbook->getClients(); 
?>

<table border="0" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>
        <a href="?submit=add" title="Add Client" icon="add"></a>
      </th>
      <th>Id</th>
      <th>Clients</th>
      <th>Price</th>
      <th>Default</th>
      <th width="20">delete</th>
    </tr>
  </thead>
  <tbody>

<?php
  foreach($ret as $key => $value) {
    print "<tr>";
    print "<td></td>";
    print '<td><a href="?submit=edit&client='.$value['id'].'">'.$value['id'].'</a></td>';
    print '<td><a href="?submit=edit&client='.$value['id'].'">'.$value['client'].'</a></td>';
    print '<td><a href="?submit=edit&client='.$value['id'].'">'.$value['price'].'</a></td>';
    print '<td><a href="?submit=edit&client='.$value['id'].'">'.$value['default'].'</a></td>';
    print '<td><a href="?submit=del&client='.$value['id'].'" icon="delete"></a></td>';
    print "</tr>";
  }
?>

  </tbody>
</table>
   </div>
 </div>
</div>
