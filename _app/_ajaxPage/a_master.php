<?php
  extract($_REQUEST, EXTR_OVERWRITE);
  include_once("_ajaxPage.php");
  include_once("../_class/class_kMaster.php");
  $result = 1;
  $s = "";
  $script = "";
  $style = "";
  $p = explode(",",$param);
  $tableName = $p[0];
  $m = new class_kMaster($link,$tableName);
  $m->config($config);
  
  switch ($act) {
    case "browse" : {
      $s .= "<h2>" . strtoupper($m->table['prefix']) . "</h2>";
      $s .= "<br>";
      $s .= "<input type=button value=\"Insert new data\" onclick=\"menuSelect('a_master','insert','$tableName')\">";
      $s .= "<br><br>";
      $s .= "<hr>";
      $s .= $m->drawTable_browse();

      $script = "$('#table_browse_".$m->table['name']."').dataTable();";
//      $script = "
//        $('#table_browse_".$m->table['name']."').dataTable({
//          'aoColumnDefs': [{ 'bSearchable': false, 'aTargets': [ 0 ] }],
//          'aoColumnDefs': [{ 'bSortable': false, 'aTargets': [ 0 ] }] 
//        });";
      break; }
    case "insert" : {
      $s .= "<h2>INSERT NEW DATA FOR " . strtoupper($m->table['prefix']) . "</h2>";
      $s .= "<iframe id=iframe name=iframe class=callback></iframe>";
      $s .= "<form id='form_master' target=iframe method=post action='./_app/_callback/c_master.php'>";
      $s .= "<input type=hidden name='act' value='inserting'>";
      $s .= "<input type=hidden name='tableName' value='$tableName'>";
      $s .= $m->drawForm_insert();
//      $s .= "<hr>";
      $s .= "<input type=submit value=\"Submit\">";
      $s .= "</form>";
      if ($m->table['file'] != "") {
        foreach($m->table['file'] as $file) {
          $s .= "<hr>";
          $s .= "<h2>Upload ".$file['prefix']." Image</h2>";
          $s .= "<iframe id='iframe_".$file['name']."' name='iframe_".$file['name']."' class=callback></iframe>";
          $s .= "<img src='' id='img_".$file['name']."' width=200px; style='display:none;'>";
          $s .= "<form id='form_".$file['name']."' name='form_".$file['name']."' enctype='multipart/form-data' target='iframe_".$file['name']."' method=post action='./_app/_callback/c_master.php'>";
            $s .= "<input type=hidden name='act' value='upload_image'>";
            $s .= "<input type=hidden name='tableName' value='$tableName'>";
            $s .= "<input type=hidden name='".$m->table['PK']."' value='".$m->table['auto_increment']."'>";
            $s .= "<input type='file' id='".$file['name']."' name='".$file['name']."' value='$value' style=\"$style\" accept=\"$accept\" />";
            $s .= "<br>";
            $s .= "<input type=submit value=\"Submit\">";
          $s .= "</form>";
        }
      }
      $s .= "<hr>";
      $s .= "<hr>";
      $s .= "<input type=button value=\"<< Back\" onclick=\"menuSelect('a_master','browse','$tableName')\">";
      break; }
    case "update" : {
      $id = $p[1];
      $s .= "<h2>UPDATE " . strtoupper($m->table['prefix']) . "</h2>";
      $s .= "<iframe id=iframe name=iframe class=callback></iframe>";
      $s .= "<form id='form_master' target=iframe method=post action='./_app/_callback/c_master.php'>";
      $s .= "<input type=hidden name='act' value='updating'>";
      $s .= "<input type=hidden name='tableName' value='$tableName'>";
      $s .= $m->drawForm_update($id);
      $s .= "<input type=submit value=\"Submit\">";
      $s .= "</form>";
      if ($m->table['file'] != "") {
        foreach($m->table['file'] as $file) {
          $s .= "<hr>";
            $s .= "<h2>Upload ".$file['prefix']." Image</h2>";
          $s .= "<iframe id='iframe_".$file['name']."' name='iframe_".$file['name']."' class=callback></iframe>";
          $filepath = $file['path'].$file['filename'].$file['fileformat'];
          $filepath = $m->traceFilePath($filepath,$id);        
          if (file_exists("../.".$filepath)) {
            $imgsrc = $filepath;
            $imgstyle = "";
          } else {
            $imgsrc = "";
            $imgstyle = "display:none;";
          }
          $s .= "<img src='$imgsrc' id='img_".$file['name']."' width=200px; style='$imgstyle'>";
          $s .= "<form id='form_".$file['name']."' name='form_".$file['name']."' enctype='multipart/form-data' target='iframe_".$file['name']."' method=post action='./_app/_callback/c_master.php'>";
            $s .= "<input type=hidden name='act' value='upload_image'>";
            $s .= "<input type=hidden name='tableName' value='$tableName'>";
            $s .= "<input type=hidden name='".$m->table['PK']."' value='$id'>";
            $s .= "<input type='file' id='".$file['name']."' name='".$file['name']."' value='$value' style=\"$style\" accept=\"$accept\" />";
            $s .= "<br>";
            $s .= "<input type=submit value=\"Submit\">";
          $s .= "</form>";
        }
      }
      $s .= "<hr>";
      $s .= "<hr>";
      $s .= "<input type=button value=\"<< Back\" onclick=\"menuSelect('a_master','detail','$tableName,$id');\">";
      break; }
    case "detail" : {
      $id = $p[1];
      $s .= "<h2>DETAIL " . strtoupper($m->table['prefix']) . "</h2>";
      $s .= "<iframe id=iframe name=iframe class=callback></iframe>";
      $s .= "<form id='form_master' target=iframe method=post action='./_app/_callback/c_master.php'>";
      $s .= "<input type=hidden name='act' value='deleting'>";
      $s .= "<input type=hidden name='tableName' value='$tableName'>";
      $s .= $m->drawForm_detail($id);
      if ($m->table['file'] != "") {
        foreach($m->table['file'] as $file) {
          $filepath = $file['path'].$file['filename'].$file['fileformat'];
          $filepath = $m->traceFilePath($filepath,$id);        
          if (file_exists("../.".$filepath)) {
            $s .= "<h2>".$file['prefix']." Image</h2>";
            $s .= "<img src='$filepath' id='img_".$file['name']."' width=200px; style='$style'>";
            $s .= "<br>";
          }
        }
      }
//      $s .= "<hr>";
      $s .= "<input type=button value=\"Update\" onclick=\"menuSelect('a_master','update','$tableName,$id');\">";
      $s .= " &nbsp; &nbsp; ";
      $s .= "<input type=submit value=\"Delete\" onclick=\"confirm('Are you sure want to delete this data?');\">";
      $s .= "</form>";
      $s .= "<hr>";
      $s .= "<hr>";
      $s .= "<input type=button value=\"<< Back\" onclick=\"menuSelect('a_master','browse','$tableName');\">";
      break; }
    default : { $result = 0; }
  }
  $script .= "$('input[type=date]').datepicker();";
  $script .= "tinyMCE.init({mode : 'textareas',theme : 'simple'});";
  
//  $script = "";
      
  $o['script'] = $script;  
  $o['style'] = $style;  
  $o['html'] = $s;  
  $o['result'] = $result;
  
  // return
  header('Content-type: application/x-json');
  echo json_encode($o);
?>