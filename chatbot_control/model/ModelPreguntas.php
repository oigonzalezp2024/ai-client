<?php

Class ModelPreguntas{

    function preguntaId(int $preguntaId)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=chatbot', 'root', '');
        $sql = 'SELECT id_pregunta, su_pregunta, respuesta, persona_id FROM preguntas WHERE id_pregunta = :id_pregunta ORDER BY id_pregunta DESC;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_pregunta", $preguntaId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    function personaId(int $personaId)
    {
        $pdo = new PDO('mysql:host=localhost;dbname=chatbot', 'root', '');
        $sql = 'SELECT id_persona, nombre, celular, activo FROM personas WHERE id_persona = :id_persona ORDER BY id_persona DESC;';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_persona", $personaId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}
