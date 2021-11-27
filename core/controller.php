<?php
	header("Access-Control-Allow-Origin:*");      
    //	header("Access-Control-Allow-Headers:
     //{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    //header("Access-Control-Allow-Origin:*");
    header("Accept:application/json"); 
    header("Authorization:*");
   // header('WWW-Authenticate: Basic realm="Test Authentication System"');
   header('Access-Control-Allow-Methods: GET, PUT,PATCH, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers:*,Authorization');
   // header('   Access-Control-Allow-Headers: Content-Type');
    
   
  //  header("Content-Type: application/x-www-form-urlencoded");
class Controller
{
    protected $secretKey = "opisdf453+2@8kbmnsd#sldlj454s*5#vljl_dfs_blu78732ljsdfh";
    protected $resetPassSecretKey = 'ouerwerkjhkjhiyue4$%sjkfs_-dkahjk#@';
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