<div id='wrapper_menu'>  
    <?php
      $s = "";
      $s .= "<div style='text-align:center;font-weight:bold'><a href='./' />[ " . $_SESSION[_SESSION_USER] . " ]</a></div>";
      $s .= "<hr>";
      $s .= "<br>";
      foreach($_menu as $key => $value) {
        if (is_array($value)) {
          $s2 = "";
          foreach ($value as $key1 => $value1) {
            $tmp = explode(",",$value1['credential']);
            $allow = true;
            if (!isset($value1['credential']) || $value1['credential'] == "" || $allow) {
              $s2 .= "<li onclick=\"".$value1['onclick'].";\">";
                $s2 .= $key1;
              $s2 .= "</li>";            
            }
          }
        }
        if ($s2 != "") {
          $s .= "<ul>";
            $s .= $key;
            $s .= $s2;
          $s .= "</ul>";
          $s .= "<br>";
        }
      }
      $s .= "<hr>";
      $s .= "<div style='text-align:center;font-weight:bold'><a href='./index.php?act=logout' />[ LOGOUT ]</a></div>";
      echo $s;
    ?>  
</div>
