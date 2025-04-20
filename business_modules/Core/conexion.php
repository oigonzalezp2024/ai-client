<?php
function conexion(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'ai_client';
    $conn = mysqli_connect($host, $user, $password, $database);
    return $conn;
}
?>
