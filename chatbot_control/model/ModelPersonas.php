<?php

Class ModelPersonas{

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
