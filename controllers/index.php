<?php


class Index extends Controller
{

    function __construct()
    {
        //echo 'we are in index controller!<br>';
    }

     function index($u ='rrrr')
    {
       print_r($u);
        echo'index fddf';
    }
     function getSlider()
    {
        echo 'dfdfddddd';
     // return json_encode($this->model->getSlider());
        
    }
     function getProducts($product='ee')
    {
        print_r($product);
        echo 'prodict';
       // return json_encode($this->model->getProducts());
    }

}


