<?php

/**
 * Logbook Module
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

?>

<div class="logbook">

<?php
  $logbook        = new OOLogbook($REX);  
  $logbookRecords = new OOLogbookRecords($REX);  
  
  // request variables
  $func = htmlspecialchars(rex_request("func","string",""));
  $id   = htmlspecialchars(rex_request("id","string",""));
  $user = $logbook->rexUser;
  
  // save the new product item
  if ($func) {
  	$update = preg_split("/\-/", date("Y-m-d"));
  	$ts = mktime(0,0,0,$update[1],$update[2],$update[0]); 
  	$kw = date('W',$ts);
  	$update = date("Y-m-d");
  
  	$subject         = htmlspecialchars(rex_request("subject","string",""));
  	$description     = rex_request("description","string","");
  	$client          = htmlspecialchars(rex_request("client","string",""));
  	$cat             = htmlspecialchars(rex_request("cat","string",""));
  	$date            = preg_split("/\-/", htmlspecialchars(rex_request("createdate","string","")));
  	$finish          = htmlspecialchars(rex_request("finish","string",""));    
  	$parent          = htmlspecialchars(rex_request("parent","string",""));    
  	$scheduledbegin  = htmlspecialchars(rex_request("scheduledbegin","string",""));    
        $scheduledend    = htmlspecialchars(rex_request("scheduledend","string",""));    		
  	$diff_left       = htmlspecialchars(rex_request("diff_left","string",""));    
  	$diff_right      = htmlspecialchars(rex_request("diff_right","string",""));    
  
  	$sqlRef = new rex_sql;
  	$sqlRef->setTable($logbook->table);
  	$sqlRef->setValue("subject", $subject);
  	$sqlRef->setValue("day", $date[2]);
  	$sqlRef->setValue("months", $date[1]);
  	$sqlRef->setValue("year", $date[0]);
  	$sqlRef->setValue("client_id", $client);
  	$sqlRef->setValue("cat_id", $cat);
  	$sqlRef->setValue("kw", $kw);
  	$sqlRef->setValue("finish", $finish);
        $sqlRef->setValue("active", 1);
        $sqlRef->setValue("update", $update);
        $sqlRef->setValue("scheduledbegin", $scheduledbegin);		
  	$sqlRef->setValue("scheduledend", $scheduledend);		
  	$sqlRef->setValue("user", $REX['COM_USER']->getValue("login"));
  	$sqlRef->setValue("description", $description);

  	if ($func == "save") {
  	  $sqlRef->insert();
  	}
  
  	if ($func == "update") {
  	  // Update unter neuer id speichern
  	  $sqlRef->setValue("parent", "$parent");
  	  $sqlRef->insert();
  
          // alte version auf inaktive setzen
  	  $sqlRef = new rex_sql;
  	  $sqlRef->setTable($logbook->table);
          $sqlRef->setValue("active", 0);
  	  $sqlRef->setValue("finish", 0);
  	  $sqlRef->setWhere("id = $parent");
  	  $sqlRef->update();
  
  	  // ID der neuen aktiven Version finden
  	  $sqlRef->setQuery("select id, parent from $logbook->table where parent  = $parent and active = 1 and user = '$user'  limit 1");
  	  $res = $sqlRef->getArray();
  	  $id = $res[0]['id'];
  	}
  
  }
  
  // wenn id angegeben wurde, dann diesen record einlesen
  if ($id) {
  	$sqlRef = new rex_sql;
        $title = $logbook->lang->msg('updaterecord');  
	
  	// wenn der diff angegeben hat, soll dieser ausgefuehrt werden
  	if ($diff_left && $diff_right) {
  	  $contentDiffLeft = $logbookRecords->getContentOfRecord($diff_left);
         
  	  $contentDiffRight = $logbookRecords->getContentOfRecord($diff_right);
         
  	  $diffDescription = $logbook->htmlDiff($contentDiffLeft['description'], $contentDiffRight['description']);
  	}
  	
  	// auslesen des aktuellen eintrages
  	$content = $logbookRecords->getContentOfRecord($id);
  
  	// das datum des aktuellen eintrages zusammen setzen
  	$date = $content['year'].'-'.$content['months'].'-'.$content['day'];
  
  	// wenn der eintag nicht der aktive ist, z.b. weil der user sich die historie anschaut, soll dieser readonly sein
  	// handelt es sich um einen aktiven, ist dieser beschreibbar, sofern der eintrag nicht auf finish gesetzt wurde.
  	if ($content['active'] == 0 || $content['finish']) {
        $title = "Show Record";	
  	$readonly = 'readonly="readonly"';
	$tinyReadOnly = "readonly : 1,";
  	// wurde das aktuelle dokument auf finish gesetzt, soll die checkbox einen haken bekommen und
  	if ($content['finish']) {
  	  $checked = "checked";
  	}			
   } 
	
	
    

	// wenn id angegeben wurde, soll die metabox nicht angezeigt werden
?>	
    <script language="javascript">
    <!--
      $(document).ready(function() {
		$('#metabox').fadeOut();	  
        $('#metabutton').fadeIn();       
      });
    //-->
	</script>
<?php	
  } else {
    $title = $logbook->lang->msg('newrecord');
  }
  

  print "<h1>$title</h1>";  
  
  if ($id) {      
    print "<p><b>";
    print $content['subject'];
    print "</b></p><p></p>";
  }
?>

<!--  Syntaxhighlighter  //-->
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shCore.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushCSharp.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushXml.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushSql.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushVb.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushBash.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushPerl.js"></script>
<script language="javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/logbook/plugins/syntaxhighlighter/scripts/shBrushPython.js"></script>

<script language="javascript">
<!--
    window.onload = function() {
        dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/flash/clipboard.swf';
        //this section is important to allow for the correct formatting of BR tags
        dp.SyntaxHighlighter.BloggerMode();

        dp.SyntaxHighlighter.HighlightAll('code');
    }
//-->
</script>

<!--  End Syntaxhighlighter  //-->

<script type="text/javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/tinymce/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo $REX['HTDOCS_PATH']; ?>/files/addons/tinymce/tiny_mce/plugins/media/js/rexembed.js"></script>
<script type="text/javascript">
<!--
tinyMCE.init({
        // General options
        language: 'de',
        mode : 'specific_textareas',
        editor_selector : 'tinyMCEEditor',
        document_base_url : 'http://www.andreas-peters.net//',
        relative_urls : true,		
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,codesyntax",

        // Theme options
        theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,codeformat",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak",

		invalid_elements: "object,applet,iframe",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        extended_valid_elements: "pre[name|class]",
        tab_focus : ":prev,:next",
		theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",
        spellchecker_languages : "+English=en,German=de",
		
        <?php echo $tinyReadOnly; ?>

        // Example content CSS (should be your site CSS)
         content_css : "/files/addons/tinymce/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",
        gecko_spellcheck : true,

});

//-->
</script>

<div class="miscBox" id="metabox">

<form name="saveform" action="" method="post">


<?php
	if ($id) {
		print '<p style="display:hidden;"><input type="hidden" id="parent" name="parent" value="'.$id.'" /></p>';
	}  else {
		$date = date("Y-m-d");
	}

?>

	<p class="formtext formlabel-title">
	  <label class="text" for="date" ><?php echo $logbook->lang->msg('createdate'); ?>:</label>
	  <input readonly="readonly" type="text" class="text" name="createdate" id="createdate" value="<?php echo $date;  ?>" />
	</p>

	<p class="formtext formlabel-title">
	  <label class="text" for="update"><?php echo $logbook->lang->msg('lastupdate'); ?>:</label>
	  <input  readonly="readonly"  type="text" class="text" name="update" id="update" value="<?php echo $content['update'];  ?>" />
	</p>

	<p class="formtext formlabel-title">
 	  <label class="text" for="kw" ><?php echo $logbook->lang->msg('calenderweek'); ?>:</label>
	  <input type="text" class="text" name="kw" id="kw" value="<?php echo $content['kw']; ?>" readonly="readonly" />
	</p>
	
    <p class="formtext formlabel-title">
	  <label class="text" for="scheduledbegin" ><?php echo $logbook->lang->msg('taskbegindate'); ?>:</label>
	  <input <?php echo $readonly;  ?> maxlength="10" type="text" class="text" name="scheduledbegin" id="scheduledbegin" value="<?php echo $content['scheduledbegin'];  ?>" />
	</p>

    <p class="formtext formlabel-title">
	  <label class="text" for="scheduledend" ><?php echo $logbook->lang->msg('taskenddate'); ?>:</label>
	  <input <?php echo $readonly;  ?> maxlength="10" type="text" class="text" name="scheduledend" id="scheduledend" value="<?php echo $content['scheduledend'];  ?>" />
	</p>

	
    <p class="formselect">
      <label class="select " for="client"><?php echo $logbook->lang->msg('client'); ?>:</label>
      <select  <?php echo $readonly;  ?> onchange="javascript:clientChange();" name="client" size="1" id="client" class="select ">
	  <?php echo $logbook->getPullDownClient($content['client_id']); ?>
      </select>
    </p>
  
    <p class="formselect">
      <label class="select " for="cat"><?php echo $logbook->lang->msg('categorie'); ?>:</label>
      <select  <?php echo $readonly;  ?> onchange="javascript:catChange();" name="cat" size="1" id="cat" class="select ">
      <?php echo $logbook->getPullDownCategories($content['cat_id']); ?>
      </select>
    </p>
  
  
    <p class="formtext formlabel-title">
       <label class="checkbox" for="finish" ><?php echo $logbook->lang->msg('finished'); ?>:</label>
       <input type="checkbox" class="checkbox" name="finish" value="1" id="finish" <?php echo $checked; ?>/>
    </p>
  
    <p class="formtext formlabel-title">
      <label class="text" for="subject" ><?php echo $logbook->lang->msg('subject'); ?>:</label>
      <input type="text" class="text" maxlength="255" onchange="javascript:subjectChange();"  name="subject" id="subject" value="<?php echo $content['subject']; ?>"  <?php echo $readonly;  ?> />
    </p>
<?php
  if ($id == "") {
?>
    
    <input type="hidden" name="func" value="save"/>

    <div class="buttons">
      <a href="javascript:submitform('saveform');" title="<?php echo $logbook->lang->msg('save'); ?>"><span icon="save"></span><?php echo $logbook->lang->msg('save'); ?></a>
      <a href="#" id="hidemetabox" title="<?php echo $logbook->lang->msg('close'); ?>"><span icon="close"></span><?php echo $logbook->lang->msg('close'); ?></a>
    </div>

<?php
  } else {
?>

    <input type="hidden" name="func" value="update"/>

    <div class="buttons">
      <a href="javascript:submitform('saveform');" title="<?php echo $logbook->lang->msg('update'); ?>"><span icon="save"></span><?php echo $logbook->lang->msg('update'); ?></a>
      <a href="#" id="hidemetabox" title="<?php echo $logbook->lang->msg('close'); ?>"><span icon="close"></span><?php echo $logbook->lang->msg('close'); ?></a>
    </div>

<?php
  }
?>  
</div>
  

    <div class="buttons" id="metabutton">
<?php
    if ($id) {
?>
      <a href="javascript:submitform('saveform');" title="<?php echo $logbook->lang->msg('update'); ?>"><span icon="save"></span><?php echo $logbook->lang->msg('update'); ?></a>	  
<?php	
    }	
?>	  
      <a href="#" id="viewmetabox" title="<?php echo $logbook->lang->msg('viewmetadata'); ?>"><span icon="meta"></span><?php echo $logbook->lang->msg('viewmetadata'); ?></a>
    </div>    
	
    <p class="formtextarea">  
      <label class="textarea" for="description"><?php echo $logbook->lang->msg('description'); ?>:</label><br/>
  
<?php
    if ($diff_left && $diff_right) {
      print '<div id="diff"><hr/>'.$diffDescription.'<hr/></div>' ;
    } else {
?>    
      <textarea class="tinyMCEEditor" style="z-index:1;width:950px; font-size:11px;"  name="description" id="description" cols="60" onclick="javascript:textChange();" rows="40"><?php echo $content['description']; ?></textarea>
<?php
    }
?>
    </p>
  </form>
<?php
  if ($id) {
    if ($content['parent']) {
?>

<a href="#historybox" id="viewhistory" icon="bullet_arrow_right" title="<?php echo $logbook->lang->msg('vhistory'); ?>"></a> 
<a href="#historybox" id="hidehistory" icon="bullet_arrow_down" title="<?php echo $logbook->lang->msg('vhistory'); ?>"></a> 
<div id="historybox">
  <form name="historyform" action="" method="post">
  <table border="0" cellspacing="0" cellpadding="0" width="100%" id="table" class="tablesorter">
    <thead>
      <tr>
        <th><?php echo $logbook->lang->msg('id'); ?></th>
        <th><?php echo $logbook->lang->msg('parent'); ?></th>
        <th><?php echo $logbook->lang->msg('update'); ?></th>
        <th><?php echo $logbook->lang->msg('description'); ?></th>
        <th><?php echo $logbook->lang->msg('diffleft'); ?></th>
        <th><?php echo $logbook->lang->msg('diffright'); ?></th>
        <th></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th><?php echo $logbook->lang->msg('id'); ?></th>
        <th><?php echo $logbook->lang->msg('parent'); ?></th>
    	<th><?php echo $logbook->lang->msg('update'); ?></th>
    	<th><?php echo $logbook->lang->msg('description'); ?></th>
    	<th><?php echo $logbook->lang->msg('diffleft'); ?></th>
    	<th><?php echo $logbook->lang->msg('diffright'); ?></th>
    	<th></th>
      </tr>
    </tfoot>
    <tbody>

<?php

// get out the history of the current record
while ($content) { 
         
    print "<tr>";
    print "<td>".$content['id']."</td>";
    print "<td>".$content['parent']."</td>";
    print "<td>".$content['update']."</td>";
    print "<td>".$content['subject']."</td>";
    print '<td> <input type="radio" id="diff_left" name="diff_left" value="'.$content['id'].'" /></td>';
    print '<td> <input type="radio" id="diff_right" name="diff_right" value="'.$content['id'].'" /></td>';
    print '<td><a href="?id='.$content['id'].'">'.$logbook->lang->msg('open').'</td>';    
    print "</tr>";


    $content = $logbookRecords->getContentOfRecord($content['parent']);           
}
?>
    </tbody>
  </table>
    <div class="buttons">
      <input type="hidden" name="func" value="diff" class="func" />
      <a href="javascript:submitform('historyform');" title="<?php echo $logbook->lang->msg('diff'); ?>"><span icon="diff"></span><?php echo $logbook->lang->msg('diff'); ?></a>
    </div>
  </form>
</div>
<?php
    }
  } 
?>
</div>
