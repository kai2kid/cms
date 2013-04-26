<?php
  include_once("./_login/class_login.php");
  extract($_REQUEST);
  $_SESSION[_SESSION_USER] = "";
  switch($act) {
    case 'login' : {
      $login = new class_login();
      $err = 1;
      if ($login_username == 'admin' && $login_password == 'admin') {
        $_SESSION[_SESSION_USER] = $login_username;
        $err = 0;          
      }          
      break; }
  }
  if ($_SESSION[_SESSION_USER] != "" && $err == 0) echo "<script>document.location='index.php';</script>";
  $err_msg = "";
  if ($err == 1) {
    $err_msg = "<div class=notif_red> <h3>Username / Password salah</h3></div>";
  }    
?>
<html>
  <head>
    <title><? echo _TITLE_LOGIN; ?></title>
  </head>
  <link type="text/css" rel="stylesheet" href="./_style/style_login.css" />
  <body>
    <div class='wrapper_login'>
      <form method='POST'>
        <input type="hidden" name='act' value="login" />
        <table class='tableLogin' cellpadding=5 cellspacing=2>
          <tr>
            <td> Username : </td>
            <td> <input type='text' name='login_username' id='login_username' required placeholder="USERNAME" autofocus autocomplete="off" /> </td>
          </tr>
          <tr>
            <td> Password : </td>
            <td> <input type='password' name='login_password' id='login_password' required placeholder="PASSWORD" /> </td>
          </tr>
          <tr>
            <td align=center colspan=2> <input type='submit' value='Login'> </td>
          </tr>
        </table>
      </form>
    </div>
    <?php echo $err_msg; ?>
  </body>
</html>