<?php
include '../../../Core/conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if ($accion == "insertar") {

    $id_item = $_POST['id_item'];
    $item_nombre = $_POST['item_nombre'];
    $stock_anterior = $_POST['stock_anterior'];
    $stock_actual = $_POST['stock_actual'];
    $stock_minimo = $_POST['stock_minimo'];
    $stock_maximo = $_POST['stock_maximo'];
    $bodega_id = $_POST['bodega_id'];
    $usuario_id = $_POST['usuario_id'];

    // Solo puede ingresar un item si la bodega le pertenece al mismo usuario 
    $sql = "SELECT id_bodega, usuario_id 
    FROM bodega
    WHERE id_bodega = $bodega_id
    AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {

        $sql = "INSERT INTO items(
          id_item, item_nombre, stock_anterior, stock_actual, stock_minimo, stock_maximo, bodega_id, usuario_id
          )VALUE(
          '$id_item', '$item_nombre', '$stock_anterior', '$stock_actual', '$stock_minimo', '$stock_maximo', '$bodega_id', '$usuario_id')";
        if (mysqli_query($conn, $sql)) {
            echo $bodega_id;
        }
    }
} elseif ($accion == "modificar") {

    $id_item = $_POST['id_item'];
    $item_nombre = $_POST['item_nombre'];
    $stock_anterior = $_POST['stock_anterior'];
    $stock_actual = $_POST['stock_actual'];
    $stock_minimo = $_POST['stock_minimo'];
    $stock_maximo = $_POST['stock_maximo'];
    $bodega_id = $_POST['bodega_id'];
    $usuario_id = $_POST['usuario_id'];

    // Solo puede modificar un item si la bodega le pertenece al mismo usuario 
    $sql = "SELECT id_bodega, usuario_id 
    FROM bodega
    WHERE id_bodega = $bodega_id
    AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {

        $sql = "UPDATE items SET
        item_nombre = '$item_nombre', 
        stock_anterior = '$stock_anterior', 
        stock_actual = '$stock_actual', 
        stock_minimo = '$stock_minimo', 
        stock_maximo = '$stock_maximo', 
        bodega_id = '$bodega_id', 
        usuario_id = '$usuario_id'
        WHERE id_item = '$id_item'";

        if (mysqli_query($conn, $sql)) {
            echo $bodega_id;
        }
    }
} elseif ($accion == "borrar") {

    $id_item = $_POST['id_item'];
    $usuario_id = $_POST['usuario_id'];
    // para la actualizacion de la tabla en la vista se requiere conocer el
    // id de la bodega.
    $sql = "SELECT bodega_id 
    FROM items 
    WHERE id_item = $id_item
    AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {
        $bodega_id = $fila['bodega_id'];
        // Solo puede eliminar un item si la bodega le pertenece al mismo usuario 
        $sql = "SELECT id_bodega, usuario_id 
            FROM bodega
            WHERE id_bodega = $bodega_id
            AND usuario_id = $usuario_id";
        $result = mysqli_query($conn, $sql);
        while ($fila = mysqli_fetch_assoc($result)) {

            $sql = "DELETE FROM items
                WHERE id_item = '$id_item'";

            if (mysqli_query($conn, $sql)) {
                echo $bodega_id;
            }
        }
    }
}
