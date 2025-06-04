<?php

Class ModelPreguntasfrecuentes{

    function pregunta_fId(int $pregunta_fId)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=chatbot', 'root', '');
        $sql = 'SELECT id_pregunta_f, su_pregunta, respuesta, usuario_id FROM preguntas_frecuentes WHERE id_pregunta_f = :id_pregunta_f ORDER BY id_pregunta_f DESC;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_pregunta_f", $pregunta_fId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

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
