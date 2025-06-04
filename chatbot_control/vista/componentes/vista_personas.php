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
    <div style="margin-top: 90px; text-align: center;"><h2>Personas</h2></div>
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
                    <th>id_persona</th>
                    <th>nombre</th>
                    <th>celular</th>
                    <th>activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = 'SELECT * FROM personas ORDER BY id_persona DESC';
                $result = mysqli_query($conn, $sql);
                while ($fila = mysqli_fetch_assoc($result)) {
                    $datos = $fila['id_persona'] . "||" .
                        $fila['nombre'] . "||" .
                        $fila['celular'] . "||" .
                        $fila['activo'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <a target="_blank" href="../pdf/pdff_personas_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                            <a href="./personas_id.php?id=<?php echo $fila['id_persona']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_persona']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $fila['id_persona']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['celular']; ?></td>
                        <td><?php echo $fila['activo']; ?></td>
                        <td>
                            <a target="_blank" href="../pdf/pdff_personas_id.php"><i class="btn glyphicon glyphicon-download-alt"></i></a>
                            <a href="./personas_id.php?id=<?php echo $fila['id_persona']; ?>"><i class="btn glyphicon glyphicon-eye-open"></i></a>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $fila['id_persona']; ?>')">
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