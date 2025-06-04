<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

class PreguntasFrecuentes
{
    private $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn;
    }

    function findAll()
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM preguntas_frecuentes ORDER BY id_pregunta_f DESC';
        $result = mysqli_query($conn, $sql);
        $preguntasFrecuentes = [];
        while ($preguntaFrecuente = mysqli_fetch_assoc($result)) {
            array_push($preguntasFrecuentes, $preguntaFrecuente);
        }
        mysqli_close($conn);
        return $preguntasFrecuentes;
    }
}

$data = new PreguntasFrecuentes($conn);
$rows = $data->findAll();

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
        <h2>Preguntas frecuentes</h2>
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
                    <th></th>
                    <th>id_pregunta_f</th>
                    <th>su_pregunta</th>
                    <th>respuesta</th>
                    <th>usuario_id</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $datos = $row['id_pregunta_f'] . "||" .
                        $row['su_pregunta'] . "||" .
                        $row['respuesta'] . "||" .
                        $row['usuario_id'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_pregunta_f']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $row['id_pregunta_f']; ?></td>
                        <td><?php echo $row['su_pregunta']; ?></td>
                        <td><?php echo $row['respuesta']; ?></td>
                        <td><?php echo $row['usuario_id']; ?></td>
                        <td>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                        </td>
                        <td>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_pregunta_f']; ?>')">
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