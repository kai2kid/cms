<?php
  extract($_REQUEST);
  include_once("_callback.php");
  include_once("../_class/class_kMaster.php");
  $script = "";
  
//  $temp=explode("&",$param);
//  foreach ($temp as $t) {
//    $tmp = explode("=",$t);
//    $post[$tmp[0]] = $tmp[1];
//  }
//  extract($post);
  $m = new class_kMaster($link,$tableName);  
  $m->config($config);  
  $result = 1;
  switch ($act) {
    case "inserting" : {
      $keys = array_keys($m->fields);
      foreach ($keys as $k) {
        if ($m->fields[$k]['type'] != "text") {
          $insert[$k] = htmlentities($_REQUEST[$k]);
        } else {
          $insert[$k] = $_REQUEST[$k];
        }
      }
      $m->inserting($insert);
      
      $script[] = "alert('Data successfully inserted!');";
      $script[] = "menuSelect('a_master','insert','$tableName');";
      break; }
    case "updating" : {
      $keys = array_keys($m->fields);
      foreach ($keys as $k) {
        if ($k == $m->table['PK']) {
          $id = $_REQUEST[$k];
        } else {
          if ($m->fields[$k]['type'] != "text") {
            $update[$k] = htmlentities($_REQUEST[$k]);
          } else {
            $update[$k] = $_REQUEST[$k];
          }
        }
        
      }            
      $m->updating($update,$id);
      $script[] = "alert('Data successfully updated!');";
      $script[] = "menuSelect('a_master','detail','$tableName,$id');";
      break; }
    case "deleting" : {
      $m->deleting($_REQUEST[$m->table['PK']]);
      $script[] = "alert('Data has been deleted!');";
      $script[] = "menuSelect('a_master','browse','$tableName');";
      break; }
    case "upload_image" : {
      $id = $_REQUEST[$m->table['PK']];
      if ($m->table['file'] != "") {
        foreach ($m->table['file'] as $file) {
          if ($_FILES[$file['name']] != "") {
          $param = $file;
          $param['id'] = $id;
          $path = $m->uploadFile($_FILES[$file['name']],$param);
          $path = str_replace("../","",$path);
          $script[] = "alert('".$file['prefix']." image uploaded!');";
          $script[] = "showUploadedImage(\"img_".$file['name']."\",\"$path\");";
          }
        }
      }
            
    }
    default : { $result = 0; }
  }
  
//  $script = "";
//  $o['script'] = $script;  
//  $o['style'] = $style;  
//  $o['html'] = $s;  
//  $o['result'] = $result;
  
  $s = "";
  if ($script != "") {
    $s .= "<script>";
    foreach ($script as $scr) {
      $s .= "window.top.".$scr;
    }
    $s .= "</script>";
  }
  echo $s;
  // return
//  header('Content-type: application/x-json');
//  echo json_encode($o);
?>