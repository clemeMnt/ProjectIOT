<?php

$connect = new mysqli("localhost", "smartwine", "raspberry", "smartwine");

if ($connect->connect_errno) {

    echo "Error: Échec d'établir une connexion MySQL, voici pourquoi : \n";
    echo "Errno: " . $connect->connect_errno . "\n";
    echo "Error: " . $connect->connect_error . "\n";
    exit;
}


$req = "SELECT * FROM `wines`";

$res = $connect->query($req);
$data=array();

foreach ($res as $row) {
    $data[] = $row;
}

mysqli_close($connect);

echo json_encode($data);

?>
