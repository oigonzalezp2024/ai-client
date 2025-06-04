<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id = $_POST['id_persona'];
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $activo = $_POST['activo'];

    $sql="INSERT INTO personas(
          id_persona, nombre, celular, activo
          )VALUE(
          '$id', '$nombre', '$celular', '$activo')";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo mysqli_insert_id($conn);
    }
}

elseif($accion == "modificar"){

    $id = $_POST['id_persona'];
    $nombre = $_POST['nombre'];
    $celular = $_POST['celular'];
    $activo = $_POST['activo'];

    $sql="UPDATE personas SET
          nombre = '$nombre', 
          celular = '$celular', 
          activo = '$activo'
          WHERE id_persona = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

elseif($accion == "borrar"){

    $id = $_POST['id_persona'];

    $sql = "DELETE FROM personas
            WHERE id_persona = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

