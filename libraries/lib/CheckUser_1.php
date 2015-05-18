<?php

@session_start();
session_unset();
session_destroy();
session_start();
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
require_once("config.inc.php");
require_once("database.inc.php");
require_once("functions.inc.php");

ob_start(); // Turn on output buffering
$sessionid = $_SESSION['session_id'];
system('ipconfig /all'); //Execute external program to display output
$mycom = ob_get_contents(); // Capture the output into a variable
ob_clean(); // Clean (erase) the output buffer

$findme = "Physical";
$pmac = strpos($mycom, $findme); // Find the position of Physical text
$mac = substr($mycom, ($pmac + 36), 17); // Get Physical Address
//echo $mac;

$tbl = _DB_PREFIX;
$db = new Database();
$time = $db->getCurrentDateTime();
$user_name = $_REQUEST["user_session_login"];
$user_pass = $_REQUEST["user_session_password"];
if (!$db->open()) {
    echo $db->open();
} else {
    $user_name = stripQuotes(removeBadChars($user_name));

    $user_pass = stripQuotes(removeBadChars($user_pass));

    $crypt_user_pass = md5(md5($user_pass));


    //echo $user_name;
    //print ctype_alnum ($user_name); 
    //die();

    if (strlen($user_pass) < 6) {

        echo $msg = "provided password too short!";
        //break;
    } else {

        //echo $user_name;
        //		echo $user_pass;

        $sql = "SELECT 
                        $tbl" . "user_login.id,
                        $tbl" . "user_login.user_id,
                        $tbl" . "user_login.ei_id,
                        $tbl" . "user_login.unit_id,
                        $tbl" . "user_login.designation,
                        $tbl" . "user_login.user_group_id,
                        $tbl" . "user_login.emp_name,
                        $tbl" . "user_login.user_name,
                        $tbl" . "user_login.user_pass,
                        $tbl" . "user_login.user_tmp_pass,
                        $tbl" . "user_login.user_expire,
                        $tbl" . "user_login.user_status,
                        $tbl" . "user_login.create_date,
                        $tbl" . "employee_info.ei_image_url
                    FROM `$tbl" . "user_login` 
                    LEFT JOIN $tbl" . "employee_info ON $tbl" . "employee_info.ei_id=$tbl" . "user_login.ei_id
                    WHERE user_name = '" . $user_name . "' AND user_pass='" . $crypt_user_pass . "'";

        $db->query($sql); // or die(mysql_error());
        $num_row = $db->numRows();
        if ($num_row > 0) {
            $row = $db->fetchArray();
            if ($row['user_status'] != "Active") {
                echo $msg = "Your user ID De-Active";
            } else {
                /* echo $msg = 'success'; exit; */
                
                $user_id = $row['user_id'];
                $ei_id = $row['ei_id'];
                $unit_id = $row['unit_id'];
                $_SESSION['user_id'] = $user_id;
//            $_SESSION['user_type'] = $user_type;
                $_SESSION['ei_id'] = $ei_id;
                $_SESSION['unit_id'] = $unit_id;
                $_SESSION['designation'] = $row['designation'];
                $_SESSION['emp_name'] = $row['emp_name'];
                $_SESSION['ei_image_url'] = $row['ei_image_url'];
                $_SESSION['logged'] = 'yes';
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_group_id'] = $row['user_group_id'];
                $_SESSION['page_title'] = '::. OFFICE AUTOMATION SYSTEM .::';

                //@$run_date = date('Y-m-d:h:m:s');

                $ip = getenv("REMOTE_ADDR");
                $db->query("INSERT INTO `$tbl" . "user_sync` 
                (
                `ei_id`,
                `unit_id`,
                `user_name`,
                ip,
                mac,
                session_id,
                create_date
                ) 
                values
                (
                '$ei_id',
                '$unit_id',
                '$user_name',
                '$ip',
                '$mac',
                '$sessionid',
                '$time'
                )"
                );
                echo $msg = "Please Wait ...";
            }
        } else {
            echo $msg = 'Incorrect user name or password';
        }
    }
}
?>
    