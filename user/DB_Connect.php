<?php

function getdbh() {
    if (!isset($GLOBALS['dbh']))
        try {
            //$GLOBALS['dbh'] = new PDO('sqlite:'.APP_PATH.'db/kissmvc.sqlite');
            // $GLOBALS['dbh'] = new PDO('mysql:host=10.224.15.13;dbname=ULBSPlatform', 'admin', 'ebsacademy2014');
            $GLOBALS['dbh'] = new PDO('mysql:host=localhost;dbname=parceltracking', 'root', '');
            //$GLOBALS['dbh'] = new PDO('mysql:host=mysql17.000webhost.com;dbname=a3652714_parcel', 'a3652714_nik', 'nik200893');
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    return $GLOBALS['dbh'];
}
