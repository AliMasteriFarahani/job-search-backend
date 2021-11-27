<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//require 'PHPMailer/src/Exception.php';
//require 'PHPMailer/src/PHPMailer.php';
//require 'PHPMailer/src/SMTP.php';
use \Firebase\JWT\JWT;
class Api extends Controller
{

  function __construct()
  {

  }

  function index()
  {
    echo 'index API';
    echo '<br>';
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer; 
 
    $mail->isSMTP();                      // Set mailer to use SMTP 
    $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
    $mail->SMTPAuth = true;               // Enable SMTP authentication 
    $mail->Username = 'alimasterifarahani95@gmail.com';   // SMTP username 
    $mail->Password = 'my_gmail.1000.i_can7';   // SMTP password 
    $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
    $mail->Port = 587;                    // TCP port to connect to 
     
    // Sender info 
    $mail->setFrom('alimasterifarahani95@gmail.com', 'CodexWorld'); 
    $mail->addReplyTo('alimasterifarahani95@gmail.com', 'CodexWorld'); 
     
    // Add a recipient 
    $mail->addAddress('alimasterifarahani95@gmail.com'); 
     
    //$mail->addCC('cc@example.com'); 
    //$mail->addBCC('bcc@example.com'); 
     
    // Set email format to HTML 
    $mail->isHTML(true); 
     
    // Mail subject 
    $mail->Subject = 'Email from Localhost by CodexWorld'; 
     
    // Mail body content 
    $bodyContent = '<h1>How to Send Email from Localhost using PHP by CodexWorld</h1>'; 
    $bodyContent .= '<p>This HTML email is sent from the localhost server using PHP by <b>CodexWorld</b></p>'; 
    $mail->Body    = $bodyContent; 
     
    // Send email 
    if(!$mail->send()) { 
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
    } else { 
        echo 'Message has been sent.'; 
    } 
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
    // header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
    // header("Access-Control-Max-Age", "3600");
    // header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    // header("Access-Control-Allow-Credentials", "true");
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getImmediateJobs();
      // print_r(unserialize($newq[0]['needed_skills']));
      //$newq[0]['needed_skills'] = unserialize($newq[0]['needed_skills']);
      echo json_encode($newq);
    }
  }
  function getJobDetails($id, $empId=null)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      //$isSaved = $this->model->checkJobSaved($id,1);

      try {
        $newq = $this->model->getJobDetails($id, $empId);
        //$newq = $this->model->getJobDetails($id, $empId);
        if (count($newq)>0) {
          $newq[0]['needed_skills'] = unserialize($newq[0]['needed_skills']);
          $newq[0]['numOfJobPosition'] = $this->model->getNumOfCompanyJobPositions($newq[0]['companyId'])[0]['numOfJobPositions'];
        }elseif(count($newq) == 0){
          echo json_encode('failed');
          return;
        }

      } catch (\Throwable $th) {
        echo json_encode('failed');
        return;
      }


      echo json_encode($newq[0]);
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
  function getSimilarPositions($jobId, $empId=null)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getSimilarPositions($jobId, $empId);
      //$newq = $this->model->checkJobSaved($jobId,$empId);
      echo json_encode($newq);
    }
  }
  function getCompanyJobPositions($companyId,$pageId,$empId=null)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $newq = $this->model->getCompanyJobPositions($companyId,$pageId,$empId);
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
  function registerEmployee()
  {
    //header('X-PHP-Response-Code: 404', true, 404);
    // header('Access-Control-Allow-Origin:http://localhost:8080/employee/login');
   // print_r(getallheaders());
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      $registerInfo = [];
      isset($_POST['email']) && ($_POST['email'] != '') ? $registerInfo['email'] = $_POST['email'] : '';
      isset($_POST['username']) && ($_POST['username'] != '') ? $registerInfo['username'] = $_POST['username'] : '';
      isset($_POST['password']) && ($_POST['password'] != '') ? $registerInfo['password'] = $_POST['password'] : '';
      isset($_POST['rePassword']) && ($_POST['rePassword'] != '') ? $registerInfo['rePassword'] = $_POST['rePassword'] : '';
      $msg = '';
      try {
        if (count($registerInfo) == 4 && $registerInfo['password'] == $registerInfo['rePassword']) {
          $registerInfo['password'] = password_hash($registerInfo['password'],PASSWORD_DEFAULT);
          $this->model->registerEmployee($registerInfo);
       //   $this->model->registerEmployee($registerInfo);
          $msg = 'ok';
        } else {
          $msg = 'failed';
        }
      } catch (\Throwable $th) {
        $msg = 'failed';
      }
      // header('X-PHP-Response-Code: 404', true, 400);
      echo json_encode($msg);
      //   $r =  http_response_code(401);
    }
  }
  function loginEmployee()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // print_r(getallheaders());
      // print_r('sssss');
      // return;
      $_POST = json_decode(file_get_contents("php://input"), true);
      $loginInfo = [];
      isset($_POST['username']) && ($_POST['username'] != '') ? $loginInfo['username'] = $_POST['username'] : '';
      isset($_POST['password']) && ($_POST['password'] != '') ? $loginInfo['password'] = $_POST['password'] : '';


    //  $secret_key = "opisdf453+2@8kbmnsd#sldlj454s*5#vljl_dfs_blu78732ljsdfh";
      $msg = '';
      if (count($loginInfo)==2) {       
       // $loginInfo['password'] = password_hash($loginInfo['password'],PASSWORD_DEFAULT);
        $result = $this->model->loginEmployee($loginInfo);
        if (count($result) > 0) {
          $payload = array(
            // "iss" => "http://example.org",
            // "iat" => time()
            // "nbf" => time() + 10,
            // "exp" => time() + 3600
            $data = array(
                'id' => $result[0]['id'],
                'username'=>$result[0]['username'],
                'email'=>$result[0]['email'],
                'expire'=>time()+1*24*3600,
            )
        );
          $jwt = JWT::encode($payload, $this->secretKey);
          $result[0]['token']=$jwt;
          $result[0]['expire']=time()+1*24*3600;
          $msg = ['status'=>true,'userInfo'=>$result[0]];
        }else{
          $msg = ['status'=>false,'msg'=>'not found'];
           http_response_code(401);
        }
      }
      echo json_encode($msg);
    }
  }

  function resetPasswordEmailRequest()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      if (!empty($_POST['email'])) {
        $msg='';
        try {
           $token = $this->model->resetPasswordEmailRequest($_POST['email'],$this->resetPassSecretKey);
           $this->sendResetPasswordEmail('alimasterifarahani95@gmail.com',$token);
          $msg = 'ok';
        } catch (\Throwable $th) {
          $msg = 'failed';
        }
      }
      echo json_encode($msg);
    }
  }
  function resetPassword($token=null,$email=null){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = json_decode(file_get_contents("php://input"), true);
      try {
        $msg="";
        if (!empty($_POST['password']) && $_POST['password'] == $_POST['rePassword']) {
        //  print_r($_POST);
         // print_r($token);
         //// print_r($email);
         $emailEx = $this->model->isEmailExistInResetPassword($email);
         if (count($emailEx) > 0) {
          $decoded = JWT::decode($token, $this->resetPassSecretKey, array('HS256'));
  
          $data = (array)$decoded[0];

          $timePassed = time()-strtotime($data['created_at']);
          if (floor(($timePassed%3600)/60) > 10) {
            $msg='passed';
            echo json_encode($msg);
            return;
          }
          $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
          $this->model->changeEmployeePassword($email,$password);
         }
          $msg = 'ok';
        }
      } catch (\Throwable $th) {
        $msg = 'failed';
      }
  
      echo json_encode($msg);
    }
  }
  function isEmployeeAuthenticated(){
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $authToken = getallheaders()['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $authToken, $matches)) {
     // header('X-PHP-Response-Code: 401', true, 401);
     //http_response_code(401);
      echo json_encode(['msg' => 'access denied']);
      exit;
    }
    $authToken = $matches[1];
    if ($authToken) {
      try {
        $decoded = JWT::decode($authToken, $this->secretKey, array('HS256'));
       // print_r((array)$decoded[0]);
        echo json_encode(['status'=>true,'userInfo'=>(array)$decoded[0]]);
      //  http_response_code(200);
        ///print_r($decoded);
      } catch (Exception $e) {
        // show error message
        echo json_encode(array(
          "status" => false,
        ));
       // http_response_code(401);
        exit;
        //die();
      }
    } else {
      header('HTTP/1.0 401 Unauthorized');
      echo json_encode(array(
        "status" => false,
      ));

      die();
    }
    }
  }
  function signOutUser(){
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      echo json_encode(['status'=>true]);
    }
  }
  function checkEmailExist($email){
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $result = $this->model->checkEmailExist($email);
      $status = $result[0]['isExist'] == 1 ? true :false; 
      echo json_encode(['status'=>$status]);
    }
  }
  function checkUsernameExist($email){
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $result = $this->model->checkUsernameExist($email);
      $status = $result[0]['isExist'] == 1 ? true :false; 
      echo json_encode(['status'=>$status]);
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


function sendResetPasswordEmail($to,$token){

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer; 

  $mail->isSMTP();                      // Set mailer to use SMTP 
  $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
  $mail->SMTPAuth = true;               // Enable SMTP authentication 
  $mail->Username = 'alimasterifarahani95@gmail.com';   // SMTP username 
  $mail->Password = 'my_gmail.1000.i_can7';   // SMTP password 
  $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
  $mail->Port = 587;                    // TCP port to connect to 
   
  // Sender info 
  $mail->setFrom('alimasterifarahani95@gmail.com', 'CodexWorld'); 
  $mail->addReplyTo('alimasterifarahani95@gmail.com', 'CodexWorld'); 
   
  // Add a recipient 
  $mail->addAddress($to); 
   
  //$mail->addCC('cc@example.com'); 
  //$mail->addBCC('bcc@example.com'); 
   
  // Set email format to HTML 
  $mail->isHTML(true); 
   
  // Mail subject 
  $mail->Subject = 'Email from Localhost by CodexWorld'; 
   
  // Mail body content 
  $link = "http://localhost:8080/reset-password?token={$token}&email={$to}";
  $bodyContent = '<h1>jobout.ir</h1>'; 
  $bodyContent .= "<p>this is your reset password link</p><br>"; 
  $bodyContent .= '<a href="'.$link.'">reset password</a>'; 
  $mail->Body    = $bodyContent; 
   
  // Send email 
  if(!$mail->send()) { 
      echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
  } else { 
      echo 'Message has been sent.'; 
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
