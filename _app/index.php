<!DOCTYPE html>
<html>
<?php
  if ($_SESSION[_SESSION_USER] == "") echo "<script>document.location='index.php'</script>";
  include_once("./_style/_style.php");
  include_once("./_script/_script.php");
  include_once("./_lib/_lib.php");
  
  include_once("_class/_class.php");
  
  require_once("_head.php");
  require_once("_body.php");
?>
</html>