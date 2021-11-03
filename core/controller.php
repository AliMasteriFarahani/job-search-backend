<?php

class Controller
{
    function __construct()
    {

    }


    function model($modelUrl)
    {
        require('models/model_' . $modelUrl . '.php');
        $classname = 'model_' . $modelUrl;
        $this->model = new $classname;
    }

}


?>