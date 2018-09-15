<?php

function conect(){
    include_once __DIR__ . '/dbinfo.php';
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_errno) {
        echo "Failed connection to MySQL: " . $conn->connect_error;
    }
    return $conn;
}