<?php
require_once "connectdb.php";

$id = $_GET["id"];

$select = $pdo->prepare("SELECT * FROM tbl_product WHERE pid = :ppid");
$select->bindParam(":ppid", $id);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$response = $row;

header('Content-Type: application/json');

echo json_encode($response);



?>