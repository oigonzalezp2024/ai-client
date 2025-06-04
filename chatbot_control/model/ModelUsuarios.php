<?php

Class ModelUsuarios{

    function usuarioId(int $usuarioId)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=chatbot', 'root', '');
        $sql = 'SELECT id_usuario, nombre, celular, fecha_registro, activo, email FROM usuarios WHERE id_usuario = :id_usuario ORDER BY id_usuario DESC;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_usuario", $usuarioId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}
