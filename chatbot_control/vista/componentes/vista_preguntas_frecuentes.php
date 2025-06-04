<?php
include_once '../../modelo/conexion.php';
$conn = conexion();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="margin-top: 90px; text-align: center;">
        <h2>Personas</h2>
    </div>
    <button class="btn navbar-left"
        data-toggle="modal"
        data-target="#modalNuevo">
        <span class="glyphicon glyphicon-plus"></span>
    </button>
    <div class="table-responsive">
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>id_pregunta_f</th>
                    <th>su_pregunta</th>
                    <th>respuesta</th>
                    <th>usuario_id</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT * FROM preguntas_frecuentes ORDER BY id_pregunta_f DESC';
                $result = mysqli_query($conn, $sql);
                while ($fila = mysqli_fetch_assoc($result)) {
                    $datos = $fila['id_pregunta_f'] . "||" .
                        $fila['su_pregunta'] . "||" .
                        $fila['respuesta'] . "||" .
                        $fila['usuario_id'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <a target="_blank" href="../pdf/pdff_preguntas_frecuentes_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                            <a href="./preguntas_frecuentes_id.php?id=<?php echo $fila['id_pregunta_f']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_pregunta_f']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $fila['id_pregunta_f']; ?></td>
                        <td><?php echo $fila['su_pregunta']; ?></td>
                        <td><?php echo $fila['respuesta']; ?></td>
                        <td><?php echo $fila['usuario_id']; ?></td>
                        <td>
                            <a target="_blank" href="../pdf/pdff_preguntas_frecuentes_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                            <a href="./preguntas_frecuentes_id.php?id=<?php echo $fila['id_pregunta_f']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_pregunta_f']; ?>')">
                            </button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<?php
mysqli_close($conn);
?>