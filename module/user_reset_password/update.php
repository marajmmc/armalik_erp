<?php

include_once '../../libraries/lib/inclue_system_file.php';

$db = new Database();
$db2 = new Database();
$user_pass = $_POST["user_new_pass"];

$user_pass = stripQuotes(removeBadChars($user_pass));
$crypt_user_pass = md5(md5($user_pass));

$mSQL_task = "update $tbl" . "user_login set
	 user_pass='$crypt_user_pass'  
        where user_id='" . $_POST['rowID'] . "'
    ";

$mSQL = "update $tbl" . "password_reset_request set
	 reset_password='" . $_POST['user_new_pass'] . "',
	 status='Done'
        where user_id='" . $_POST['rowID'] . "'
    ";
if ($db2->open()) {
    $db2->query($mSQL_task);
    $db2->query($mSQL);
    echo "Save";
    $db2->freeResult();
}

?>