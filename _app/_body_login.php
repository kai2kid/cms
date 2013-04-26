<body>
<?php  
  require_once("_header.php");
?>
  <div id='wrapper_body'>
    <div id='div_login'>
      <form method=post>
        <table align=center style='color:darkblue'>
          <tr>
            <td align=left>ID</td>
            <td>:</td>
            <td><input type='text' name=kemhsan_user></td>
          </tr>
          <tr>
            <td align=left>Password</td>
            <td>:</td>
            <td><input type='password' name=kemhsan_pass></td>
          </tr>
          <tr>
            <td align=center colspan=3><input type=submit></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  
<?php  
  require_once("_footer.php");                                                   
?>
</body>
