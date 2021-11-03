<?php

class Employer extends Controller
{

  function __construct()
  {
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
  }

  function index()
  {
    echo 'index API';
    echo '<br>';

    print_r($_REQUEST);
    print_r($_SERVER['REQUEST_METHOD']);
  }

  //===============================
  //  GET :
  //===============================
  function getCategories()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $categories =  $this->model->getCategories();
    echo json_encode($categories);
    }
  }

  function getProvinces()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $provinces =  $this->model->getProvinces();
    echo json_encode($provinces);
    }
  }
  function getNewJobs()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $newq = $this->model->getNewJobs();
    // print_r(unserialize($newq[0]['needed_skills']));
    //$newq[0]['needed_skills'] = unserialize($newq[0]['needed_skills']);
    //print_r($newq);
    echo json_encode($newq);
    }
  }
  function getImmediateJobs()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $newq = $this->model->getImmediateJobs();
    // print_r(unserialize($newq[0]['needed_skills']));
    //$newq[0]['needed_skills'] = unserialize($newq[0]['needed_skills']);
    echo json_encode($newq);
    }
  }
  function getJobDetails($id, $empId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //$isSaved = $this->model->checkJobSaved($id,1);
    $newq = $this->model->getJobDetails($id, $empId);
    $newq[0]['needed_skills'] = unserialize($newq[0]['needed_skills']);
    $newq[0]['numOfJobPosition'] = $this->model->getNumOfCompanyJobPositions($newq[0]['companyId'])[0]['numOfJobPositions'];

    echo json_encode($newq);
    }
  }
  function getNumOfCompanyJobPositions($companyId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getNumOfCompanyJobPositions($companyId);
      echo json_encode($newq);
    }
    }
  }
  function isJobSaved($id, $empId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $isSaved = $this->model->checkJobSaved($id, $empId);
    $isSaved = ['isJobSaved' => intval($isSaved[0]['isJobSaved'])];
    echo json_encode($isSaved);
    }
  }
  function getSimilarPositions($jobId, $empId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $newq = $this->model->getSimilarPositions($jobId, $empId);
    //$newq = $this->model->checkJobSaved($jobId,$empId);
    echo json_encode($newq);
    }
  }
  //===============================
  //  POST :
  //===============================
  function addJobToSaved($jobId, $empId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newq = $this->model->addJobToSaved($jobId, $empId);
    echo json_encode($newq);
    // print_r($_REQUEST);
    // print_r($_SERVER['REQUEST_METHOD']);
    }
  }
  //===============================
  //  DELETE :
  //===============================
  function removeJobFromSaved($jobId, $empId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $newq = $this->model->removeJobFromSaved($jobId, $empId);
    echo json_encode($newq);
    }
  }


  function ss()
  {
    $t = $this->model->ss();
    print_r($t);
  }

  function dd()
  {
    $t = $this->model->dd();
    print_r($t);
  }



  function setJob()
  {
    $this->model->setJob();
  }









  function getProducts()
  {
    echo json_encode($this->model->getProducts());
    // print_r($_SERVER['REQUEST_METHOD']);
  }

  function mostSaleProducts()
  {
    echo json_encode($this->model->mostSaleProducts());
  }
  function latestProducts()
  {
    echo json_encode($this->model->latestProducts());
  }
  function products()
  {

    (isset($_REQUEST["page_id"]) && is_numeric($_REQUEST["page_id"])) ? $pageId = (int)$_REQUEST["page_id"] : $pageId =  1;
    $products = $this->model->products($pageId);
    $pageCount = 4;
    $endPage = 4;
    $result = ['products' => $products, 'active_page' => $pageId, 'page_count' => $pageCount, 'page_id' => $pageId, 'end_page' => $endPage];
    echo json_encode($result);
  }
  function product($productId = 1)
  {
    echo json_encode($this->model->product($productId));
  }

  function deleteProduct($id)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      echo json_encode(['msg' => 'was deleted']);
    }
  }
}
