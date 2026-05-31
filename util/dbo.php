<?php

function createDBInstance() {
    $DB_HOST = $_ENV["DB_HOST"];
    $DB_USER = $_ENV["DB_USERNAME"];
    $DB_PASSWORD = $_ENV["DB_PASSWORD"];
    $DB_NAME = $_ENV["DB_DATABASE"];

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    if($conn->connect_errno){
        throw new Exception('MYSQL_CONNECT_ERR');
    }

    return $conn;
}


?>