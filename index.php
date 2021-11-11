<?php
// header('Access-Control-Allow-Origin:http://localhost:8080/employee/register');
// header('Content-Type: application/pdf');
// header('Access-Control-Allow-Origin: *');
// header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
// header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
// header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
// //header ("Authorization:ali");
// header('Access-Control-Max-Age: 86400');
//error_reporting(0);
require ('core/app.php');
require ('core/controller.php');
require ('core/model.php');
include './vendor/autoload.php';

new App;

