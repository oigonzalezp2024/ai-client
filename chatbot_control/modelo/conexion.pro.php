<?php
function conexion(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'chatbot';
    $conn = mysqli_connect($host, $user, $password, $database);
    return $conn;
}
