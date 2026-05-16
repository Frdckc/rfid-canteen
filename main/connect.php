<?php
//error_reporting(0);
if (!isset($_SESSION)) {
    session_start();
}

date_default_timezone_set('Asia/Manila');
$dt = date('Y-m-d H:i:s');
$dtNow = date('Y-m-d');
 
$dtl = date('Y-m-d H:i:sa');
 

// if(time()-$_SESSION['time']>20) {
// unset($_SESSION['time']);

// $message = "Database Backup created.";
// 		echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
// 	 } 
// else {
// $_SESSION['time']=time();//updating with latest timestamp
// echo$_SESSION['time'];
// }

$db_host = 'localhost'; 

$db_user = 'root';
$db_pass = '';
$db_database = 'u410635593_rfidcanteen';
 


$db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_database, $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$system_title = "Canteen Management System";
date_default_timezone_set('Asia/Manila');
$dt = date('Y-m-d H:i:s');

function dob ($birthday){
    list($day,$month,$year) = explode("/",$birthday);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;
    return $year_diff;
}

function addSpace($no)
{
    $data = '';
    for ($i = 0; $i < $no; $i++) {
        $data = $data . "&nbsp;";
    }
    return $data;
}


 

function format_date($val)
{
    if ($val <> '') {
        $val = date('M. d, Y ', strtotime($val));
    }
    return $val;
}

function format_time($val)
{
    if ($val <> '') {
        $val = date('g:i A', strtotime($val));
    }
    return $val;
}

function format_datetime($val)
{
    if ($val <> '') {
        $val = date('M. d, Y g:i A', strtotime($val));
    }
    return $val;
}


function peso_format($amount)
{
    $amount = number_format($amount, 2, ".", ","); // returns: 1,23
    if ($amount == '0.00') {
        $amount = "";
    }
    //$amount = "<a class='text-align: right;'>".$amount."</a>";

    return $amount;
}



(isset($_SESSION['userID']) ? $user_id = $_SESSION['userID'] : '');
(isset($_SESSION['role']) ? $user_role = $_SESSION['role'] : '');



 ?>