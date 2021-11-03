<?php

class employee extends Controller
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
  function getSavedJobs($employeeId, $pageId = 1)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $savedJobs =  $this->model->getSavedJobs($employeeId, $pageId);
      echo json_encode($savedJobs);
    }
  }
  function getPersonalInfo($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $personalInfo =  $this->model->getPersonalInfo($employeeId);
      echo json_encode($personalInfo);
    }
  }
  function getPersonalInfoById($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $personalInfo =  $this->model->getPersonalInfoById($employeeId);
      echo json_encode($personalInfo);
    }
  }
  function getAboutMe($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $aboutMe =  $this->model->getAboutMe($employeeId);
      if ($aboutMe['aboutMe'] == null) {
        $aboutMe = '';
      }
      echo json_encode($aboutMe);
    }
  }
  function getSkills($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $skills =  $this->model->getSkills($employeeId);
      echo json_encode($skills['skills']);
    }
  }
  function getAllEducations($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $education =  $this->model->getAllEducations($employeeId);
      if (count($education) == 0) {
        $education = '';
      }
      echo json_encode($education);
    }
  }
  function getEducation($employeeId,$id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $education =  $this->model->getEducation($employeeId,$id);
      if (count($education) == 0) {
        $education = '';
      }else{
        $education = $education[0];
      }
      echo json_encode($education);
    }
  }
  function getAllJobExperience($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $jobExperience =  $this->model->getAllJobExperience($employeeId);
      if (count($jobExperience) == 0) {
        $jobExperience = '';
      }
      echo json_encode($jobExperience);
    }
  }
  function getJobExperience($employeeId,$id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $jobExperience =  $this->model->getJobExperience($employeeId,$id);
      if (count($jobExperience) == 0) {
        $jobExperience = '';
      }else{
        $jobExperience = $jobExperience[0];
      }
      echo json_encode($jobExperience);
    }
  }
  // function checkEmployeeHasResume($employeeId)
  // {
  //   if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  //     $personalInfo =  $this->model->checkEmployeeHasResume($employeeId);
  //     echo json_encode($personalInfo);
  //   }
  // }
  //===============================
  //  PATCH :
  //===============================
  function savePersonalInfo($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $personalInfo = [];
      isset($_POST['name']) && ($_POST['name'] != '') ? $personalInfo['name'] = $_POST['name'] : '';
      isset($_POST['family']) && ($_POST['family'] != '') ? $personalInfo['family'] = $_POST['family'] : '';
      isset($_POST['jobTitle']) && ($_POST['jobTitle'] != '') ? $personalInfo['jobTitle'] = $_POST['jobTitle'] : '';
      isset($_POST['birthdate']) && ($_POST['birthdate'] != '') ? $personalInfo['birthdate'] = $_POST['birthdate'] : '';
      isset($_POST['mobile']) && ($_POST['mobile'] != '') ? $personalInfo['mobile'] = $_POST['mobile'] : '';
      isset($_POST['address']) && ($_POST['address'] != '') ? $personalInfo['address'] = $_POST['address'] : '';
      isset($_POST['province']) && ($_POST['province'] != '') ? $personalInfo['province'] = $_POST['province'] : '';
      isset($_POST['maritalStatus']) && ($_POST['maritalStatus'] != '') ? $personalInfo['maritalStatus'] = intval($_POST['maritalStatus']) : '';
      isset($_POST['gender']) && ($_POST['gender'] != '') ? $personalInfo['gender'] = intval($_POST['gender']) : '';
      isset($_POST['militaryStatus']) && ($_POST['militaryStatus'] != '') ? $personalInfo['militaryStatus'] = $_POST['militaryStatus'] : '';
      isset($_POST['emailAddress']) && ($_POST['emailAddress'] != '') ? $personalInfo['emailAddress'] = $_POST['emailAddress'] : '';
      isset($_POST['salaryRequested']) && ($_POST['salaryRequested'] != '') ? $personalInfo['salaryRequested'] = $_POST['salaryRequested'] : '';


      if (count($personalInfo) == 12) {
        $msg = '';
        try {
          //code...
          $personalInfo =  $this->model->savePersonalInfo($personalInfo, $employeeId);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }



      // echo json_encode($personalInfo);
    }
  }
  function saveAboutMe($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $aboutMe = '';
      isset($_POST['aboutMe']) && ($_POST['aboutMe'] != '') ? $aboutMe = $_POST['aboutMe'] : '';
      if ($aboutMe != '') {
        $msg = '';
        try {
          //code...
          $aboutMe =  $this->model->saveAboutMe($aboutMe, $employeeId);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }
  function saveSkills($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $skills = [];
      isset($_POST['skills']) && ($_POST['skills'] != '') ? $skills = $_POST['skills'] : '';
      if (count($skills) > 0) {
        $msg = '';
        try {
          $this->model->saveSkills($skills, $employeeId);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }

  function updateEducation($employeeId,$id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $education = [];
      isset($_POST['majorTitle']) && ($_POST['majorTitle'] != '') ? $education['majorTitle'] = $_POST['majorTitle'] : '';
      isset($_POST['uniTitle']) && ($_POST['uniTitle'] != '') ? $education['uniTitle'] = $_POST['uniTitle'] : '';
      isset($_POST['grade']) && ($_POST['grade'] != '') ? $education['grade'] = intval($_POST['grade']) : '';
      isset($_POST['average']) && ($_POST['average'] != '') ? $education['average'] = $_POST['average'] : '';
      isset($_POST['startYear']) && ($_POST['startYear'] != '') ? $education['startYear'] = $_POST['startYear'] : '';
      isset($_POST['endYear']) && ($_POST['endYear'] != '') ? $education['endYear'] = $_POST['endYear'] : '';
      if (count($education) == 6) {
        $msg = '';
        try {
          $this->model->updateEducation($education, $employeeId,$id);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }
  function updateJobExperience($employeeId,$id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $jobExperience = [];
      isset($_POST['jobTitle']) && ($_POST['jobTitle'] != '') ? $jobExperience['jobTitle'] = $_POST['jobTitle'] : '';
      isset($_POST['orgTitle']) && ($_POST['orgTitle'] != '') ? $jobExperience['orgTitle'] = $_POST['orgTitle'] : '';
      isset($_POST['startYear']) && ($_POST['startYear'] != '') ? $jobExperience['startYear'] = $_POST['startYear'] : '';
      isset($_POST['endYear']) && ($_POST['endYear'] != '') ? $jobExperience['endYear'] = $_POST['endYear'] : '';
      if (count($jobExperience) == 4) {
        $msg = '';
        try {
          $this->model->updateJobExperience($jobExperience, $employeeId,$id);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }


  //===============================
  //  PUT :
  //===============================

  function saveEducation($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $education = [];
      isset($_POST['majorTitle']) && ($_POST['majorTitle'] != '') ? $education['majorTitle'] = $_POST['majorTitle'] : '';
      isset($_POST['uniTitle']) && ($_POST['uniTitle'] != '') ? $education['uniTitle'] = $_POST['uniTitle'] : '';
      isset($_POST['grade']) && ($_POST['grade'] != '') ? $education['grade'] = intval($_POST['grade']) : '';
      isset($_POST['average']) && ($_POST['average'] != '') ? $education['average'] = $_POST['average'] : '';
      isset($_POST['startYear']) && ($_POST['startYear'] != '') ? $education['startYear'] = $_POST['startYear'] : '';
      isset($_POST['endYear']) && ($_POST['endYear'] != '') ? $education['endYear'] = $_POST['endYear'] : '';
      if (count($education) == 6) {
        $msg = '';
        try {
          $this->model->saveEducation($education, $employeeId);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }
  function saveJobExperience($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $jobExperience = [];
      isset($_POST['jobTitle']) && ($_POST['jobTitle'] != '') ? $jobExperience['jobTitle'] = $_POST['jobTitle'] : '';
      isset($_POST['orgTitle']) && ($_POST['orgTitle'] != '') ? $jobExperience['orgTitle'] = $_POST['orgTitle'] : '';
      isset($_POST['startYear']) && ($_POST['startYear'] != '') ? $jobExperience['startYear'] = $_POST['startYear'] : '';
      isset($_POST['endYear']) && ($_POST['endYear'] != '') ? $jobExperience['endYear'] = $_POST['endYear'] : '';
      if (count($jobExperience) == 4) {
        $msg = '';
        try {
          $this->model->saveJobExperience($jobExperience, $employeeId);
          $msg = 'ok';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }

    //===============================
  //  DELETE :
  //===============================
  function removeEducationResume($id){
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

      if (isset($id)) {
        $msg = '';
        try {
          $this->model->removeEducationResume($id);
          $msg = 'deleted';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }
  function removeJobExperience($id){
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

      if (isset($id)) {
        $msg = '';
        try {
          $this->model->removeJobExperience($id);
          $msg = 'deleted';
        } catch (\Throwable $th) {
          //throw $th;
          $msg = 'failed';
        }
        // sleep(2);
        echo json_encode($msg);
      }
    }
  }
}
