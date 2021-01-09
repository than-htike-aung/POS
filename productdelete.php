<?php
require_once "connectdb.php";

if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){
    header('location:index.php');
  }

$id = $_POST['pidd'];
$sql = "DELETE FROM tbl_product WHERE pid = $id";
$delete = $pdo->prepare($sql);

if($delete->execute()){

}else{
    echo "Error in Deleting";
}

?>