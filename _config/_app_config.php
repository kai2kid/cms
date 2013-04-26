<?php
  define('_TITLE', 'STTS - Absensi');
  define('_TITLE_LOGIN', 'STTS - Absensi | Login');
  define('_TITLE_HEADER', 'STTS - Absensi');
  
  // default: all tables in database
  /*/
  $sql = mysqli_query($link,"SHOW tables");
  while ($row = mysqli_fetch_row($sql)) {
    $_tables[$row[0]] = $row[0];
  }
  /*/
  
  //  define your own table for masters (Must be in the same database)
  // $_master[tableName] = Prefix;
//  $_master = $_tables;

  $_master = array(
    "berita" => array(
      "prefix" => "Berita",
      "file" => array(
        array(
          "name" => "cover",
          "prefix" => "Cover",
          "path" => "./news/",
          "accept" => "image/jpeg",
          "filename" => "}berita_id{",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        )
      )
    ),
    "cluster" => array(
      "credential" => "admin_imperial",
      "prefix" => "Cluster",
      "file" => array(
        array(
          "name" => "cover", 
          "prefix" => "Cover",
          "path" => "./cluster/}cluster_id{/",
          "accept" => "image/jpeg",
          "filename" => "utama",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        )
      )    
    ),
    "cluster_sub" => array(
      "credential" => "admin_imperial",
      "prefix" => "Sub Cluster",
      "file" => array(
        array(
          "name" => "facade", 
          "prefix" => "Facade",
          "path" => "./cluster/}cluster_id{/",
          "accept" => "image/jpeg",
          "filename" => "}cluster_sub_id{_facade",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
        array(
          "name" => "floor", 
          "prefix" => "Floor",
          "path" => "./cluster/}cluster_id{/",
          "accept" => "image/jpeg",
          "filename" => "}cluster_sub_id{_floorplan",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        )
      )
    ),
    "imperial_world" => array(
      "credential" => "admin_imperial",
      "prefix" => "Imperial World",
      "file" => array(
        array(
          "name" => "image_1", 
          "prefix" => "Image 1",
          "path" => "./imperialworld/}imperial_world_id{/",
          "accept" => "image/jpeg",
          "filename" => "}imperial_world_id{_1",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
        array(
          "name" => "image_2", 
          "prefix" => "Image 2",
          "path" => "./imperialworld/}imperial_world_id{/",
          "accept" => "image/jpeg",
          "filename" => "}imperial_world_id{_2",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
        array(
          "name" => "image_3", 
          "prefix" => "Image 3",
          "path" => "./imperialworld/}imperial_world_id{/",
          "accept" => "image/jpeg",
          "filename" => "}imperial_world_id{_3",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
        array(
          "name" => "image_4", 
          "prefix" => "Image 4",
          "path" => "./imperialworld/}imperial_world_id{/",
          "accept" => "image/jpeg",
          "filename" => "}imperial_world_id{_4",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
        array(
          "name" => "image_5", 
          "prefix" => "Image 5",
          "path" => "./imperialworld/}imperial_world_id{/",
          "accept" => "image/jpeg",
          "filename" => "}imperial_world_id{_5",
          "fileformat" => ".jpg",
          "maxsize" => "10000"
        ),
      )
    )
  );
  
  //define confiq for each master table
  $_master['berita']['fields'] = array(
    "berita_id" => array("prefix" => "ID"),
    "berita_judul" => array("prefix" => "Judul"),
    "berita_artikel" => array("prefix" => "Artikel"),
    "berita_tanggal" => array("prefix" => "Tanggal")
  );
  $_master['cluster']['fields'] = array(
    "cluster_id" => array("prefix" => "ID"),
    "cluster_nama" => array("prefix" => "Nama"),
    "cluster_keterangan" => array("prefix" => "Keterangan")
  );
  $_master['cluster_sub']['fields'] = array(
    "cluster_sub_id" => array("prefix" => "ID"),
    "cluster_id" => array(
      "prefix" => "Cluster",
      "foreign" => array(
        "table"=>"cluster",
        "key"=>"cluster_id",
        "value"=>"cluster_nama"
      )
    ),
    "cluster_sub_judul" => array("prefix" => "Judul"),
    "cluster_sub_keterangan" => array("prefix" => "Keterangan"),
    "cluster_sub_artikel" => array("prefix" => "Artikel")
  );
  $_master['imperial_world']['fields'] = array(
    "imperial_world_id" => array("prefix" => "ID"),
    "imperial_world_judul" => array("prefix" => "Judul"),
    "imperial_world_keterangan" => array("prefix" => "Keterangan")
  );

  // Setting master menu and onclick function
  foreach ($_master as $table => $value) {
    $_menu['Master'][$value['prefix']]['onclick'] = "menuSelect('a_master','browse','$table')";
    $_menu['Master'][$value['prefix']]['credential'] = $value['credential'];
  }
  $config['tables'] = $_master;
  
  global $config;
  
?>