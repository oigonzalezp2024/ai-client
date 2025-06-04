<?php
include 'conexion.php';
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id = $_POST['id_pregunta'];
    $su_pregunta = $_POST['su_pregunta'];
    $respuesta = $_POST['respuesta'];
    $persona_id = $_POST['persona_id'];

    $sql="INSERT INTO preguntas(
          id_pregunta, su_pregunta, respuesta, persona_id
          )VALUE(
          '$id', '$su_pregunta', '$respuesta', '$persona_id')";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo mysqli_insert_id($conn);
    }
}

elseif($accion == "modificar"){

    $id = $_POST['id_pregunta'];
    $su_pregunta = $_POST['su_pregunta'];
    $respuesta = $_POST['respuesta'];
    $persona_id = $_POST['persona_id'];

    $sql="UPDATE preguntas SET
          su_pregunta = '$su_pregunta', 
          respuesta = '$respuesta', 
          persona_id = '$persona_id'
          WHERE id_pregunta = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

elseif($accion == "borrar"){

    $id = $_POST['id_pregunta'];

    $sql = "DELETE FROM preguntas
            WHERE id_pregunta = '$id'";

    $consulta = mysqli_query($conn, $sql);
    if($consulta = true)
    {
        echo $id;
    }
}

