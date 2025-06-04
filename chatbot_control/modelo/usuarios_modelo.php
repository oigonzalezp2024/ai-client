<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $fecha_registro = $_POST['fecha_registro'];
    $activo = $_POST['activo'];
    $email = $_POST['email'];

    $sql="INSERT INTO usuarios(
          id_usuario, nombre, celular, fecha_registro, activo, email
          )VALUE(
          '$id', '$nombre', '$celular', '$fecha_registro', '$activo', '$email')";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo mysqli_insert_id($conn);
    }
}

elseif($accion == "modificar"){

    $id = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $fecha_registro = $_POST['fecha_registro'];
    $activo = $_POST['activo'];
    $email = $_POST['email'];

    $sql="UPDATE usuarios SET
          nombre = '$nombre', 
          celular = '$celular', 
          fecha_registro = '$fecha_registro', 
          activo = '$activo', 
          email = '$email'
          WHERE id_usuario = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

elseif($accion == "borrar"){

    $id = $_POST['id_usuario'];

    $sql = "DELETE FROM usuarios
            WHERE id_usuario = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

