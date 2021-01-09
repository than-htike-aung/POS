<?php
try{

    $pdo = new PDO('mysql:host=localhost;dbname=pos_db','root','');
  //  echo 'Connection successful';

}catch(PDOException $e){
    echo $e->getMessage();
}

?>