<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

class Preguntas
{
    private $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn;
    }

    function findAll()
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM preguntas ORDER BY id_pregunta DESC';
        $result = mysqli_query($conn, $sql);
        $preguntas = [];
        while ($pregunta = mysqli_fetch_assoc($result)) {
            array_push($preguntas, $pregunta);
        }
        mysqli_close($conn);
        return $preguntas;
    }
}

$data = new Preguntas($conn);
$rows = $data->findAll();

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
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>id_pregunta</th>
                    <th>su_pregunta</th>
                    <th>respuesta</th>
                    <th>persona_id</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $datos = $row['id_pregunta'] . "||" .
                        $row['su_pregunta'] . "||" .
                        $row['respuesta'] . "||" .
                        $row['persona_id'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_pregunta']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $row['id_pregunta']; ?></td>
                        <td><?php echo $row['su_pregunta']; ?></td>
                        <td><?php echo $row['respuesta']; ?></td>
                        <td><?php echo $row['persona_id']; ?></td>
                        <td>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_pregunta']; ?>')">
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