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
        while ($fila = mysqli_fetch_assoc($result)) {
            array_push($personas, $fila);
        }
        mysqli_close($conn);
        return $personas;
    }
}

$data = new Personas($conn);
$personas = $data->findAll();

foreach ($personas as $persona) {
    echo $persona['nombre'];
    echo "<br>";
}
