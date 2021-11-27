<?php
use \Firebase\JWT\JWT;
class model_api extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    //===============================
    //  GET :
    //===============================
    function getCategories()
    {
        $sql = "SELECT * from jss_job_categories";
        $result = $this->doSelect($sql);
        return $result;
    }

    function getProvinces()
    {
        $sql = "SELECT * from jss_provinces";
        $result = $this->doSelect($sql);
        return $result;
    }
    function getMilitaryStatus()
    {
        $sql = "SELECT * from jss_military_service_status";
        $result = $this->doSelect($sql);
        return $result;
    }
    // get new jobs for home page : 
    function getNewJobs()
    {
        $now = date("Y-m-d H:i");
        $lastWeek = date("Y-m-d H:i", strtotime("-7 days"));
        // $this->dateDiffInDays();

        $sql = "SELECT jss_jobs.* ,jss_companies.name as companyName,jss_companies.logo,jss_provinces.name as provinceName 
                FROM jss_jobs 
                LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
                LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
                WHERE (jss_jobs.created_at BETWEEN '" . $lastWeek . "' AND '" . $now . "') AND  '" . $now . "' < jss_jobs.expire_date  
                LIMIT 16";

        $result = $this->doSelect($sql);
        return $result;
    }
    function getImmediateJobs()
    {

        $now = date("Y-m-d H:i");
        $lastWeek = date("Y-m-d H:i", strtotime("-7 days"));
        // $this->dateDiffInDays();

        $sql = "SELECT jss_jobs.* ,jss_companies.name as companyName,jss_companies.logo,jss_provinces.name as provinceName from jss_jobs 
        LEFT JOIN jss_companies ON 
        jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        WHERE (jss_jobs.created_at BETWEEN '" . $lastWeek . "' AND '" . $now . "') AND  '" . $now . "' < jss_jobs.expire_date  
        LIMIT 11";

        $result = $this->doSelect($sql);
        return $result;

        // original : 
        // $now = date("Y-m-d H:i");
        // $sql = "SELECT jss_jobs.* ,jss_companies.name from jss_jobs LEFT JOIN jss_companies ON 
        //  jss_jobs.fk_company_id = jss_companies.id  WHERE (DATEDIFF('2021-10-10 23:29:34', jss_jobs.created_at) >= 27 AND jss_jobs.created_at < jss_jobs.expire_date) AND  '" . $now . "' < jss_jobs.expire_date LIMIT 16";

        // $result = $this->doSelect($sql);
        // return $result;
    }
    function getJobDetails($jobId, $empId)
    {
        $now = date("Y-m-d H:i");
        $lastWeek = date("Y-m-d H:i", strtotime("-7 days"));
        // $this->dateDiffInDays();

        $sql =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,job_descriptions,workforce_num,military_status,gender,needed_skills,company_intro,expire_date ,jss_companies.name as companyName,logo,date_of_establishment,num_of_staff,site_address,jss_companies.id as companyId,jss_provinces.name as provinceName,jss_cities.name as cityName,jss_job_categories.title as catTitle ,jss_contract.contract_type,jss_salary.fee as salary,jss_work_experience.title as work_experience ,jss_age_range.title as age_range,jss_degree_of_education.title as degree_of_education
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_cities ON jss_jobs.fk_city_id = jss_cities.id 
        LEFT JOIN jss_job_categories ON jss_jobs.fk_category_id = jss_job_categories.id  
        LEFT JOIN jss_contract ON jss_jobs.fk_contract_id = jss_contract.id
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        LEFT JOIN jss_work_experience ON jss_jobs.fk_work_experience_id = jss_work_experience.id
        LEFT JOIN jss_age_range ON jss_jobs.fk_age_range_id = jss_age_range.id
        LEFT JOIN jss_degree_of_education ON jss_jobs.fk_degree_of_education_id = jss_degree_of_education.id
        WHERE jss_jobs.id = ?";

        $result = $this->doSelect($sql, [$jobId]);
        if (count($result) > 0) {
            $result[0]['isSaved'] = $this->checkJobSaved($jobId, $empId)[0]['isJobSaved'];
            $result[0]['expire_date'] = $this->dateDiffInDays($result[0]['expire_date'], $now);
        }
        return $result;
    }

    function getNumOfCompanyJobPositions($companyId)
    {
        $sql = "SELECT COUNT(id) as numOfJobPositions FROM jss_jobs  WHERE fk_company_id = ?";
        $result = $this->doSelect($sql, [$companyId]);
        return $result;
    }
    function checkJobSaved($id, $empId)
    {
        $sqlSave = "SELECT COUNT(fk_job_id) as isJobSaved FROM jss_saved_jobs WHERE jss_saved_jobs.fk_job_id = ? AND jss_saved_jobs.fk_employee_id=?";
        $result = $this->doSelect($sqlSave, [$id, $empId]);
        if ($result[0]['isJobSaved'] >= 1) {
            $result[0]['isJobSaved'] = 1;
        } elseif ($result[0]['isJobSaved'] == 0) {
            $result[0]['isJobSaved'] = 0;
        }
        //sleep(1);
        return $result;
    }


    function getSimilarPositions($jobId, $empId)
    {
        $sql =
            "SELECT jss_jobs.id as jobId,created_at,jss_jobs.title as jobTitle,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName,jss_salary.fee as salary 
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        WHERE jss_jobs.id <> ?
        LIMIT 4";
        $result = $this->doSelect($sql, [$jobId]);
        foreach ($result as $key => $value) {
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $empId)[0]['isJobSaved'];
            $result[$key]['created_at'] = $this->howManayDayAgo(strtotime($result[$key]['created_at']));
        }
        return $result;
    }
    function getCompanyJobPositions($companyId, $pageId, $empId)
    {
        if ($pageId == 1 || $pageId == 0) {
            $offset = 0;
        } else {
            $offset = ($pageId - 1) * 10;
        }
        $sql =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName ,jss_salary.fee as salary
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        WHERE jss_jobs.fk_company_id = ?
        LIMIT {$offset},10";


        $totalJobsSql =
            "SELECT COUNT(jss_jobs.id) as totalJobs
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        WHERE jss_jobs.fk_company_id = ?
        ";

        $totalJobs = $this->doSelect($totalJobsSql, [$companyId])[0]['totalJobs'];
        $perPage = 10;
        $allPages = ceil($totalJobs / $perPage);

        $result = $this->doSelect($sql, [$companyId]);
        foreach ($result as $key => $value) {
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $empId)[0]['isJobSaved'];
            $result[$key]['created_at'] = $this->howManayDayAgo(strtotime($result[$key]['created_at']));
        }

        $output['result'] = $result;
        $output['totalJobs'] = $totalJobs;
        $output['allPages'] = $allPages;
        $output['pageId'] = $pageId;
        return $output;
    }
    function getCompanySummaryInfo($companyId)
    {
        $sql =
            "SELECT jss_companies.name as companyName,logo,introduce,date_of_establishment,num_of_staff,site_address
        FROM jss_companies  
        WHERE jss_companies.id = ?";
        $result = $this->doSelect($sql, [$companyId]);
        return $result;
    }
    function jobs($employeeId, $pageId)
    {
        if ($pageId == 1 || $pageId == 0) {
            $offset = 0;
        } else {
            $offset = ($pageId - 1) * 10;
        }
        $sqlTotalJobs = "SELECT COUNT(id) as totalJobs FROM jss_jobs";
        $totalJobs = $this->doSelect($sqlTotalJobs)[0]['totalJobs'];
        $perPage = 10;
        $allPages = ceil($totalJobs / $perPage);
        $sql =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName ,jss_salary.fee as salary
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        ORDER BY jss_jobs.created_at DESC
        LIMIT {$offset},10";
        $result = $this->doSelect($sql);
        foreach ($result as $key => $value) {
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $employeeId)[0]['isJobSaved'];
            $result[$key]['created_at'] = $this->howManayDayAgo(strtotime($result[$key]['created_at']));
        }
        $output['result'] = $result;
        $output['totalJobs'] = $totalJobs;
        $output['allPages'] = $allPages;
        return $output;
    }
    function getCities($provinceId)
    {
        $sql = "SELECT id,name as cityName 
        From jss_cities WHERE jss_cities.fk_province_id = ?
        ";
        $result = $this->doSelect($sql, [$provinceId]);
        return $result;
    }
    function getSalary()
    {
        $sql = "SELECT id,fee  From jss_salary";
        $result = $this->doSelect($sql);
        return $result;
    }
    function getWorkExperience()
    {
        $sql = "SELECT id,title  From jss_work_experience";
        $result = $this->doSelect($sql);
        return $result;
    }
    function getContract()
    {
        $sql = "SELECT id,contract_type  From jss_contract";
        $result = $this->doSelect($sql);
        return $result;
    }
    function getGrades()
    {
        $sql = "SELECT id,title  From jss_grade";
        $result = $this->doSelect($sql);
        return $result;
    }

    //===============================
    //  POST :
    //===============================


    function getSearchJobs($empId, $pageId, $filters)
    {
        $sql2 =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName ,jss_salary.fee as salary
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id ";
        $sCount =
            "SELECT count(jss_jobs.id) as totalJobs FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id ";


        if (count($filters) > 1) {
            $sql2 .= ' WHERE ';
            $sCount .= ' WHERE ';
        }
        $allQ = [];
        $allParams = [];
        $whatSearch = [];
        foreach ($filters as $key => $value) {
            if (isset($filters[$key]) && $key == 'searchText') {
                $allQ[] = " (jss_jobs.title LIKE ? OR  jss_jobs.job_descriptions LIKE ?) ";
                $searchText = "%{$filters['searchText']}%";
                $allParams[] = $searchText;
                $allParams[] = $searchText;
                $whatSearch[] = ['name' => 'searchText', 'value' => $filters['searchText']];
            }
            if (isset($filters[$key]) && $key == 'province') {
                $allQ[] = " jss_jobs.fk_province_id= ? ";
                $provinceId = $filters['province'];
                $allParams[] = $provinceId;
                $prSql = "SELECT name as provinceName FROM jss_provinces WHERE id = ?  ";
                $province = $this->doSelect($prSql, [$provinceId])[0]['provinceName'];
                $whatSearch[] = ['name' => 'province', 'value' => $province];
            }
            if (isset($filters[$key]) && $key == 'category') {
                $allQ[] = " jss_jobs.fk_category_id = ? ";
                $categoryId = $filters['category'];
                $allParams[] = $categoryId;
                $caSql = "SELECT title FROM jss_job_categories WHERE id = ?  ";
                $category = $this->doSelect($caSql, [$categoryId])[0]['title'];
                $whatSearch[] = ['name' => 'category', 'value' => $category];
            }
            if (isset($filters[$key]) && $key == 'salary') {
                if ($filters[$key] == 1) {
                    $e = '=';
                } else {
                    $e = '>=';
                }
                $allQ[] = " jss_jobs.fk_salary_id {$e} ? ";
                $salaryId = $filters['salary'];
                $allParams[] = $salaryId;
                $saSql = "SELECT fee FROM jss_salary WHERE id = ?  ";
                $salary = $this->doSelect($saSql, [$salaryId])[0]['fee'];
                $whatSearch[] = ['name' => 'salary', 'value' => $salary];
            }
            if (isset($filters[$key]) && $key == 'workExperience') {
                $allQ[] = " jss_jobs.fk_work_experience_id = ? ";
                $workExperienceId = $filters['workExperience'];
                $allParams[] = $workExperienceId;
                $weSql = "SELECT title FROM jss_work_experience WHERE id = ?  ";
                $workExperience = $this->doSelect($weSql, [$workExperienceId])[0]['title'];
                $whatSearch[] = ['name' => 'workExperience', 'value' => $workExperience];
            }
            if (isset($filters[$key]) && $key == 'contractType') {
                $allQ[] = " jss_jobs.fk_contract_id = ? ";
                $contractTypeId = $filters['contractType'];
                $allParams[] = $contractTypeId;
                $ctSql = "SELECT contract_type FROM jss_contract WHERE id = ?  ";
                $contractType = $this->doSelect($ctSql, [$contractTypeId])[0]['contract_type'];
                $whatSearch[] = ['name' => 'contractType', 'value' => $contractType];
            }
            if (isset($filters[$key]) && $key == 'gender') {
                $allQ[] = " jss_jobs.gender = {$filters['gender']} ";
                if ($filters['gender'] == 1) {
                    $gender = 'آقا';
                } elseif ($filters['gender'] == 2) {
                    $gender = 'خانم';
                }
                $whatSearch[] = ['name' => 'gender', 'value' => $gender];
            }
        }

        $ssql = '';
        for ($i = 0; $i < count($allQ); $i++) {
            $ssql .= $allQ[$i];
            if (count($allQ) > 1 && $i != count($allQ) - 1) {
                $ssql .= ' AND ';
            }
        }
        $sql2 .= $ssql;
        $sCount .= $ssql;
        //$sql2 .= "ORDER BY jss_jobs.created_at DESC";
        $order = intval($filters['sortStatus']);
        if ($order  == 2) {
            $sql2 .= " ORDER BY jss_jobs.fk_salary_id DESC ";
        } else {
            $sql2 .= " ORDER BY jss_jobs.created_at DESC ";
        }

        if ($pageId == 1 || $pageId == 0) {
            $offset = 0;
        } else {
            $offset = ($pageId - 1) * 10;
        }
        $sql2 .= " LIMIT {$offset},10 ";

        //   $totalJobs= '';
        if (count($filters) > 1) {
            $totalJobs = $this->doSelect($sCount, $allParams)[0]['totalJobs'];
        } else {
            $sqlTotalJobs = "SELECT COUNT(id) as totalJobs FROM jss_jobs";
            $totalJobs = $this->doSelect($sqlTotalJobs)[0]['totalJobs'];
        }

        $perPage = 10;
        $allPages = ceil($totalJobs / $perPage);
        $result = $this->doSelect($sql2, $allParams);

        //$result = $this->doSelect($sql, [$searchText, $searchText, $provinceId, $categoryId, $salaryId, $workExperienceId, $contractTypeId]);

        foreach ($result as $key => $value) {
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $empId)[0]['isJobSaved'];
            $result[$key]['created_at'] = $this->howManayDayAgo(strtotime($result[$key]['created_at']));
        }

        $output['result'] = $result;
        $output['totalJobs'] = $totalJobs;
        $output['allPages'] = $allPages;
        $output['pageId'] = $pageId;
        $output['whatSearch'] = $whatSearch;

        return $output;
    }
    function addJobToSaved($jobId, $empId)
    {
        $sqlSave = "INSERT INTO jss_saved_jobs (fk_job_id,fk_employee_id) VALUES (?,?)";
        $result = $this->doQuery($sqlSave, [$jobId, $empId]);
        return $result;
    }
    function registerEmployee($registerInfo)
    {
        $sqlIs = "SELECT COUNT(id) as isExist FROM jss_employees WHERE user_name=? AND email =?";
        $isUserExist = $this->doSelect($sqlIs, [$registerInfo['username'], $registerInfo['email']]);

        if ($isUserExist[0]['isExist'] == 0) {
            $sql = "INSERT INTO jss_employees (user_name,email,password) VALUES (?,?,?)";
            $this->doQuery($sql, [$registerInfo['username'], $registerInfo['email'], $registerInfo['password']]);
            $id = "SELECT id FROM jss_employees WHERE user_name=? AND email =?";
            $id = $this->doSelect($id, [$registerInfo['username'], $registerInfo['email']]);
            $sql = "INSERT INTO jss_resume (fk_employee_id) VALUES (?)";
            $this->doQuery($sql, [$id[0]['id']]);
        } else {
            return 'failed';
        }
    }
    function loginEmployee($loginInfo)
    {
        $sql = "SELECT id,password FROM jss_employees WHERE (user_name=? OR email =?)";
        $isUserExist = $this->doSelect($sql, [$loginInfo['username'], $loginInfo['username']]);
        if (count($isUserExist) == 0) {
            return [];
        } else {
            $pass = $isUserExist[0]['password'];
        }

        // $loginInfo['password'] = 'kjhhdshhs';
        if (password_verify($loginInfo['password'], $pass) == 1) {
            $sqlIs = "SELECT id,user_name as username,email,token FROM jss_employees WHERE (user_name=? OR email =?)";
            $isUserExist = $this->doSelect($sqlIs, [$loginInfo['username'], $loginInfo['username']]);
            return $isUserExist;
        } else {
            return [];
        }
    }
    function checkEmailExist($email)
    {
        $sqlIs = "SELECT COUNT(id) as isExist FROM jss_employees WHERE email =?";
        $isUserExist = $this->doSelect($sqlIs, [$email]);
        return $isUserExist;
    }
    function checkUsernameExist($username)
    {
        $sqlIs = "SELECT COUNT(id) as isExist FROM jss_employees WHERE user_name =?";
        $isUserExist = $this->doSelect($sqlIs, [$username]);
        return $isUserExist;
    }
    function resetPasswordEmailRequest($email,$resetPassSecretKey)
    {
        $isExEmailSql = 'SELECT email from jss_employees WHERE email=?';
        $isExEmail = $this->doSelect($isExEmailSql, [$email]);

        if (!empty($isExEmail)) {
            $delSql = 'DELETE FROM jss_reset_password WHERE email=?';
            $this->doQuery($delSql, [$email]);
            $payload = array(
                // "iss" => "http://example.org",
                // "iat" => time()
                // "nbf" => time() + 10,
                // "exp" => time() + 3600
                $data = array(
                    'email'=>$isExEmail['0']['email'],
                    'created_at'=>date("Y-m-d H:i",time())
                )
            );
            // print_r('ooo');
            // return;
              $jwt = JWT::encode($payload,$resetPassSecretKey);
            $sql = "INSERT INTO jss_reset_password (email,token) VALUES (?,?)";
             $this->doQuery($sql, [$email, $jwt]);
             return $jwt;
        }
    }
    function isEmailExistInResetPassword($email){
        $isExEmailSql = 'SELECT email from jss_employees WHERE email=?';
        $isExEmail = $this->doSelect($isExEmailSql, [$email]);
        return $isExEmail;
    }

    //===============================
    //  DELETE :
    //===============================
    function removeJobFromSaved($jobId, $empId)
    {
        $sqlSave = "DELETE FROM jss_saved_jobs WHERE jss_saved_jobs.fk_job_id = ? AND jss_saved_jobs.fk_employee_id=?";
        $result = $this->doQuery($sqlSave, [$jobId, $empId]);
        return $result;
    }

    //===============================
    //  UPDATE :
    //===============================

    function changeEmployeePassword($email,$password){
        $sql = "UPDATE jss_employees SET password=? WHERE email=? ";
        $this->doQuery($sql, [$password,$email]);
    }












    function ss()
    {
        parent::sessionInit();
        parent::sessionSet('name', 'ali');
        return parent::sessionGet('name');
    }

    function dd()
    {
        parent::sessionInit();
        return parent::sessionGet('name');
    }









    function setJob()
    {
        $sql = "INSERT INTO jss_jobs (title,fk_province_id,fk_city_id,descriptions,salary,workforce_num,work_experience,
        military_status,degree_of_education,gender,age_range,fk_company_id,type_of_cooperation,needed_skills,img_banner,fk_category_id,expire_date)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $title = 'title';

        $expireDate = date("Y-m-d H:i", strtotime("+30 days"));
        $array = array('hello', '1', '2', 'fvfgfgf', '2', '7', '3', '1', '1', '1', '5', '1', '1', 'php', 'lsl.jpg', '1', $expireDate);
        $this->doQuery($sql, $array);
    }













    function getSlider()
    {
        $sql = "select * from slider";
        $result = $this->doSelect($sql);
        return $result;
    }

    function getProducts()
    {
        $sql = "select * from products";
        $result = $this->doSelect($sql);
        return $result;
    }

    function mostSaleProducts()
    {
        return $this->doSelect("select * from products where is_most_sale=1 limit 13");
    }
    function latestProducts()
    {
        return $this->doSelect("select * from products order by id desc limit 12");
    }
    function products($pageId)
    {
        return $this->doSelect('select * from products limit ' . $pageId . ',9');
    }
    function product($productId)
    {
        return $this->doSelect('select * from products where id=' . $productId . '');
    }





    function getSavedJobs($employeeId, $pageId)
    {
        if ($pageId == 1 || $pageId == 0) {
            $offset = 0;
        } else {
            $offset = ($pageId - 1) * 10;
        }
        $sql =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName ,jss_salary.fee as salary
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_saved_jobs ON jss_saved_jobs.fk_employee_id = ? 
        LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id 
        WHERE jss_jobs.id = jss_saved_jobs.fk_job_id
        LIMIT {$offset},10";
        //     $sql =
        //     "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName ,jss_salary.fee as salary
        // FROM jss_jobs  
        // LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        // LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        // LEFT JOIN jss_salary ON jss_jobs.fk_salary_id = jss_salary.id
        // WHERE jss_jobs.fk_company_id = ?
        // LIMIT {$offset},10";

        $totalSavedSql = "SELECT COUNT(jss_saved_jobs.id) as totalSaved
         FROM jss_saved_jobs  
         WHERE  jss_saved_jobs.fk_employee_id=?";


        $totalSaved = $this->doSelect($totalSavedSql, [$employeeId])[0]['totalSaved'];
        $perPage = 10;
        $allPages = ceil($totalSaved / $perPage);

        $result = $this->doSelect($sql, [$employeeId]);
        foreach ($result as $key => $value) {
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $employeeId)[0]['isJobSaved'];
            $result[$key]['created_at'] = $this->howManayDayAgo(strtotime($result[$key]['created_at']));
        }


        $output['result'] = $result;
        $output['totalSaved'] = $totalSaved;
        $output['allPages'] = $allPages;
        $output['pageId'] = $pageId;
        return $output;



        // $wselect = 'jss_jobs.id as jobId,jss_jobs.title as jobTitle,created_at,salary,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName';
        // $t = 
        // $this->select('jss_jobs',$wselect)
        // ->leftJoin('jss_companies','jss_jobs.fk_company_id = jss_companies.id')
        // ->leftJoin('jss_provinces','jss_jobs.fk_province_id = jss_provinces.id')
        // ->leftJoin('jss_saved_jobs','jss_saved_jobs.fk_employee_id = ?')
        // ->where('jss_jobs.id ','=', 'jss_saved_jobs.fk_job_id')
        // ->sql();

    }
}
