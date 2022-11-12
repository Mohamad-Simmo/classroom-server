<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/Material.php';

  $database = new Database();
  $db = $database->connect();

  $upload_dir = "{$_SERVER['DOCUMENT_ROOT']}/classroom-api/uploads/";
  $server_url = 'http://localhost/';


  try {
    require '../../config/protect.php';

    if ($_FILES['file']) {
      $count = count($_FILES['file']['name']);

      for ($i = 0; $i < $count; $i++) {
        $file_name = $_FILES["file"]["name"][$i];
        $file_tmp_name = $_FILES["file"]["tmp_name"][$i];
        $size = $_FILES["file"]["size"][$i];
        $type = $_FILES["file"]["type"][$i];


        $random_name = rand(1000,1000000)."-".$file_name;
        $upload_name = $upload_dir.strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
        

        if(move_uploaded_file($file_tmp_name , $upload_name)) {
          $upload_name = str_replace("/opt/lampp/htdocs/", "", $upload_name);

          $material = new Material($db);
          $material->section_id = (int)$_POST["section_id"];
          $material->file_name = $file_name;
          $material->url = $server_url.$upload_name;
          $material->size = $size;
          $material->type = $type;
          $material->create();
        }
        else {
          echo "fail";
        }
      }
      echo "success";
    } else {
      
    }
  } catch (Exception $e) {
    echo $e->getMessage();
  }

?>