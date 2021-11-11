<?php
header("Access-Control-Allow-Origin:*");
// header('Access-Control-Allow-Credentials: true');
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
 header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
// header('Content-Type: application/json');
// header('Access-Control-Max-Age: 86400');
// header("Access-Control-Allow-Headers: *");
// header("Vary: *");
header("Authorization:*");
use \Firebase\JWT\JWT;

class employee extends Controller
{
  function __construct()
  {
    header("Authorization:*");
    //  header ("Authorization:ali");
    //  header('Access-Control-Allow-Origin: *');
    //  header('Content-type: application/json');
    $auth = getallheaders()['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
      //header('X-PHP-Response-Code: 401', true, 401);
      echo json_encode(['msg' => 'access denied']);
      //http_response_code(401);
      exit;
    }
    $auth = $matches[1];
    if ($auth) {
      try {
        $decoded = JWT::decode($auth, $this->secretKey, array('HS256'));
        http_response_code(200);
        ///print_r($decoded);
      } catch (Exception $e) {

        // show error message
        echo json_encode(array(
          "message" => "Access denied.",
          "error" => $e->getMessage()
        ));
        http_response_code(401);
        exit;
        //die();
      }
    } else {
      header('HTTP/1.0 401 Unauthorized');
      echo json_encode(array(
        "message" => "access denied"
      ));

      die();
    }




  //   // ----------------------------
  //   // $r = $_SERVER['HTTP_AUTHORIZATION'];
  //   // RewriteEngine On
  //   // RewriteCond %{HTTP:Authorization} ^(.+)$
  //   // RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
  //   //----------------------------
  //   //   header('HTTP/1.1 401 Unauthorized');
  //   //   header('X-PHP-Response-Code: 401', true, 401);
  //   //   http_response_code(401);
   }

  function index()
  {
    echo 'index API';
    echo '<br>';
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
  function getEducation($employeeId, $id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $education =  $this->model->getEducation($employeeId, $id);
      if (count($education) == 0) {
        $education = '';
      } else {
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
  function getJobExperience($employeeId, $id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $jobExperience =  $this->model->getJobExperience($employeeId, $id);
      if (count($jobExperience) == 0) {
        $jobExperience = '';
      } else {
        $jobExperience = $jobExperience[0];
      }
      echo json_encode($jobExperience);
    }
  }
  function getAllLanguageSkills($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $languageSkills =  $this->model->getAllLanguageSkills($employeeId);
      if (count($languageSkills) == 0) {
        $languageSkills = '';
      }
      echo json_encode($languageSkills);
    }
  }
  function getLanguageSkill($employeeId, $id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $languageSkill =  $this->model->getLanguageSkill($employeeId, $id);
      if (count($languageSkill) == 0) {
        $languageSkill = '';
      } else {
        $languageSkill = $languageSkill[0];
      }
      echo json_encode($languageSkill);
    }
  }
  function getEmployeeAvatar($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $avatar =  $this->model->getEmployeeAvatar($employeeId);
      if (count($avatar) == 0 || $avatar['avatar'] == null) {
        $avatar = '';
      } else {
        $avatar = $avatar['avatar'];
      }
      echo json_encode($avatar);
    }
  }
  function getEmployeeResumeAttach($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $resumeAttach =  $this->model->getEmployeeResumeAttach($employeeId);
      if (count($resumeAttach) == 0 || $resumeAttach['resumeAttach'] == null) {
        $resumeAttach = '';
      } else {
        $resumeAttach = $resumeAttach['resumeAttach'];
      }
      echo json_encode($resumeAttach);
    }
  }
  function getResumeLeftSideInfo($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $resumeAttach =  $this->model->getResumeLeftSideInfo($employeeId);
      if (count($resumeAttach) == 0) {
        $resumeAttach = '';
      }
      echo json_encode($resumeAttach);
    }
  }
  function isJobApplied($employeeId, $jobId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $isApplied =  $this->model->isJobApplied($employeeId, $jobId);

      echo json_encode($isApplied);
    }
  }
  function getAppliedJobs($employeeId, $pageId = 1)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $allAppliedJobs =  $this->model->getAppliedJobs($employeeId, $pageId);

      echo json_encode($allAppliedJobs);
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
  //  POST :
  //===============================
  function saveEmployeeAvatar($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      if (isset($_FILES['avatar'])) {
        $avatar = $_FILES['avatar'];
        $msg = '';
        try {
          $msg = $this->model->saveEmployeeAvatar($avatar, $employeeId);
          $msg = $msg == '' ? 'ok' : $msg;
        } catch (\Throwable $th) {
          $msg = 'failed';
        }
        echo json_encode($msg);
      }
    }
  }
  function saveEmployeeResumeAttach($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      if (isset($_FILES['resumeAttach'])) {
        $resumeAttach = $_FILES['resumeAttach'];
        $msg = '';
        try {
          $msg = $this->model->saveEmployeeResumeAttach($resumeAttach, $employeeId);
          $msg = $msg == '' ? 'ok' : $msg;
        } catch (\Throwable $th) {
          $msg = 'failed';
        }
        echo json_encode($msg);
      }
    }
  }
  function applyJobForCompany($employeeId, $jobId, $sendSimilars)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $msg = '';
      try {
        $msg = $this->model->applyJobForCompany($employeeId, $jobId, $sendSimilars);
        $msg = $msg == '' ? 'applied' : $msg;
      } catch (\Throwable $th) {
        $msg = 'failed';
      }
      echo json_encode($msg);
    }
  }
  // function registerEmployee()
  // {
  //   //header('X-PHP-Response-Code: 404', true, 404);
  //   // header('Access-Control-Allow-Origin:http://localhost:8080/employee/login');
  //  // print_r(getallheaders());
  //   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //     $_POST = json_decode(file_get_contents("php://input"), true);
  //     $registerInfo = [];
  //     isset($_POST['email']) && ($_POST['email'] != '') ? $registerInfo['email'] = $_POST['email'] : '';
  //     isset($_POST['username']) && ($_POST['username'] != '') ? $registerInfo['username'] = $_POST['username'] : '';
  //     isset($_POST['password']) && ($_POST['password'] != '') ? $registerInfo['password'] = $_POST['password'] : '';
  //     isset($_POST['rePassword']) && ($_POST['rePassword'] != '') ? $registerInfo['rePassword'] = $_POST['rePassword'] : '';
  //     $msg = '';
  //     try {
  //       if (count($registerInfo) == 4 && $registerInfo['password'] == $registerInfo['rePassword']) {
  //         $this->model->registerEmployee($registerInfo);
  //         $msg = 'ok';
  //       } else {
  //         $msg = 'failed';
  //       }
  //     } catch (\Throwable $th) {
  //       $msg = 'failed';
  //     }
  //     // header('X-PHP-Response-Code: 404', true, 400);
  //     echo json_encode($msg);
  //     //   $r =  http_response_code(401);
  //   }
  // }
  // function loginEmployee()
  // {
  //   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //     // print_r(getallheaders());
  //     // print_r('sssss');
  //     // return;
  //     $_POST = json_decode(file_get_contents("php://input"), true);
  //     $loginInfo = [];
  //     isset($_POST['username']) && ($_POST['username'] != '') ? $loginInfo['username'] = $_POST['username'] : '';
  //     isset($_POST['password']) && ($_POST['password'] != '') ? $loginInfo['password'] = $_POST['password'] : '';
  //     $secret_key = "opisdf4538kbmnsd#sldlj454sdfsblu78732ljsdfh";
  //     $payload = array(
  //         // "iss" => "http://example.org",
  //         // "iat" => time()
  //         // "nbf" => time() + 10,
  //         // "exp" => time() + 3600
  //         $data = array(
  //             'id' => '1',
  //             'username'=>'alifarahani',
  //             'email'=>'alifarahani@gmail.com',
  //             'expire'=>time()*100,
  //         )
  //     );

  //     $jwt = JWT::encode($payload, $secret_key);
  //     $msg = '';
  //     if (count($loginInfo)==2) {       
  //       $result = $this->model->loginEmployee($loginInfo);
  //       if (count($result) > 0) {
  //         $result[0]['token']=$jwt;
  //         $result[0]['expire']=time()+1*24*3600;
  //         $msg = ['status'=>true,'userInfo'=>$result[0]];
  //       }else{
  //         $msg = ['status'=>false,'msg'=>'not found'];
  //         http_response_code(401);
  //       }
  //     }
  //     echo json_encode($msg);
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
  function updateSkills($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $skills = [];
      isset($_POST['skills']) && ($_POST['skills'] != '') ? $skills = $_POST['skills'] : '';
      if (count($skills) == 0) {
        $msg = '';
        try {
          $this->model->updateSkills($employeeId);
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

  function updateEducation($employeeId, $id)
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
          $this->model->updateEducation($education, $employeeId, $id);
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
  function updateJobExperience($employeeId, $id)
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
          $this->model->updateJobExperience($jobExperience, $employeeId, $id);
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
  function updateLanguageSkill($employeeId, $id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $languageSkill = [];
      isset($_POST['title']) && ($_POST['title'] != '') ? $languageSkill['title'] = $_POST['title'] : '';
      isset($_POST['level']) && ($_POST['level'] != '') ? $languageSkill['level'] = $_POST['level'] : '';

      if (count($languageSkill) == 2) {
        $msg = '';
        $this->model->updateLanguageSkill($languageSkill, $employeeId, $id);
        try {
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
  function saveLanguageSkill($employeeId)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $languageSkill = [];
      isset($_POST['title']) && ($_POST['title'] != '') ? $languageSkill['title'] = $_POST['title'] : '';
      isset($_POST['level']) && ($_POST['level'] != '') ? $languageSkill['level'] = $_POST['level'] : '';

      if (count($languageSkill) == 2) {
        $msg = '';
        try {
          $this->model->saveLanguageSkill($languageSkill, $employeeId);
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
  function removeEducationResume($id)
  {
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
  function removeJobExperience($id)
  {
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
  function removeLanguageSkill($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

      if (isset($id)) {
        $msg = '';
        try {
          $this->model->removeLanguageSkill($id);
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
  function removeEmployeeAvatar($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

      if (isset($id)) {
        $msg = '';
        try {
          $this->model->removeEmployeeAvatar($id);
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
