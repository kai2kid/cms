<?php
  include_once("_config/_config.php");
  if ($_REQUEST['act'] == 'logout') $_SESSION[_SESSION_USER] = "";
  if ($_SESSION[_SESSION_USER] == "") {
    require_once("_login/index.php");
  } else {
    require_once("_app/index.php");
  }
?>