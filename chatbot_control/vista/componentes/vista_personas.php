<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

class Personas
{
    private $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn;
    }

    function findAll()
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM personas ORDER BY id_persona DESC';
        $result = mysqli_query($conn, $sql);
        $personas = [];
        while ($persona = mysqli_fetch_assoc($result)) {
            array_push($personas, $persona);
        }
        mysqli_close($conn);
        return $personas;
    }
}

$data = new Personas($conn);
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
                foreach ($rows as $row) {
                    $persona = $row['id_persona'] . "||" .
                        $row['nombre'] . "||" .
                        $row['celular'] . "||" .
                        $row['activo'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $persona; ?>')">
                            </button>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_persona']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $row['id_persona']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['celular']; ?></td>
                        <td><?php echo $row['activo']; ?></td>
                        <td>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $persona; ?>')">
                            </button>

                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_persona']; ?>')">
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