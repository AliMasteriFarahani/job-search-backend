<?php

class Model
{

    public static $conn = '';
    public $totalMenu = array();
    public $query='';

    function __construct()
    {   date_default_timezone_set('Iran');
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'jobout_job-search';
        $attr = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        self::$conn = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname, $username, $password, $attr);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // if (function_exists('jdate') == false) {
        //     require('public/jdf/jdf.php');
        // }

    }

     function select($tblName,$value){
        $this->query = "SELECT $value FROM $tblName";
        return $this;
    }

    function leftJoin($tblName,$condiotion='')
    {
        $this->query .= " LEFT JOIN $tblName ON  $condiotion";
        return $this;
    }
    function where($column,$compareType,$condiotion='')
    {
        $this->query .= " WHERE $column $compareType $condiotion";
        return $this;
    }
    
     function sql(){
        return $this->query;
    }

    function doSelect($sql, $values = array(), $fetch = '', $fetchStyle = PDO::FETCH_ASSOC)
    {

        $stmt = self::$conn->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();
        if ($fetch == '') {
            $result = $stmt->fetchAll($fetchStyle);
        } else {
            $result = $stmt->fetch($fetchStyle);
        }
        return $result;
    }

    function doQuery($sql, $values = array())
    {

        $stmt = self::$conn->prepare($sql);

        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();

    }


   

    public static function sessionInit()
    {

        @session_start();
    }

    public static function sessionSet($name, $value)
    {

        $_SESSION[$name] = $value;
    }

    public static function sessionGet($name)
    {

        if (isset($_SESSION[$name])) {

            return $_SESSION[$name];
        } else {
            return false;
        }
    }


    // public static function jaliliDate($format = 'Y/n/j')
    // {

    //     $date = jdate($format);
    //     return $date;
    // }

    // public static function jaliliToMiladi($jalili, $format = '/')
    // {

    //     $jalili = explode('/', $jalili);
    //     $year = $jalili[0];
    //     $month = $jalili[1];
    //     $day = $jalili[2];
    //     $date = jalali_to_gregorian($year, $month, $day);
    //     $date = implode($format, $date);
    //     $date = new DateTime($date);
    //     $date = $date->format('Y/m/d');

    //     return $date;
    // }

    // public static function MiladiTojalili($miladi, $format = '/')
    // {

    //     $miladi = explode('/', $miladi);
    //     $year = $miladi[0];
    //     $month = $miladi[1];
    //     $day = $miladi[2];
    //     $date = gregorian_to_jalali($year, $month, $day);
    //     $date = implode($format, $date);
    //     return $date;
    // }

    function dateDiffInDays($date1, $date2) 
{
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);
      
    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return abs(round($diff / 86400));
}
  function howManayDayAgo($createdAt){

        // day : 0 --> today
        // day : 1 --> yesterday
        // day : x --> x days ago

        $oneDay = 24 * 60 * 60;
        $d = mktime(00, 00);
        $midd = date("Y-m-d H:i", $d);
        $midd = strtotime($midd);
       // $createdAt = time() -  35 * 60 *60;
        $nowT = time();
        // print_r('created_at :'.$createdAt .' -- ' . date("Y-m-d H:i", $createdAt)."\n");
        // print_r('now :'.$nowT .' -- ' . date("Y-m-d H:i", $nowT)."\n");
        // print_r('mid :'.$midd .' -- ' . date("Y-m-d H:i", $midd)."\n");
  
        $diff =   $nowT - $createdAt;
        $dayNum = abs(round($diff / $oneDay));
        $hour = ($diff%$oneDay)/(60*60);
        $minutes = (($diff%$oneDay)%(60*60))/(60);
        // print_r('d: '.abs(round($dayNum))."\n");
        // print_r('h: '.abs(floor($hour))."\n");
        // print_r('m: '.abs(round($minutes))."\n");
       
        if ($diff <= $oneDay) {
            if ($createdAt < $midd) {
                // yesterday : 1
                $dayNum = 'yesterday';
            } elseif ($createdAt >= $midd) {
                // today : 0
                $dayNum = 'today';
            }
        }elseif($diff > $oneDay){
             $createdAt = date("Y-m-d 00:00", $createdAt);
             $nowT = date("Y-m-d 00:00", $nowT);
            //  print_r("cr".$createdAt."\n");
            //  print_r("cr".$nowT."\n");
             $diff =   strtotime($nowT) - strtotime($createdAt);
             $dayNum = abs(round($diff / $oneDay));
            // print_r("dayNum".$dayNumm."\n");
            // return $dayNum;
        }
        return $dayNum;



  }


}








