<?php

Class ControllerPersonas{

    function personaId(int $personaId)
    {
        include "../../model/ModelPersonas.php";
        $model = new ModelPersonas();
        return $model->personaId($personaId);
    }
}
