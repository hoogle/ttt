<?
  session_start();
  require LIBRARY_PATH."mysql_cfg.inc";
  require LIBRARY_PATH."function.inc";

  Connect_Mysql();
  $curr_time = date("Y-m-d H:i:s");
  if (isset($_POST['action']))
  {
    Connect_Mysql();
    switch($_POST['action'])
    {
      case "create":
        $data_point = array(
          "userid" => $_SESSION['userid'], 
          "lat" => $_POST['lat'], 
          "lng" => $_POST['lng'],
          "curr_time" => $curr_time
        );
        insertData("web3.map_point", $data_point, $new_markerid);
        $data_photo = array(
          "userid" => $_SESSION['userid'], 
          "book" => $book,
          "title" => $title,
          "description" => $desc,
          "post_time" => $curr_time,
          "point_id" => $new_markerid
        );
        insertData("web3.photo", $data_photo, $new_photo);
        Close_Mysql();
        echo "{new_markerid:{$new_markerid}}";
        break;
      case "update":
        $sql = "UPDATE web3.map_point SET ";
        $sql.= "lat = '{$_POST['lat']}', lng = '{$_POST['lng']}', curr_time = '{$curr_time}' ";
        $sql.= "WHERE id = '{$_POST['point_id']}'";
        Query_Mysql($sql);
        Close_Mysql();
        break;
    }
  }
?>