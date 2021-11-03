<?php

class model_employer extends Model
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
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,job_descriptions,salary,workforce_num,work_experience,military_status,degree_of_education,gender,age_range,type_of_cooperation,needed_skills,company_intro,expire_date ,jss_companies.name as companyName,logo,date_of_establishment,num_of_staff,site_address,jss_companies.id as companyId,jss_provinces.name as provinceName,jss_cities.name as cityName,jss_job_categories.title as catTitle  
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        LEFT JOIN jss_cities ON jss_jobs.fk_city_id = jss_cities.id 
        LEFT JOIN jss_job_categories ON jss_jobs.fk_category_id = jss_job_categories.id  
        WHERE jss_jobs.id = ?";

        $result = $this->doSelect($sql, [$jobId]);
        $result[0]['isSaved'] = $this->checkJobSaved($jobId, $empId)[0]['isJobSaved'];
        $result[0]['expire_date'] = $this->dateDiffInDays($result[0]['expire_date'], $now);
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
        sleep(1);
        return $result;
    }


    function getSimilarPositions($jobId, $empId)
    {
        $sql =
            "SELECT jss_jobs.id as jobId,jss_jobs.title as jobTitle,salary,jss_companies.name as companyName,logo,jss_companies.id as companyId,jss_provinces.name as provinceName 
        FROM jss_jobs  
        LEFT JOIN jss_companies ON jss_jobs.fk_company_id = jss_companies.id  
        LEFT JOIN jss_provinces ON jss_jobs.fk_province_id = jss_provinces.id 
        WHERE jss_jobs.id <> ?
        LIMIT 4";
        $result = $this->doSelect($sql, [$jobId]);
        foreach ($result as $key => $value) {
            //  print_r($value['jobId']);
            $result[$key]['isSaved'] = $this->checkJobSaved($value['jobId'], $empId)[0]['isJobSaved'];
        }
        // print_r($result);
        return $result;
    }

    //===============================
    //  POST :
    //===============================

    function addJobToSaved($jobId, $empId)
    {
        $sqlSave = "INSERT INTO jss_saved_jobs (fk_job_id,fk_employee_id) VALUES (?,?)";
        $result = $this->doQuery($sqlSave, [$jobId, $empId]);
        return $result;
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
}
