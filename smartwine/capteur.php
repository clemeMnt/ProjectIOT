<?php

$mysqli = new mysqli("localhost", "smartwine", "raspberry", "smartwine");

if ($mysqli->connect_errno) {
    echo "Echec de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (isset($_GET["temp"]) && isset($_GET["hygro"])) {
    $stmt = $mysqli->prepare("INSERT INTO smartwine (temp, hygro) VALUES (?, ?);");
    $stmt->bind_param("dd", $_GET["temp"], $_GET["hygro"]);
    $stmt->execute();
    exit();
}

