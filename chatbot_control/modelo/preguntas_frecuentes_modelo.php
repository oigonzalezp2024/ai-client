<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id = $_POST['id_pregunta_f'];
    $su_pregunta = $_POST['su_pregunta'];
    $respuesta = $_POST['respuesta'];
    $usuario_id = $_POST['usuario_id'];

    $sql="INSERT INTO preguntas_frecuentes(
          id_pregunta_f, su_pregunta, respuesta, usuario_id
          )VALUE(
          '$id', '$su_pregunta', '$respuesta', '$usuario_id')";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo mysqli_insert_id($conn);
    }
}

elseif($accion == "modificar"){

    $id = $_POST['id_pregunta_f'];
    $su_pregunta = $_POST['su_pregunta'];
    $respuesta = $_POST['respuesta'];
    $usuario_id = $_POST['usuario_id'];

    $sql="UPDATE preguntas_frecuentes SET
          su_pregunta = '$su_pregunta', 
          respuesta = '$respuesta', 
          usuario_id = '$usuario_id'
          WHERE id_pregunta_f = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

elseif($accion == "borrar"){

    $id = $_POST['id_pregunta_f'];

    $sql = "DELETE FROM preguntas_frecuentes
            WHERE id_pregunta_f = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

