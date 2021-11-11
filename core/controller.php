<?php

class Controller
{
    protected $secretKey = "opisdf453+2@8kbmnsd#sldlj454s*5#vljl_dfs_blu78732ljsdfh";
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