<?php
$conn = conexion();

$accion = $_GET['accion'];

if ($accion == "insertar") {

    $id_bodega = $_POST['id_bodega'];
    $bodega_nombre = $_POST['bodega_nombre'];
    $usuario_id = $_POST['usuario_id'];

    $sql = "INSERT INTO bodega(
          id_bodega, bodega_nombre, usuario_id
          )VALUE(
          '$id_bodega', '$bodega_nombre', '$usuario_id')";

    mysqli_query($conn, $sql);
    echo $usuario_id;
} elseif ($accion == "modificar") {

    $id_bodega = $_POST['id_bodega'];
    $bodega_nombre = $_POST['bodega_nombre'];
    $usuario_id = $_POST['usuario_id'];

    // Solo puede modificar una bodega si la bodega le pertenece al mismo usuario 
    $sql = "SELECT id_bodega, usuario_id 
    FROM bodega
    WHERE id_bodega = $bodega_id
    AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {
        $sql = "UPDATE bodega SET
          bodega_nombre = '$bodega_nombre', 
          usuario_id = '$usuario_id'
          WHERE id_bodega = '$id_bodega'";
        mysqli_query($conn, $sql);
        echo $usuario_id;
    }
} elseif ($accion == "borrar") {

    $id_bodega = $_POST['id_bodega'];
    $usuario_id = $_POST['usuario_id'];

    // Solo puede eliminar una bodega si la bodega le pertenece al mismo usuario 
    $sql = "SELECT id_bodega, usuario_id 
    FROM bodega
    WHERE id_bodega = $id_bodega
    AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {
        $sql = "DELETE FROM bodega
        WHERE id_bodega = '$id_bodega'";
        mysqli_query($conn, $sql);
        echo $usuario_id;
    }
}
