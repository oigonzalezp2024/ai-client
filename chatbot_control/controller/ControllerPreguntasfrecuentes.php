<?php

Class ControllerPreguntasfrecuentes{

    function pregunta_fId(int $pregunta_fId)
    {
        include "../../model/ModelPreguntasfrecuentes.php";
        $model = new ModelPreguntasfrecuentes();
        return $model->pregunta_fId($pregunta_fId);
    }

    function usuarioId(int $usuarioId)
    {
        include "../../model/ModelUsuarios.php";
        $model = new ModelUsuarios();
        return $model->usuarioId($usuarioId);
    }
}
