<?php
include_once '../../modelo/conexion.php';
$conn = conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>arreglos</title>
</head>
<div class="row"><br><br><br><br>
    <div>
<center>
<h2>Usuarios</h2>
</center>
<button class="btn navbar-left"
               data-toggle="modal"
               data-target="#modalNuevo">
    <span class="glyphicon glyphicon-plus"></span>
</button></div>
    <div class="table-responsive">
    <table class="table table-hover table-condensed">
    <thead>
        <tr><th></th>
            <th>id_usuario</th>
            <th>nombre</th>
            <th>celular</th>
            <th>fecha_registro</th>
            <th>activo</th>
            <th>email</th>
        <th></th></tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM usuarios ORDER BY id_usuario DESC';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_usuario'] . "||" .
                  $fila['nombre'] . "||" .
                  $fila['celular'] . "||" .
                  $fila['fecha_registro'] . "||" .
                  $fila['activo'] . "||" .
                  $fila['email'];
    ?>
        <tr>
            <td style="text-align:right;">
                <a target="_blank" href="../pdf/pdff_usuarios_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                <a href="./usuarios_id.php?id=<?php echo $fila['id_usuario']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                <button class="btn glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button>
            
                <button class="btn glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_usuario']; ?>')">
                </button>
</td>
            <td><?php echo $fila['id_usuario']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['celular']; ?></td>
            <td><?php echo $fila['fecha_registro']; ?></td>
            <td><?php echo $fila['activo']; ?></td>
            <td><?php echo $fila['email']; ?></td>
            <td>
                <a target="_blank" href="../pdf/pdff_usuarios_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                <a href="./usuarios_id.php?id=<?php echo $fila['id_usuario']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                <button class="btn glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button>
            
                <button class="btn glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_usuario']; ?>')">
                </button>
</td>
        </tr>
    <?php
    }
    ?>
    </tbody>
    </table>
<div>
</div>
</body>
</html>
<?php
mysqli_close($conn);
?>
