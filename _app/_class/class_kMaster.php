<?php
//Last Update : 2012 - 11 - 05
//Created by: KaI2KId

include_once("class_kTable.php");
class class_kMaster extends class_kTable {
  public $link = "";
  protected $data;
  public $table;
  public $fields;

  public function __construct($link,$tableName="") {
    $this->link = $link;
    if ($tableName != "") { $this->tableName($tableName); }
  }  
  public function tableName($name = "") {
    if ($name == "") {
      return $this->table['name'];
    } else {
      $this->table['name'] = $name;
      $this->table['prefix'] = $name;
      $this->table['PK'] = $this->getTablePK();    
      $this->table['auto_increment'] = $this->getTableStatus('Auto_increment');
      $this->fields("*");
    }
  }
  private function getTablePK() {    
    $row = mysqli_fetch_assoc(mysqli_query($this->link,"SHOW KEYS FROM " . $this->table['name'] . " WHERE Key_name = 'PRIMARY'"));
    $ret = $row['Column_name'];
    if ($ret == "") {
      $row = mysqli_fetch_fields(mysqli_query($this->link,"SELECT * FROM " . $this->table['name'] . " LIMIT 1"));
      $ret = $row[0]->orgname;
    }
    return $ret;
  }
  function getTableStatus($param="") {
    $ret = $this->query("SHOW TABLE STATUS LIKE '".$this->table['name']."'");
    $ret = $ret[0];
    if ($param != "") $ret = $ret[$param];
    return $ret;
  }
  public function query($sql = '') {
    $result = mysqli_query($this->link, $sql) or die(mysqli_error($this->link) . ': ' . $sql);
    if ($result) {
      $return = array();
      while ($row = mysqli_fetch_assoc($result))
        $return[] = $row;
      return $return;
    }
    return $result;
  }
  public function query_one($sql = '') {
    $result = mysqli_query($this->link, $sql.' LIMIT 1') or die(mysqli_error($this->link) . ': ' . $sql);
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      return $row;
    }
    return $result;
  }
  public function insert($table = '', $vars = array()) {
    $sql = 'INSERT INTO ' . $table . ' ';
    $keys = array_keys($vars);
    $vals = array_values($vars);
    foreach ($vals as &$val)
      $val = $this->escape_and_quote($val);
      $sql .= '(' . implode(',',$keys) . ') VALUES (' . implode(',',$vals) . ')';
    return $this->query_exec($sql);
  }
  public function update($table = '', $vars = array(), $where = '1=1') {
    $sql = 'UPDATE ' . $table . ' SET ';
    $set = array();
    foreach($vars as $key => $val)
      $set[] = ' ' . $key . '=' . $this->escape_and_quote($val) ;
    $sql .= implode(',',$set) . ' WHERE ' . $where;
    return $this->query_exec($sql);
  }
  public function delete($table, $where) {
    $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
    return $this->query_exec($sql);
  }
  public function rowCount($table = '', $where = '1=1') {
    $sql  = 'SELECT * FROM ' . $table . ' WHERE ' . $where;
    $row = mysqli_query($this->link, $sql) or die(mysqli_error($this->link) . ': ' . $sql);
    $ret = mysqli_num_rows($row);
    return $ret;
  }
  public function select($fields = "*",$where="") {
    if ($fields != "*") $fields = implode(", ",$fields);
    $ret = $this->query("SELECT $fields FROM " . $this->table['name']." ".$where);
    return $ret;
  }
  public function quickSelect($term = "all") {
    switch ($term[0]) {
      case 'PK' : { $where[] = $this->table['PK']."="."'".$term[1]."'"; break; }
      default : { $where[] = '1=1'; break; }
    }
    $from[] = $this->table['name'];
    $ctr = 1;
    foreach($this->fields as $field => $value) {
      if ($value['foreign'] != "") {
        $pr = $value['foreign']['table'].$ctr;
        $fields[] = "(SELECT $pr.".$value['foreign']['value']." FROM ".$value['foreign']['table']." $pr WHERE ".$this->table['name'].".".$field." = $pr.".$value['foreign']['key'].") as " . $field;
      } else {
        $fields[] = $this->table['name'].".".$field;
      }
    }
    $fields = implode(",",$fields);
    $from = implode(",",$from);
    $where = implode(" AND ",$where);
    
    $qry = "SELECT ".$fields." FROM " . $from." WHERE ".$where . " ";
    $ret = $this->query($qry);
//    if (count($ret) == 1) $ret = $ret[0];
    return $ret;
  }
  public function config($config) {
    if ($config != "") {
      foreach ($config["tables"][$this->table['name']] as $t_prop => $t_prop_val) {
        switch ($t_prop) {
          case "fields" : {          
            $this->fields(implode(",",array_keys($t_prop_val)));
            foreach ($t_prop_val as $f_name => $f_prop) {
              foreach($f_prop as $f_prop_name => $f_prop_val) {
                $this->fields[$f_name][$f_prop_name] = $f_prop_val;
              }
            }
            break; }
          default : {
            $this->table[$t_prop] = $t_prop_val;
          }
        }
      }      
    }
  }  
  public function fields($fields = "") {
    if ($fields == "") {
      foreach($this->fields as $value) {
        $ret[] = $value['name'];
      }
      return implode(",",$ret);
    } else {
      $this->fields = array();
      $row = $this->query("SHOW FIELDS FROM " . $this->table['name']);
      foreach($row as $data) {
        $temp[$data['Field']] = $data;
      }
      $row = mysqli_fetch_fields(mysqli_query($this->link,"SELECT $fields FROM " . $this->table['name'] . " LIMIT 1"));
      
      foreach($row as $data) {
        $name = $data->orgname;
        $tmp = explode("(",$temp[$name]['Type']);
        $type = $tmp[0];
        $this->fields[$name]['name'] = $name;
        $this->fields[$name]['prefix'] = $name;
        $this->fields[$name]['table'] = $data->orgtable;
        $this->fields[$name]['max_length'] = $data->max_length;
        $this->fields[$name]['length'] = $data->length;
        $this->fields[$name]['charsetnr'] = $data->charsetnr;
        $this->fields[$name]['flags'] = $data->flags;
        $this->fields[$name]['type'] = $type;
        $this->fields[$name]['decimals'] = $data->decimals;
        $this->fields[$name]['null'] = $temp[$name]['Null'];
        $this->fields[$name]['key'] = $temp[$name]['Key'];
        $this->fields[$name]['default'] = $temp[$name]['Default'];
        $this->fields[$name]['extra'] = $temp[$name]['Extra'];
      }
    }
  }  
  public function query_list($sql = '') { // returns one dimension array
    $result = $this->query($sql);
    if (count($result) == 0)
      return array();
    $return = array();
    if (count($result[0]) == 1) {
      foreach ($result as $row)
        $return[] = current($row);
      return $return;
    } else {
      foreach ($result as $row)
        $return[current($row)] = next($row);
      return $return;
    }
    return array();
  }
  private function query_exec($sql = '') {
    $result = mysqli_query($this->link, $sql);
    if ($result === false)
      debug(mysqli_error($this->link) . ': ' . $sql);
    return $result;
  }
  public function escape_and_quote($val) {
    if (is_string($val))
      return '\'' . mysqli_real_escape_string($this->link, $val) . '\'';
    else if (is_array($val) && count($val) == 1)
      return current($val);
    return $val;
  }
  
  public function drawComboBox($comboName,$fieldShown,$fieldValue,$defaultValue="",$sortBy="",$sort="asc") {
    if ($sort != "desc") $sort = "asc";
    if ($sortBy == "") $sortBy = $fieldShown;

    $o = "<option value=''></option>";
    
    $sql  = "SELECT ".$fieldShown." text, $fieldValue value FROM " . $this->table['name'] . " ORDER BY " . $sortBy . " " . $sort;
    
    $row = $this->query($sql);
    
    foreach ($row as $r) {
      if ($defaultValue == $r['value']) $sel = 'selected'; else $sel = "";
      $o .= "<option value='".$r['value']."' ".$sel.">".$r['text']."</option>";
    }
    
    $ret = "";
    $ret .= "<select id='".$comboName."' name='".$comboName."'>";
      $ret .= $o;
    $ret .= "</select>";
    
    return $ret;
  }
  
  public function nextID ($fieldName, $firstPrefix = "") {
    if ($firstPrefix != "") {
      $sql = "SELECT 
        CONCAT('$firstPrefix',
          LPAD(
            SUBSTR(
              max($fieldName),LENGTH('$firstPrefix')+1
            )+1,
            LENGTH($fieldName)-LENGTH('$firstPrefix'),
            '0'
          )
        ) id ";
      if ($firstPrefix == "UKM01SENMA") $firstPrefix .= "0";
      $sql .= "FROM " . $this->table['name'] . " WHERE $fieldName LIKE '".$firstPrefix."%'";
      $row = $this->query_one($sql);
      if ($row['id'] != "") {
        $ret = $row['id'];
      } else {
        $ret = $firstPrefix . "0001";
      }
    } else {
      $sql = "SELECT LPAD(max($fieldName)+1,LENGTH($fieldName),'0') id FROM " . $this->table['name'];
      $row = $this->query_one($sql);
      if ($row['id'] != "") {
        $ret = $row['id'];
      } else {
        $ret = "1";
      }
    }
    return $ret;
  }  
  public function createInput($fieldName) {
    $name = "input_" . $fieldName;
    $ret = "";
    $ret .= "<input type=text id='$name' name='$name'>";
    return $ret;
  }
  public function receive($request) {
    
  }
  public function drawForm_detail($id) {
    $row = $this->quickSelect(array("PK",$id));
    $row = $row[0];
    $s = "";
    $s .= "<input type=hidden value='$id' name='".$this->table['PK']."' />";
    $s .= "<table cellpadding=5 cellspacing=2>";
      $s .= "<caption><caption>";
      $s .= "<thead>";
      $s .= "</thead>";
      $s .= "<tbody>";
      foreach($this->fields as $field=>$prop) {
        $value = $row[$field];
        $s .= "<tr valign=top>";
          $s .= "<td>".$prop['prefix']."</td>";
          $s .= "<td>:</td>";
          $s .= "<td>".$value."</td>";
        $s .= "</tr>";
      }
      $s .= "</tbody>";
      $s .= "<tfoot>";
      $s .= "</tfoot>";
    $s .= "</table>";
    return $s;
  }
  public function drawForm_insert() {
    $s = "";
    $s .= "<table cellpadding=5 cellspacing=2>";
      $s .= "<caption><caption>";
      $s .= "<thead>";
      $s .= "</thead>";
      $s .= "<tbody>";
      foreach($this->fields as $key=>$value) {
        $s .= "<tr valign=top>";
          $s .= "<td>".$value['prefix']."</td>";
          $s .= "<td>:</td>";
          $param = $this->fields[$key];
          $s .= "<td>".$this->drawInput($param)."</td>";
        $s .= "</tr>";
      }
      $s .= "</tbody>";
      $s .= "<tfoot>";
      $s .= "</tfoot>";
    $s .= "</table>";
    return $s;
  }
  public function drawForm_update($id) {
    $qry = "SELECT " . $this->fields() . " FROM " . $this->table['name'] . " WHERE " . $this->table['PK'] . " = '". $id ."'";
    $row = $this->query_one($qry);
    $s = "";
    $s .= "<table cellpadding=5 cellspacing=2>";
      $s .= "<caption><caption>";
      $s .= "<thead>";
      $s .= "</thead>";
      $s .= "<tbody>";
      foreach($this->fields as $key=>$value) {
        $param = $this->fields[$key];
        if ($key == $this->table['PK']) {
          $param['type'] = 'hidden';
          $param['display'] = 'show';
        } else {
          $param['type'] = $value['type'];
        }
        $param['value'] = $row[$key];
        
        $s .= "<tr valign=top>";
          $s .= "<td>".$value['prefix']."</td>";
          $s .= "<td>:</td>";
          $s .= "<td>".$this->drawInput($param)."</td>";
        $s .= "</tr>";
      }      
      $s .= "</tbody>";
      $s .= "<tfoot>";
      $s .= "</tfoot>";
    $s .= "</table>";
    return $s;
  }
  public function drawTable_browse() {
    $s = "";
    $s .= "<table id='table_browse_".$this->table['name']."' class='tableBasic' border=1 cellpadding=5 cellspacing=5>";
      $s .= "<thead>";
        $s .= "<tr>";
        foreach ($this->fields as $data) {
          $s .= "<th>".$data['prefix']."</th>";
        }
        $s .= "</tr>";
      $s .= "</thead>";

      $s .= "<tbody>";
      $row = $this->quickSelect();
      foreach ($row as $key => $data) {
        $s .= "<tr class='tr_selectable' onclick=\"menuSelect('a_master','detail','".$this->table['name']."," . $data[$this->table['PK']] . "');\">";
        foreach($this->fields as $field=>$prop) {
            $value = $data[$field];
          $value0=$value;
          if (strlen($value) > 50) $value=substr(trim($value),0,50)."...";
          $s .= "<td title=\"$value0\">".$value." </td>";
        }
        $s .= "</tr>";
      }
      $s .= "</tbody>";

      $s .= "<tfoot>";
      $s .= "</tfoot>";
    $s .= "</table>";
    return $s;
  }
  public function drawForm_file() {
    if ($this->table['file'] != "") {
      $s = "";
      $s .= "<table cellpadding=5 cellspacing=2>";
        $s .= "<caption><caption>";
        $s .= "<thead>";
        $s .= "</thead>";
        $s .= "<tbody>";
          foreach ($this->table['file'] as $file) {
            $s .= "<tr>";
              $s .= "<td>".$file['prefix']."</td>";
              $s .= "<td>:</td>";
              $param['type'] = "file";
              $param['name'] = $file['name'];
              $param['accept'] = $file['accept'];
              $s .= "<td>".$this->drawInput($param)."</td>";
            $s .= "</tr>";          
          }
        $s .= "</tbody>";
        $s .= "<tfoot>";
        $s .= "</tfoot>";
      $s .= "</table>";
      return $s;
      
    }
  }
  public function drawInput($param="") {
    extract($param);
    //$param: id / name, value, style
    if ($id == "") $id = $param['name'];
    if ($name == "") $name = $param['id'];
    if ($foreign != "") {
      $f = new class_kTable($this->link,$foreign['table']);
      $s .= $f->drawComboBox($name,$foreign['value'],$foreign['key'],$value,$foreign['key']);
    } else {
      if ($key == "PRI" && $extra == "auto_increment") {
        $type='hidden'; $display='show'; 
        if ($value == "") { $value=$this->table['auto_increment']; }
      }
      if ($type == "date" && $value == "") $value = date("Y-m-d");
      switch($type) {
        case "date" : { $s .= "<input type='date' id='$name' name='$name' value='$value' style=\"$style\" maxlength=\"$length\" />"; break; }
        case "int" : { $s .= "<input type='number' id='$name' name='$name' value='$value' style=\"$style\" maxlength=\"$length\" />"; break; }
        case "file" : { $s .= "<input type='file' id='$name' name='$name' value='$value' style=\"$style\" accept=\"$accept\" />"; break; }
        case "text" : { $s .= "<textarea id='$name' name='$name' style=\"$style\" cols=\"50\">$value</textarea>"; break; }
        case "hidden" : { $s .= "<input type='hidden' id='$name' name='$name' value='$value' />"; if ($display == 'show') $s .= $value; break; }
        default : { $s .= "<input type='text' id='$name' name='$name' value='$value' style=\"$style\" maxlength=\"$length\" />"; break; }
      }
    }
    return $s;
  }  
  
  public function inserting($vars) {
    return $this->insert($this->table['name'],$vars);
  }
  public function updating($vars,$id) {
    return $this->update($this->table['name'],$vars,$this->table['PK'] . " = '$id'");
  }
  public function deleting($id) {
    return $this->delete($this->table['name'],$this->table['PK'] . " = '$id'");
  }
  public function uploadFile($file,$param) {
    extract($param);
    //path
    $base_dir = "../.";
    $upload_to = $base_dir.$path.$filename.$fileformat;
    $upload_to = $this->traceFilePath($upload_to,$id);
    $this->fixFilePath($upload_to);
//    echo "<br>" . $upload_to;
//    $maxsizeinkb = intval($maxsize/1024);
    
//  foreach ($file["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
      $tmp_name = $file["tmp_name"][$key];
//          $name = $file["tmp_name"]["name"][$key];
//          move_uploaded_file($tmp_name, "$uploads_dir/$name");
          
      }
      move_uploaded_file($file["tmp_name"], $upload_to);
//  }
  //function fiestoupload($fieldname,$destdir,$destfile,$maxsize,$allowedtypes="gif,jpg,jpeg,png,bmp") {
  /*
  $fieldname : field name di form
  $destdir : direktori tujuan
  $destfile : nama file (minus extension, which is always the same as uploaded)
  $maxsize : ukuran maksimum dalam byte (harus konsisten dengan MAX_FILE_SIZE di html)
  $lang : (optional) bahasa. default="id".
  $allowedtypes : (optional) jenis extension yang diizinkan, dipisahkan tanda koma. default = "gif,jpg,jpeg,png".
  */    
/*/   
    if ($_FILES[$fieldname]['name'] != '') {
      $maxsizeinkb = intval($maxsize/1024);

    //Filter 1: cek apakah file terupload dengan benar
      switch ($_FILES[$name]['error']) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
          //file too big
          return _FILETOOBIG." $maxsizeinkb kbytes.";
          break;
        case UPLOAD_ERR_PARTIAL:
          return _FILEPARTIAL;
          break;
        case UPLOAD_ERR_NO_FILE:
          return _FILEERROR1;
          break;
      }
       
      //filesize
      if ($_FILES[$name]['size'] > $maxsize) {
        return _FILETOOBIG." $maxsizeinkb kbytes.";
      }
      
      //extension
      $rallowedtypes = split(",",$allowedtypes);
      $temp = split("\.",$_FILES[$fieldname]['name']);
      $extension = $temp[count($temp)-1];

      $isallowed = false;
      $extension=strtolower($extension);
      foreach ($rallowedtypes as $allowedtype) {
        if ($extension == $allowedtype) $isallowed = true;
      }        
      if (!$isallowed) {
        return _ALLOWEDTYPE." $allowedtypes.";
      }
                 
    //Filter 4: cek apakah benar-benar file gambar (hanya jika $allowedtypes="gif,jpg,jpeg,png")
    //Tidak cek MIME-type karena barubah-ubah terus
    //Tidak cek extension karena nanti dipaksa berubah
    //Cek dilakukan sebelum dipindah ke destination dir (masih di temp)

      if ($extension=="gif" || $extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="bmp") {
        $size = getimagesize($_FILES[$fieldname]['tmp_name']);
        if ($size==FALSE) {
          return _ALLOWEDTYPE." $allowedtypes.";
        }
      }

    //Filter 5: Jalankan
      $thelastdestination = "$destdir/$destfile.jpg";
      if (!move_uploaded_file($_FILES[$name]['tmp_name'],$destination)) {
        return _MAYBEPERMISSION;
      }
      return "Sukses";
    } else{
      return _FILEPARTIAL;
    }
  }
/*/
     return $upload_to;
  }
  public function traceFilePath($path,$PK_id) {
    $path = explode("/",$path);
    $ret = "";
    foreach($path as $p) {
      $tmp = $p;
      if (strpos($p,"{") >= 0 && strpos($p,"}") >= 0) {
        $pos_0 = strpos($p,"}");
        $pos_1 = strpos($p,"{");
        if ($pos_0 < $pos_1) {
          $prefix = substr($p,$pos_0+1,($pos_1-$pos_0-1));
          if ($prefix == $this->table['PK']) {
            $tmp = str_replace("}".$prefix."{",$PK_id,$p);
          } else {
            $row = $this->query_one("SELECT * FROM ".$this->table['name']." WHERE " . $this->table['PK'] . " = '" . $PK_id ."'");
            $tmp = str_replace("}".$prefix."{",$row[$prefix],$p);
          }
        }
      }
      $ret[] = $tmp;
    }
    $ret = implode("/",$ret);
    return $ret;
  }
  public function fixFilePath($path) {
    $tmp = explode("/",$path);
    $dir = ".";
    foreach ($tmp as $p) {
      chmod($dir,0777);
      $dir .= "/".$p;
      $t = explode(".",$p);
      if (count($t) <= 0 || strlen($t[count($t)-1]) != 3) {
        if (!file_exists($dir)) {
          mkdir($dir,0777);
//          echo "<script>alert('".$dir."');</script>";
        }
        chmod($dir,0777);
      }
    }
    
  }
}
?>