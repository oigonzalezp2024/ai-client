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
<h2>Preguntas</h2>
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
            <th>id_pregunta</th>
            <th>su_pregunta</th>
            <th>respuesta</th>
            <th>persona_id</th>
        <th></th></tr>
   </thead>
    <tbody>
    <?php
    $sql = 'SELECT * FROM preguntas ORDER BY id_pregunta DESC';
    $result = mysqli_query($conn, $sql);
    WHILE($fila = mysqli_fetch_assoc($result)){
        $datos = $fila['id_pregunta'] . "||" .
                  $fila['su_pregunta'] . "||" .
                  $fila['respuesta'] . "||" .
                  $fila['persona_id'];
    ?>
        <tr>
            <td style="text-align:right;">
                <a target="_blank" href="../pdf/pdff_preguntas_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                <a href="./preguntas_id.php?id=<?php echo $fila['id_pregunta']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                <button class="btn glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button>
            
                <button class="btn glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_pregunta']; ?>')">
                </button>
</td>
            <td><?php echo $fila['id_pregunta']; ?></td>
            <td><?php echo $fila['su_pregunta']; ?></td>
            <td><?php echo $fila['respuesta']; ?></td>
            <td><?php echo $fila['persona_id']; ?></td>
            <td>
                <a target="_blank" href="../pdf/pdff_preguntas_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                <a href="./preguntas_id.php?id=<?php echo $fila['id_pregunta']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                <button class="btn glyphicon glyphicon-pencil"
                               data-toggle="modal"
                               data-target="#modalEdicion"
                               onclick="agregaform('<?php echo $datos; ?>')">
                </button>
            
                <button class="btn glyphicon glyphicon-remove"
                           onclick="preguntarSiNo('<?php echo $fila['id_pregunta']; ?>')">
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
