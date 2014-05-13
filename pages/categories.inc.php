<?php

/**
 * Logbook Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */


// request variables
$func = rex_request("func","string","");

// save the new product item
if ($func == "add_save") {
  $categorie = rex_request("categorie","string","");
  $default   = rex_request("default","string","");
  
  $sqlRef = new rex_sql;
  $sqlRef->setTable($tableCategorie);
  $sqlRef->setValue("cat", $categorie);
  $sqlRef->setValue("default", $default);
  $sqlRef->insert();

 }

// delete a item                                                                                                                                                                                                                                                            
if ($func == "del") {
  $categorie_id = rex_request("categorie_id","string","");
  
  $sqlRef = new rex_sql;
  $sqlRef->setTable($tableCategorie);
  $sqlRef->setWhere(sprintf("id =  '%d'",$categorie_id));
  $sqlRef->delete();
 }


// create formular for add or edit a item
if ($func == "add") {

  print '<div class="rex-area">';
  print '<h3 class="rex-hl2">'.$I18N->msg("add_categorie").'</h3>';
  print '<div class="rex-area-content">';
  print '<div id="rex-xform" class="xform">';
  print '<form action="index.php" method="post" enctype="multipart/form-data">';

  print '<p class="formtext formlabel-name">';
  print '<label class="text" for="name" >'.$I18N->msg("categorie").'</label>';
  print '<input type="text" class="text" name="categorie" id="categorie" value="" />';
  print '</p>';

  print '<p class="formtext formlabel-name">';
  print '<label class="text" for="name" >'.$I18N->msg("default").'</label>';
  print '<input type="text" class="text" name="default" id="default" value="" />';
  print '</p>';

  print '<p style="display:hidden;"><input type="hidden" id="page" name="page" value="logbook" /></p>';
  print '<p style="display:hidden;"><input type="hidden" id="subpage" name="subpage" value="categories" /></p>';
  print '<p style="display:hidden;"><input type="hidden" name="func" value="add_save" /></p>';
  
  print '<p class="formsubmit">';
  print '<input type="submit" name="submit" value="'.$I18N->msg("save").'" class="submit" />';
  print '</p>';
  print '</form>';
  print '</div>';
  print '</div>';
  print '</div>';
  print '<hr/>';
 }


// print out all categories
$sqlRef = new rex_sql;
$sqlRef->setQuery("select * from $tableCategorie");


print '<table class="rex-table">';
print '<thead><tr>';
print '<th class="rex-icon">';
print '<a class="rex-i-element rex-i-article-add" href="index.php?page=logbook&subpage=categories&func=add" title="'.$I18N->msg('add_categories').'">';
print '<span class="rex-i-element-text">'.$I18N->msg("add_categories").'</span>';
print '</a>';
print '</th>';
print '<th>'.$I18N->msg('id').'</th>';
print '<th>'.$I18N->msg('categorie').'</th>';
print '<th>'.$I18N->msg('default').'</th>';
print '<th width="20">'.$I18N->msg('delete').'</th>';
print '</tr></thead>';
print '<tbody>';


foreach($sqlRef->getArray() as $key => $value) {
  print "<tr>";
  print "<td></td>";
  print "<td>".$value['id']."</td>";
  print "<td>".$value['cat']."</td>";
  print "<td>".$value['default']."</td>";
  print '<td><a href="index.php?page=logbook&subpage=categories&func=del&categorie_id='.$value['id'].'">'.$I18N->msg("delete").'</td>';
  print "</tr>";
}

?>

  </tbody>
</table>