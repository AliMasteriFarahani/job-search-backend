<?php

class Api extends Controller
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
    print_r($_GET);
    print_r($_SERVER['REQUEST_METHOD']);
  }

  //===============================
  //  GET :
  //===============================
  function getSavedJobs($employeeId,$pageId=1)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $savedJobs =  $this->model->getSavedJobs($employeeId,$pageId);
    echo json_encode($savedJobs);
    }
  }
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
  function getMilitaryStatus()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $militaryStatus =  $this->model->getMilitaryStatus();
      echo json_encode($militaryStatus);
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
  function getCompanyJobPositions($companyId, $empId,$pageId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getCompanyJobPositions($companyId, $empId,$pageId);
      //$newq = $this->model->checkJobSaved($jobId,$empId);
      echo json_encode($newq);
    }
  }
  function getCompanySummaryInfo($companyId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getCompanySummaryInfo($companyId);
      echo json_encode($newq);
    }
  }
  function jobs($employeeId=null,$pageId=1)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->jobs($employeeId,$pageId);
      echo json_encode($newq);
    }
  }

  function getCities($provinceId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getCities($provinceId);
      echo json_encode($newq);
    }
  }
  function getSalary()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getSalary();
      echo json_encode($newq);
    }
  }
  function getWorkExperience()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getWorkExperience();
      echo json_encode($newq);
    }
  }
  function getContract()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getContract();
      echo json_encode($newq);
    }
  }
  function getGrades()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getGrades();
      echo json_encode($newq);
    }
  }
  function getSearchJobs($employeeId=null,$pageId=1)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // $newq =json_decode($_POST['name']);
      // $newq = $this->model->getContract();
      $_POST = json_decode(file_get_contents("php://input"), true);

      $filters = [];

      isset($_POST['searchText']) && ($_POST['searchText'] != '') ? $filters['searchText'] = $_POST['searchText'] : '';
      isset($_POST['province']) && ($_POST['province'] != '' && $_POST['province'] != 0) ? $filters['province'] = $_POST['province'] : '';
      isset($_POST['category']) && ($_POST['category'] != '' && $_POST['category'] != 0) ? $filters['category'] = $_POST['category'] : '';
      isset($_POST['gender']) && ($_POST['gender'] != '' && $_POST['gender'] != 0) ? $filters['gender'] = $_POST['gender'] : '';
      isset($_POST['salary']) && ($_POST['salary'] != '' && $_POST['salary'] != 0) ? $filters['salary'] = $_POST['salary'] : '';
      isset($_POST['workExperience']) && ($_POST['workExperience'] != '' && $_POST['workExperience'] != 1) ? $filters['workExperience'] = $_POST['workExperience'] : '';

      isset($_POST['contractType']) && ($_POST['contractType'] != '' && $_POST['contractType'] != 0) ? $filters['contractType'] = $_POST['contractType'] : '';
      isset($_POST['sortStatus']) && ($_POST['sortStatus'] != '' && $_POST['sortStatus'] != 0) ? $filters['sortStatus'] = $_POST['sortStatus'] : $filters['sortStatus'] = 1;


     // print_r($filters);
      // if (isset($_POST['gender']) && ($_POST['gender'] != '' || $_POST['gender'] != 0)) {
      //   $filters['gender'] = $_POST['gender'];
      //   print_r($_POST['gender']);
      //   print_r($filters);
      // }

      // -1 $_POST = json_decode(array_keys($_POST)[0], true);
      // -1 print_r($_POST['name']);
      // echo $newq['name'];
      $newq = $this->model->getSearchJobs($employeeId,$pageId,$filters);
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
