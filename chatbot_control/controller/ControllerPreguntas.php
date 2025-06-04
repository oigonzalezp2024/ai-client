<?php

Class ControllerPreguntas{

    function preguntaId(int $preguntaId)
    {
        include "../../model/ModelPreguntas.php";
        $model = new ModelPreguntas();
        return $model->preguntaId($preguntaId);
    }

    function personaId(int $personaId)
    {
        include "../../model/ModelPersonas.php";
        $model = new ModelPersonas();
        return $model->personaId($personaId);
    }
}
