<?php

      // exportar datos
      require_once('app/Resources/views/easy_admin/CollectionPoint/spyc/spyc.php');

      $data = Spyc::YAMLLoad('app/config/parameters.yml');

      define('SERVIDOR',$data['parameters']['database_host']);
      define('USUARIO', $data['parameters']['database_user']);
      define('PASSWORD',$data['parameters']['database_password']);
      define('BD', $data['parameters']['database_name']);

      $dt_obj = new DateTime(" UTC");
      $dt_obj->setTimezone(new DateTimeZone('America/Bogota'));
      

      $servidor="mysql:dbname=".BD.";host=".SERVIDOR;
      try{
      $pdo = new PDO($servidor,USUARIO,PASSWORD,
            array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8")
      );
      //echo "<script>alert('Conexión con exito a la base de datos');</script>";
      }catch (PDOException $e){
      echo "<script>alert('error al conectar con la base de datos');</script>";
      }


      $d1 = $_POST['d1'];
      $d2 = $_POST['d2'];
      $d3 = $_POST['d3'];
      $d4 = $_POST['d4'];
      $d5 = $_POST['d5'];
      $d6 = $_POST['d6'];
      $d7 = $_POST['d7'];
      $d8 = $_POST['d8'];
      $d9 = $_POST['d9'];
      $d10 = $_POST['d10'];
      $d11 = $_POST['d11'];

      // address google
      $address_concat = $d3.', '.$d6.', '.$d7.', '.$d8;
      $fecha = $formatted_date_long=date_format($dt_obj, 'Y-m-d H:i:s');;
      $estado = "1";



      $sentencia = $pdo->prepare("
            INSERT INTO collection_point ( 
                  name, 
                  description, 
                  address_line1, 
                  address_line2, 
                  created_at, 
                  enabled, 
                  locality, 
                  sub_locality, 
                  administrative_level1, 
                  country, 
                  administrative_level2,
                  point
            ) VALUES(
                  :name,
                  :description,
                  :address_line1,
                  :address_line2,
                  :created_at,
                  :enabled,
                  :locality,
                  :sub_locality,
                  :administrative_level1,
                  :country,
                  :administrative_level2,
                  Point(:longitud, :latitud)
            )");

      $sentencia->bindParam(':name',$d1);
      $sentencia->bindParam(':description',$d2);
      $sentencia->bindParam(':address_line1',$address_concat);
      $sentencia->bindParam(':address_line2',$d4);
      $sentencia->bindParam(':sub_locality',$d5);
      $sentencia->bindParam(':locality',$d6);
      $sentencia->bindParam(':administrative_level2',$d6);
      $sentencia->bindParam(':administrative_level1',$d7);
      $sentencia->bindParam(':country',$d8);
      $sentencia->bindParam(':enabled',$estado);
      $sentencia->bindParam(':created_at',$fecha);
      $sentencia->bindParam(':latitud',$d10);
      $sentencia->bindParam(':longitud',$d11);
      $sentencia->execute();

      //call last id
      $lastId = $pdo->lastInsertId();

      //user load massive
      $editor = 1;
      $sentencia = $pdo->prepare("INSERT INTO collection_point_user
            ( collectionpoint_id, user_id) 
      VALUES(:collectionpoint_id,:user_id)");

      $sentencia->bindParam(':user_id',$editor);
      $sentencia->bindParam(':collectionpoint_id',$lastId);
      $sentencia->execute();

      // Schedule
      $horario = 8824;
      $sentencia = $pdo->prepare("INSERT INTO collection_point_schedule
            ( collectionpoint_id, schedule_id) 
      VALUES(:collectionpoint_id,:schedule_id)");

      $sentencia->bindParam(':schedule_id',$horario);
      $sentencia->bindParam(':collectionpoint_id',$lastId);
      $sentencia->execute();

      //waste
      $residuo = $d9;
      $sentencia = $pdo->prepare("INSERT INTO collection_point_waste
            ( collectionpoint_id, waste_id) 
      VALUES(:collectionpoint_id,:waste_id)");

      $sentencia->bindParam(':waste_id',$residuo);
      $sentencia->bindParam(':collectionpoint_id',$lastId);
      $sentencia->execute();

?>