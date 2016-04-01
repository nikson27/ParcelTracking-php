<?php

require_once './AppConstants.php';
require_once 'User.php';
require_once 'DB_Connect.php';

if (isset(filter_input(INPUT_POST, AppConstants::$UID))) {
    $user = new User(getdbh());
    $checkUID = $user->checkID(filter_input(INPUT_POST, AppConstants::$UID));

    if (!$checkUID) {

        $response["error"] = TRUE;
        $response["error_msg"] = "Invalid User ID";
        echo json_encode($response);
    } else {
        $id = filter_input(INPUT_POST, AppConstants::$UID);
        $fname = filter_input(INPUT_POST, AppConstants::$FIRST_NAME);
        $lname = filter_input(INPUT_POST, AppConstants::$LAST_NAME);
        $phone = filter_input(INPUT_POST, AppConstants::$PHONE);
        $city = filter_input(INPUT_POST, AppConstants::$CITY);
        $state = filter_input(INPUT_POST, AppConstants::$STATE);
        $addrln1 = filter_input(INPUT_POST, AppConstants::$ADDRESSLINE1);
        $addrln2 = filter_input(INPUT_POST, AppConstants::$ADDRESSLINE2);
        $credit = filter_input(INPUT_POST, AppConstants::$CREDIT);


        $result = $user->updateUser($id, $fname, $lname, $phone, $city, $state, $addrln1, $addrln2, $credit);
        if ($result) {
            $response["error"] = FALSE;
            $response["error_msg"] = "Update Succesfully";
            echo json_encode($response);
        } else {
            $response["error"] = TRUE;
            $response["error_msg"] = "Error, please try again later";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Error, please try again later";
    echo json_encode($response);
}
