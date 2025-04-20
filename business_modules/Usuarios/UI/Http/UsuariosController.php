<?php
$conn = conexion();

$accion = $_GET['accion'];

if($accion == "insertar"){

    $id_usuario = $_POST['id_usuario'];
    $usuario_nombre = $_POST['usuario_nombre'];
    $usuario_email = $_POST['usuario_email'];
    $usuario_celular = $_POST['usuario_celular'];
    $usuario_pass = $_POST['usuario_pass'];

    $sql="INSERT INTO usuario(
          id_usuario, usuario_nombre, usuario_email, usuario_celular, usuario_pass
          )VALUE(
          '$id_usuario', '$usuario_nombre', '$usuario_email', '$usuario_celular', '$usuario_pass')";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "modificar"){

    $id_usuario = $_POST['id_usuario'];
    $usuario_nombre = $_POST['usuario_nombre'];
    $usuario_email = $_POST['usuario_email'];
    $usuario_celular = $_POST['usuario_celular'];
    $usuario_pass = $_POST['usuario_pass'];

    $sql="UPDATE usuario SET
          usuario_nombre = '$usuario_nombre', 
          usuario_email = '$usuario_email', 
          usuario_celular = '$usuario_celular', 
          usuario_pass = '$usuario_pass'
          WHERE id_usuario = '$id_usuario'";

    echo $consulta = mysqli_query($conn, $sql);
}

elseif($accion == "borrar"){

    $id_usuario = $_POST['id_usuario'];

    $sql = "DELETE FROM usuario
            WHERE id_usuario = '$id_usuario'";

    echo $consulta = mysqli_query($conn, $sql);
}


?>