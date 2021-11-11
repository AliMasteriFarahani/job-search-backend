<?php
use \Firebase\JWT\JWT;
class model_employee extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    //===============================
    //  GET :                       
    //===============================

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

    function checkJobSaved($id, $empId)
    {
        $sqlSave = "SELECT COUNT(fk_job_id) as isJobSaved FROM jss_saved_jobs WHERE jss_saved_jobs.fk_job_id = ? AND jss_saved_jobs.fk_employee_id=?";
        $result = $this->doSelect($sqlSave, [$id, $empId]);
        if ($result[0]['isJobSaved'] >= 1) {
            $result[0]['isJobSaved'] = 1;
        } elseif ($result[0]['isJobSaved'] == 0) {
            $result[0]['isJobSaved'] = 0;
        }
        // sleep(1);
        return $result;
    }
    // function checkEmployeeHasResume($empId)
    // {
    //     $sql = "SELECT COUNT(id) as isExist FROM jss_resume WHERE fk_employee_id = ? ";
    //     $result = $this->doSelect($sql, [$empId])[0]['isExist'];
    //     return intVal($result);
    // }


    function getPersonalInfo($empId)
    {
        $sqlSave = "SELECT jss_resume.name,family,email_address as emailAddress,birthdate,mobile,address,job_title as jobTitle,gender,marital_status as maritalStatus,jss_military_service_status.title as militaryStatus,jss_provinces.name as province,jss_salary.fee as salaryRequested
        FROM jss_resume 
        LEFT JOIN jss_military_service_status ON jss_resume.fk_military_service_status_id = jss_military_service_status.id
        LEFT JOIN jss_provinces ON jss_resume.fk_province_id = jss_provinces.id
        LEFT JOIN jss_salary ON jss_resume.fk_salary_id = jss_salary.id
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sqlSave, [$empId])[0];
        if ($result['name'] == null) {
            $result = '';
        }
        return $result;
    }
    function getPersonalInfoById($empId)
    {
        $sqlSave = "SELECT jss_resume.name,family,email_address as emailAddress,birthdate,mobile,address,job_title as jobTitle,gender,marital_status as maritalStatus,fk_province_id as province ,fk_military_service_status_id as militaryStatus,fk_salary_id as salaryRequested
        FROM jss_resume 
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sqlSave, [$empId])[0];
        if ($result['gender'] == 'آقا') {
            $result['gender'] = 1;
        } elseif ($result['gender'] == 'خانم') {
            $result['gender'] = 2;
        }
        if ($result['maritalStatus'] == 'مجرد') {
            $result['maritalStatus'] = 1;
        } elseif ($result['maritalStatus'] == 'متاهل') {
            $result['maritalStatus'] = 2;
        }
        // if ($result['name'] == null) {
        //     $result = '';
        // }
        return $result;
    }
    function getAboutMe($empId)
    {
        $sql = "SELECT about_me as aboutMe 
        FROM jss_resume 
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId])[0];
        return $result;
    }
    function getSkills($empId)
    {
        $sql = "SELECT skills 
        FROM jss_resume 
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId])[0];
        $result['skills'] = unserialize($result['skills']);
        return $result;
    }
    function getAllEducations($empId)
    {
        $sql = "SELECT jss_resume_educational_background.id,major_title as majorTitle,start_year as startYear,end_year as endYear,uni as uniTitle,average,jss_grade.title as grade
        FROM jss_resume_educational_background 
        LEFT JOIN jss_grade ON jss_resume_educational_background.fk_grade_id = jss_grade.id
        WHERE jss_resume_educational_background.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId]);
        return $result;
    }
    function getEducation($empId, $id)
    {
        $sql = "SELECT major_title as majorTitle,start_year as startYear,end_year as endYear,uni as uniTitle,average,fk_grade_id as grade
        FROM jss_resume_educational_background 
        WHERE jss_resume_educational_background.fk_employee_id = ? AND jss_resume_educational_background.id=?";
        $result = $this->doSelect($sql, [$empId, $id]);
        return $result;
    }
    function getAllJobExperience($empId)
    {
        $sql = "SELECT id,job_title as jobTitle,start_year as startYear,end_year as endYear,org_name as orgTitle
        FROM jss_resume_job_experience
        WHERE jss_resume_job_experience.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId]);
        return $result;
    }

    function getJobExperience($empId, $id)
    {
        $sql = "SELECT id,job_title as jobTitle,start_year as startYear,end_year as endYear,org_name as orgTitle
        FROM jss_resume_job_experience
        WHERE jss_resume_job_experience.fk_employee_id = ? AND jss_resume_job_experience.id=?";
        $result = $this->doSelect($sql, [$empId, $id]);
        return $result;
    }

    function getAllLanguageSkills($empId)
    {
        $sql = "SELECT id,title,level
        FROM jss_resume_language_skills
        WHERE jss_resume_language_skills.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId]);
        return $result;
    }

    function getLanguageSkill($empId, $id)
    {
        $sql = "SELECT title,level
        FROM jss_resume_language_skills
        WHERE jss_resume_language_skills.fk_employee_id = ? AND jss_resume_language_skills.id=?";
        $result = $this->doSelect($sql, [$empId, $id]);
        return $result;
    }
    function getEmployeeAvatar($empId)
    {
        $sql = "SELECT avatar
        FROM jss_resume
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId])[0];
        return $result;
    }
    function getEmployeeResumeAttach($empId)
    {
        $sql = "SELECT resume_attach_file as resumeAttach
        FROM jss_resume
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId])[0];
        return $result;
    }
    function getResumeLeftSideInfo($empId)
    {
        $sql = "SELECT name,family,job_title as jobTitle
        FROM jss_resume
        WHERE jss_resume.fk_employee_id = ?";
        $result = $this->doSelect($sql, [$empId])[0];
        return $result;
    }
    function isJobApplied($empId, $jobId)
    {
        $sqlA = "SELECT COUNT(id) as isJobApplied FROM jss_applied WHERE fk_employee_id=? AND fk_job_id = ? ";
        $sqlB = "SELECT send_similars as sendSimilars FROM jss_applied WHERE fk_employee_id=? AND fk_job_id = ? ";
        $resultA = $this->doSelect($sqlA, [$empId, $jobId])[0];
        $resultB = $this->doSelect($sqlB, [$empId, $jobId]);
        if ($resultA['isJobApplied'] >= 1) {
            $resultA['isJobApplied'] = 1;
        } elseif ($resultA['isJobApplied'] == 0) {
            $resultA['isJobApplied'] = 0;
        }
        if (count($resultB) > 0) {
            $sendSimilars = $resultB[0]['sendSimilars'];
        } else {
            $sendSimilars = 0;
        }
        $result['sendSimilars'] = $sendSimilars;
        $result['isJobApplied'] = $resultA['isJobApplied'];
        return $result;
    }
    function getAppliedJobs($empId, $pageId)
    {
        if ($pageId == 1 || $pageId == 0) {
            $offset = 0;
        } else {
            $offset = ($pageId - 1) * 10;
        }
        $totalAppliedJob = "SELECT COUNT(jss_applied.id) as totalAppliedJob
        FROM jss_applied 
        WHERE  jss_applied.fk_employee_id=?";


        $totalAppliedJob = $this->doSelect($totalAppliedJob, [$empId])[0]['totalAppliedJob'];
        $perPage = 10;
        $allPages = ceil($totalAppliedJob / $perPage);

        $sql = "SELECT jss_applied.date,status,jss_jobs.title as jobTitle,jss_jobs.id as jobId,jss_provinces.name as province,jss_companies.logo,jss_companies.name as companyName
         FROM jss_applied 
         INNER JOIN jss_jobs ON jss_applied.fk_job_id = jss_jobs.id
         INNER JOIN jss_provinces ON jss_provinces.id = jss_jobs.fk_province_id
         INNER JOIN jss_companies ON jss_companies.id = jss_jobs.fk_company_id
         WHERE fk_employee_id=? LIMIT {$offset},10";

        $result = $this->doSelect($sql, [$empId]);
        $output['result'] = $result;
        $output['totalAppliedJob'] = $totalAppliedJob;
        $output['allPages'] = $allPages;
        $output['pageId'] = $pageId;
        return $output;
    }

    //===============================
    //  POST :                       
    //===============================
    function saveEmployeeAvatar($avatar, $employeeId)
    {

        if ($avatar['size'] > 5242880) {
            $msg = 'failed';
            return $msg;
        }
        if ($avatar['type'] != 'image/png' && $avatar['type'] != 'image/jpg' && $avatar['type'] != 'image/jpeg') {
            $msg = 'failed';
            return $msg;
        }
        if ($avatar['error'] != 0) {
            $msg = 'failed';
            return $msg;
        }
        if ($avatar['name'] != "") {
            if (!file_exists("public/employee/{$employeeId}/avatar")) {
                mkdir("public/employee/{$employeeId}/avatar", 0777, true);
            }
            $avatarPath = "public/employee/{$employeeId}/avatar/";
            $files = glob("$avatarPath/*"); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
            $path = $avatar['name'];
            $pathto = $avatarPath . $path;
            move_uploaded_file($avatar['tmp_name'], $pathto) or die("Could not copy file!");
            $sql = "UPDATE jss_resume SET avatar=? WHERE fk_employee_id=?";
            $this->doQuery($sql, [$path, $employeeId]);
        } else {
            $msg = 'failed';
            return $msg;
        }
    }

    function saveEmployeeResumeAttach($resumeAttach, $employeeId)
    {

        if ($resumeAttach['size'] > 5242880) {
            $msg = 'failed';
            return $msg;
        }
        if ($resumeAttach['type'] != 'application/pdf') {
            $msg = 'failed';
            return $msg;
        }
        if ($resumeAttach['error'] != 0) {
            $msg = 'failed';
            return $msg;
        }
        if ($resumeAttach['name'] != "") {
            if (!file_exists("public/employee/{$employeeId}/resumeAttachment")) {
                mkdir("public/employee/{$employeeId}/resumeAttachment", 0777, true);
            }
            $resumeAttachmentPath = "public/employee/{$employeeId}/resumeAttachment/";
            $files = glob("$resumeAttachmentPath/*"); // get all file names
            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    unlink($file); // delete file
                }
            }
            $path = $resumeAttach['name'];
            $pathto = $resumeAttachmentPath . $path;
            move_uploaded_file($resumeAttach['tmp_name'], $pathto) or die("Could not copy file!");
            $sql = "UPDATE jss_resume SET resume_attach_file=? WHERE fk_employee_id=?";
            $this->doQuery($sql, [$path, $employeeId]);
        } else {
            $msg = 'failed';
            return $msg;
        }
    }

    function applyJobForCompany($employeeId, $jobId, $sendSimilars)
    {
        if ($sendSimilars == 'true') {
            $sendSimilars = 1;
        } elseif ($sendSimilars == 'false') {
            $sendSimilars = 0;
        }
        $sql = "INSERT INTO jss_applied (fk_employee_id,fk_job_id,send_similars) VALUES (?,?,?)";
        $this->doQuery($sql, [$employeeId, $jobId, $sendSimilars]);
    }
    // function registerEmployee($registerInfo)
    // {
    //     $sqlIs = "SELECT COUNT(id) as isExist FROM jss_employees WHERE user_name=? AND email =?";
    //     $isUserExist = $this->doSelect($sqlIs, [$registerInfo['username'], $registerInfo['email']]);
    //     if ($isUserExist[0]['isExist'] == 0) {
    //         $sql = "INSERT INTO jss_employees (user_name,email,password,token) VALUES (?,?,?)";
    //         $this->doQuery($sql, [$registerInfo['username'], $registerInfo['email'], $registerInfo['password']]);
    //         $id = "SELECT id FROM jss_employees WHERE user_name=? AND email =?";
    //         $id = $this->doSelect($id, [$registerInfo['username'], $registerInfo['email']]);
    //         $sql = "INSERT INTO jss_resume (fk_employee_id) VALUES (?)";
    //         $this->doQuery($sql, [$id[0]['id']]);
    //     } else {
    //         return 'failed';
    //     }
    // }
    // function loginEmployee($loginInfo)
    // {
    //     $sqlIs = "SELECT id,user_name as username,email,token FROM jss_employees WHERE (user_name=? OR email =?) AND password=?";
    //     $isUserExist = $this->doSelect($sqlIs, [$loginInfo['username'], $loginInfo['username'],$loginInfo['password']]);
    //     return $isUserExist;
    // }

    //===============================
    //  PATCH :                       
    //===============================

    function savePersonalInfo($personalInfo, $employeeId)
    {
        // $sql = "INSERT INTO jss_resume (name,family,job_title,birthdate,mobile,address,fk_province_id,marital_status,gender,
        // fk_military_service_status_id,email_address,fk_salary_id,fk_employee_id)
        //  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $sql = "UPDATE jss_resume SET name=?,family=?,job_title=?,birthdate=?,mobile=?,address=?,fk_province_id=?,marital_status=?,gender=?,
        fk_military_service_status_id=?,email_address=?,fk_salary_id=? WHERE fk_employee_id=?";
        $now = date("Y-m-d H:i");
        $array = [];
        foreach ($personalInfo as  $value) {
            $array[] = $value;
        }
        $array[] = $employeeId;
        // $array[] = $now;
        $this->doQuery($sql, $array);
    }
    function saveAboutMe($aboutMe, $employeeId)
    {
        $sql = "UPDATE jss_resume SET about_me=? WHERE fk_employee_id=?";
        $this->doQuery($sql, [$aboutMe, $employeeId]);
    }


    function saveSkills($skills, $employeeId)
    {
        $skills = serialize($skills);
        $sql = "UPDATE jss_resume SET skills=? WHERE fk_employee_id=?";
        $this->doQuery($sql, [$skills, $employeeId]);
    }
    function updateSkills($employeeId)
    {
        $sql = "UPDATE jss_resume SET skills=? WHERE fk_employee_id=?";
        $this->doQuery($sql, [null, $employeeId]);
    }
    function updateEducation($education, $employeeId, $id)
    {

        $sql = "UPDATE jss_resume_educational_background SET major_title=?,uni=?,fk_grade_id=?,average=?,start_year=?,end_year=?,fk_employee_id=? WHERE id=?";
        $array = [];
        foreach ($education as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $array[] = $id;
        $this->doQuery($sql, $array);
    }
    function updateJobExperience($jobExperience, $employeeId, $id)
    {
        $sql = "UPDATE jss_resume_job_experience SET job_title=?,org_name=?,start_year=?,end_year=?,fk_employee_id=? WHERE id=?";
        $array = [];
        foreach ($jobExperience as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $array[] = $id;
        $this->doQuery($sql, $array);
    }
    function updateLanguageSkill($languageSkill, $employeeId, $id)
    {
        $sql = "UPDATE jss_resume_language_skills SET title=?,level=?,fk_employee_id=? WHERE id=?";
        $array = [];
        foreach ($languageSkill as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $array[] = $id;
        $this->doQuery($sql, $array);
    }


    //===============================
    //  PUT :                       
    //===============================

    function saveEducation($education, $employeeId)
    {

        $sql = "INSERT INTO jss_resume_educational_background (major_title,uni,fk_grade_id,average,start_year,end_year,fk_employee_id) VALUES (?,?,?,?,?,?,?) ";
        $array = [];
        foreach ($education as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $this->doQuery($sql, $array);
    }
    function saveJobExperience($jobExperience, $employeeId)
    {
        $sql = "INSERT INTO jss_resume_job_experience (job_title,org_name,start_year,end_year,fk_employee_id) VALUES (?,?,?,?,?) ";
        $array = [];
        foreach ($jobExperience as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $this->doQuery($sql, $array);
    }
    function saveLanguageSkill($languageSkill, $employeeId)
    {
        $sql = "INSERT INTO jss_resume_language_skills (title,level,fk_employee_id) VALUES (?,?,?) ";
        $array = [];
        foreach ($languageSkill as  $value) {
            $array[] = $value;
        }
        $array[] = intval($employeeId);
        $this->doQuery($sql, $array);
    }


    //===============================
    //  DELETE :
    //===============================
    function removeEducationResume($id)
    {
        $sql = "DELETE FROM jss_resume_educational_background WHERE id =?";
        $this->doQuery($sql, [$id]);
    }
    function removeJobExperience($id)
    {
        $sql = "DELETE FROM jss_resume_job_experience WHERE id =?";
        $this->doQuery($sql, [$id]);
    }
    function removeLanguageSkill($id)
    {
        $sql = "DELETE FROM jss_resume_language_skills WHERE id =?";
        $this->doQuery($sql, [$id]);
    }
    function removeEmployeeAvatar($id)
    {
        $sql = "UPDATE jss_resume SET avatar=? WHERE fk_employee_id=?";
        $this->doQuery($sql, [null, $id]);
    }
}
