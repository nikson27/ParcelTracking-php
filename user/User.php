<?php

class User {

    private $db;

    function __construct($db) {

        $this->db = $db;
    }

//ok
    /**
     * 
     * Return ID and TIP if password is corect <br>

     * @param string $email <br>
     * @param string $pass <br>
     * @return array or bool
     */
    public function checkPassword($email, $pass) {
        $result = array();
        $pass = md5($pass);

        $stmt = $this->db->prepare('SELECT *'
                . ' FROM `user`'
                . ' WHERE `EMAIL`=:email '
                . 'AND`PASSWORD`=:pass');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }

            return $result;
        } else {
            return false;
        }
    }

    //ok
    /**
     * 
     * Return user details <br>
     * Campuri: <br>
     * NUME <br>
     * PRENUME <br>
     * EMAIL <br>
     * TIP <br>
     * DATAADAUGARII <br>
     * STATUS 
     * @param int $user_id <br>
     * @return array
     */
    public function getUserDetails($user_id) {

        $result = array();
        $stmt = $this->db->prepare('SELECT '
                . '`EMAIL`, ' . '`FNAME`,' . ' `LNAME`,' . ' `REGISTER_DATE`, ' . '`ZIPCODE`, `ADDRESSLINE1`, '
                . '`ADDRESSLINE2`, `'
                . 'CITY`, '
                . '`STATE`, '
                . '`PHONE`, '
                . '`CREDIT_LIMIT` '
                . 'FROM '
                . '`user` WHERE `ID`=:id');
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        if (count($result) == 1) {
            return $result;
        } else {
            return false;
        }
    }

    //ok
    /**
     * 
     * Set user email <br>
     * @param int $id
     * @param String $email
     * @return bool
     */
    public function setEmail($id, $email) {

        $stmt = $this->db->prepare('UPDATE `parceltracking`.`User`
                                SET
                                `EMAIL` =:email
                                WHERE `ID` =:id;');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute() ? true : false;
    }

//ok
    /**
     * 
     * Set user email <br>
     * @param int $id
     * @param String $email
     * @param String $fname
     * @param String $lname
     * @param String $phone
     * @param String $city
     * @param String $state
     * @param String $addrln1
     * @param String $addrln2
     * @param int $credit
     * @param String $zipcode

     * @return bool
     */
    public function updateUser($id, $fname, $lname, $phone, $city, $state, $addrln1, $addrln2, $credit) {

        $stmt = $this->db->prepare('UPDATE `user` SET 
            `FNAME`=:fname,`LNAME`=:lname,
            `ADDRESSLINE1`=:addrln1,`ADDRESSLINE2`=:addrln2,
            `CITY`=:city,`STATE`=:state,`PHONE`=:phone,`CREDIT_LIMIT`=:credit
            WHERE `ID`=:id ');
        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
        $stmt->bindParam(':addrln1', $addrln1, PDO::PARAM_STR);
        $stmt->bindParam(':addrln2', $addrln2, PDO::PARAM_STR);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->bindParam(':state', $state, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':credit', $credit, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute() ? true : false;
    }

//ok
    /**
     *
     * Add new user <br>
     * @param String $email
     * @param String $password
     * @param String $nume
     * @param String $prenume
     * @param String $tip
     * @return user_id or false
     */
    public function addUser($email, $parola, $nume, $prenume) {
        $parola = md5($parola);
        $stmt = $this->db->prepare('  INSERT INTO `user` '
                . '(`EMAIL`, '
                . '`PASSWORD`, '
                . '`FNAME`, '
                . '`LNAME`, '
                . '`REGISTER_DATE`)'
                . ' VALUES '
                . '(:email,'
                . ':parola,'
                . ':nume,'
                . ':prenume, '
                . 'now());');



        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':parola', $parola);
        $stmt->bindParam(':nume', $nume, PDO::PARAM_STR);
        $stmt->bindParam(':prenume', $prenume, PDO::PARAM_STR);
        return $stmt->execute() ? true : false;
    }

//ok
    /**
     * 
     * Check user email <br>
     * @param String $email
     * @return bool or userID
     */
    public function checkEmail($email) {



        $stmt = $this->db->prepare('SELECT
                                        `user`.`ID`
                                    FROM `user`
                                    WHERE `user`.`EMAIL`=:email;');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * Check user id <br>
     * @param int $id
     * @return bool
     */
    public function checkID($id) {



        $stmt = $this->db->prepare('SELECT
                                        `user`.`ID`
                                    FROM `user`
                                    WHERE `user`.`ID`=:id;');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * Set token for new pass and send email <br>
     * @param int $id
     * @param String $email
     * @return $token or false
     */
    public function setRecover($id, $email) {

        $key1 = uniqid($id, true);
        $key = str_replace(".", "", $key1);

        $stmt = $this->db->prepare('UPDATE `parceltracking`.`User`
                                    SET
                                    `STATUS` =\'NEW_PASS\',
                                    `PAROLA` =\'\',
                                    `FORGOT_PASS_TOKEN` =:key,
                                    `FORGOT_PASS_EXPIRATION_DATE` =now()+INTERVAL 2 DAY
                                    WHERE `ID` =:id');

        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $key;
        } else {
            return false;
        }
    }

    /**
     * 
     * Set token for new pass and send email <br>
     * @param int $id
     * @param String $email
     * @return $token or false
     */
    public function newUserToken($id) {

        $key1 = uniqid($id, true);
        $key = str_replace(".", "", $key1);

        $stmt = $this->db->prepare('UPDATE `parceltracking`.`User`
                                    SET
                                    `STATUS` =\'NO_CONFIRMATION\',
                                    `FORGOT_PASS_TOKEN` =:key,
                                    `FORGOT_PASS_EXPIRATION_DATE` =now()+INTERVAL 2 DAY
                                    WHERE `ID` =:id');

        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $key;
        } else {
            return false;
        }
    }

    /**
     * 
     * Check if token is corect and not expired <br>
     * @param String $token
     * @return array or bool
     */
    public function checkToken($token) {
        $result = array();

        $stmt = $this->db->prepare('SELECT 
                                        `User`.`ID`,
                                        `User`.`EMAIL`
                                    FROM 
                                        `parceltracking`.`User`
                                    WHERE 
                                        FORGOT_PASS_TOKEN=:token
                                    AND now()<(select FORGOT_PASS_EXPIRATION_DATE from `parceltracking`.`User` where FORGOT_PASS_TOKEN=:token )
                                    AND `User`.`STATUS`=\'NEW_PASS\';
                                    ');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);



        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            return 'false';
        }
    }

    /**
     * 
     * Check if token is corect and not expired <br>
     * @param String $token
     * @return array or bool
     */
    public function checkConfirmationToken($token) {
        $result = array();

        $stmt = $this->db->prepare('SELECT 
                                        `User`.`ID`,
                                        `User`.`EMAIL`
                                    FROM 
                                        `parceltracking`.`User`
                                    WHERE 
                                        FORGOT_PASS_TOKEN=:token
                                    AND now()<(select FORGOT_PASS_EXPIRATION_DATE from `parceltracking`.`User` where FORGOT_PASS_TOKEN=:token )
                                    AND `User`.`STATUS`=\'NO_CONFIRMATION\';
                                    ');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);



        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } else {
            return 'false';
        }
    }

    /**
     * 
     * Set new passwotd <br>
     * @param String $password
     * @return bool
     */
    public function newPassword($password, $id) {
        $password = md5($password);

        $stmt = $this->db->prepare('UPDATE `parceltracking`.`User`
                                            SET
                                            `PAROLA` =:pass,
                                            `STATUS` =\'ACTIV\',
                                            `FORGOT_PASS_TOKEN` =\'\'
                                            WHERE `ID` =:id;');
        $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id);



        return $stmt->execute() ? true : false;
    }

    /**
     * 
     * Delete User <br>
     * @param int $id
     * @return bool
     */
    public function deleteUser($id) {

        $stmt = $this->db->prepare('DELETE from `parceltracking`.`User`
                                    WHERE `ID` =:id;');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute() ? true : false;
    }

}
