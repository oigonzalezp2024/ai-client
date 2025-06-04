<?php

Class ControllerUsuarios{

    function usuarioId(int $usuarioId)
    {
        include "../../model/ModelUsuarios.php";
        $model = new ModelUsuarios();
        return $model->usuarioId($usuarioId);
    }
}
