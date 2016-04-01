<?php

//  global $basedir;
//  include($basedir . '/Conection/DB_Connect.php');
//  echo $basedir . '/Conection/DB_Connect.php';
require_once 'User.php';
require_once './AppConstants.php';
require_once 'DB_Connect.php';

if (isset(filter_input(INPUT_POST, AppConstants::$EMAIL)) && isset(filter_input(INPUT_POST, AppConstants::$PASSWORD))) {
    $user = new User(getdbh());
    $user_details = $user->checkPassword(filter_input(INPUT_POST, AppConstants::$EMAIL), filter_input(INPUT_POST, AppConstants::$PASSWORD));
    if (count($user_details) == 1) {

        $response["error"] = FALSE;
        $response[AppConstants::$UID] = $user_details[0]["ID"];
        $response["user"][AppConstants::$FIRST_NAME] = $user_details[0]["FNAME"];
        $response["user"][AppConstants::$LAST_NAME] = $user_details[0]["LNAME"];
        $response["user"][AppConstants::$ADDRESSLINE1] = $user_details[0]["ADDRESSLINE1"];
        $response["user"][AppConstants::$ADDRESSLINE2] = $user_details[0]["ADDRESSLINE2"];
        $response["user"][AppConstants::$CITY] = $user_details[0]["CITY"];
        $response["user"][AppConstants::$PHONE] = $user_details[0]["PHONE"];
        $response["user"][AppConstants::$STATE] = $user_details[0]["STATE"];
        $response["user"][AppConstants::$EMAIL] = $user_details[0]["EMAIL"];
        $response["user"][AppConstants::$CREDIT] = $user_details[0]["CREDIT_LIMIT"];
        echo json_encode($response);
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Invalid email or password";
        echo json_encode($response);
    }
}
   
