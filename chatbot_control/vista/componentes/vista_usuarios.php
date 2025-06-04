<?php
include_once '../../modelo/conexion.php';
$conn = conexion();

class Usuarios
{
    private $conn;

    public function __construct($conn = null)
    {
        $this->conn = $conn;
    }

    function findAll()
    {
        $conn = $this->conn;
        $sql = 'SELECT * FROM usuarios ORDER BY id_usuario DESC';
        $result = mysqli_query($conn, $sql);
        $usuarios = [];
        while ($usuario = mysqli_fetch_assoc($result)) {
            array_push($usuarios, $usuario);
        }
        mysqli_close($conn);
        return $usuarios;
    }
}

$data = new Usuarios($conn);
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
            <h2>Usuarios</h2>
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
                    <th>id_usuario</th>
                    <th>nombre</th>
                    <th>celular</th>
                    <th>fecha_registro</th>
                    <th>activo</th>
                    <th>email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $datos = $row['id_usuario'] . "||" .
                        $row['nombre'] . "||" .
                        $row['celular'] . "||" .
                        $row['fecha_registro'] . "||" .
                        $row['activo'] . "||" .
                        $row['email'];
                ?>
                    <tr>
                        <td style="text-align:right;">
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_usuario']; ?>')">
                            </button>
                        </td>
                        <td><?php echo $row['id_usuario']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['celular']; ?></td>
                        <td><?php echo $row['fecha_registro']; ?></td>
                        <td><?php echo $row['activo']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <button class="btn glyphicon glyphicon-pencil"
                                data-toggle="modal"
                                data-target="#modalEdicion"
                                onclick="agregaform('<?php echo $datos; ?>')">
                            </button>
                            <button class="btn glyphicon glyphicon-remove"
                                onclick="preguntarSiNo('<?php echo $row['id_usuario']; ?>')">
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