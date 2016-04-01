<?php

require_once 'User.php';
require_once 'DB_Connect.php';
if (isset($_GET['email'])) {
    $user = new User(getdbh());

    $checkemail = $user->checkEmail($_GET['email']);


    if ($checkemail == false) {
        $result = $user->addUser($_GET['email'], $_GET['password'], $_GET['fname'], $_GET['lname']);
        if ($result) {
            $response["error"] = FALSE;
            $response["error_msg"] = " User successfully registered. Try login now!";

            echo json_encode($response);
        } else {
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown Error";
            echo json_encode($response);
        }
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Email Already Exist";
        echo json_encode($response);
    }
}
