<?php

class UsersModel {
    
    private $userInfo= array();
    
    /**
     * Get all users from database [array of rows]
     * @param string $extra
     * @return array2D
     */    
    public function get ($extra=''){
        $users= array();
        System::get('db')->Execute("SELECT * FROM `users` {$extra}");
        if (System::get('db')->AffectedRows()>0)
            $users= System::get('db')->GetRows();
        return $users;
    }
    
    /**
     * Get one user [row] from the database by ID
     * @param integer $id
     * @return array
     */
    public function getById ($id){
        $id= (int)$id;
        $user= $this->get("WHERE `id`= $id");
        if (count($user)>0)
            return $user[0];
    }
    
    /**
     * Get the last specified number of users [array of rows]
     * each row represents one user data
     * @param integer $num
     * @return array2D
     */
    public function getLast ($num){
        $num= (int)$num;
        return $this->get("ORDER BY `id` DESC LIMIT $num");
    }
    
    /**
     * Get list of admin users
     * @return array2D
     */
    public function getAdmin (){
        return $this->get("WHERE `is_admin`= 1");
    }
    
    /**
     * Get list of new unapproved users
     * @return array2D
     */
    public function getNew(){
        return $this->get("WHERE `is_approved`=0");
    }
    
    /**
     * Check if the user of this ID is admin or not
     * @param type $id
     * @return boolean
     */
    public function isAdmin ($id){
        $id= (int)$id;
        $user= $this->getById($id);
        if (count($user)>0){
            if ($user['is_admin']==1)
                return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Check if the user of this ID is approved or not
     * @param type $id
     * @return boolean
     */
    public function isApproved ($id){
        $id= (int)$id;
        $user= $this->getById($id);
        if (count($user)>0){
            if ($user['is_approved']==1)
                return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Insert new user in the end of the database
     * @param array $data
     * @return boolean
     */
    public function add ($data){
        if (System::get('db')->Insert('users', $data))
            return TRUE;
        return FALSE;
    }
    
    /**
     * Update specific user's data by his ID
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public function update ($id,$data){
        $id= (int)$id;
        if (System::get('db')->Update('users', $data, "WHERE `id`= $id"))
            return TRUE;
        return FALSE;
    }
    
    /**
     * Delete single user by ID
     * @param integer $id
     * @return boolean
     */
    public function delete ($id){
        $id= (int)$id;
        if (System::get('db')->Delete('users',"WHERE `id`=$id"))
            return TRUE;
        return FALSE;
    }
    
    /**
     * Reset specific user's password
     * @param integer $id
     * @param string $password
     * @return boolean
     */
    public function resetPassword ($id, $password){
        $id= (int)$id;
        if (System::get('db')->Update('users', array('password'=> $password), "WHERE `id`= $id"))
            return TRUE;
        return FALSE;
    }
    
    /**
     * Approve new users by admin-----NOTIFICATION MAY BE USED IN THE FUTURE
     * @param integer $id
     * @return boolean
     */
    public function approve ($id){
        $id= (int)$id;
        if (System::get('db')->Update('users', array('is_approved' => 1), "WHERE `id`= $id")) {
            $admin = $this->getById($id);
            mail($admin['email'], "Approval", "An admin approved you, now you have access to the home go to log in. \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Assign user of specific ID as an admin-----NOTIFICATION MAY BE USED IN THE FUTURE
     * @param integer $id
     * @return boolean
     */
    public function setAdmin ($id){
        $id= (int)$id;
        if (System::get('db')->Update('users', array('is_admin' => 1), "WHERE `id`= $id")) {
            $admin = $this->getById($id);
            mail($admin['email'], "Administrative message", "An admin set you as an admin for the home, now you can control the admin panel and approve new users. \r\n \r\n \r\n Regards, \r\n Connectvia Home", "From: admin@connectvia.net");
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Delete admin from adminstration panel
     * @param integer $id
     * @return boolean
     */
    public function unsetAdmin ($id){
        $id= (int)$id;
        if (System::get('db')->Update('users', array('is_admin'=> 0) ,"WHERE `id`= $id"))
            return TRUE;
        return FALSE;
    }


    /**
     * Login mobile user
     * Check if the user of given username or email and password is found in database
     * If found then generate token and update token in the database and update userInfo using new token and firebase token
     * Else return false [don't login]
     * @param string $username
     * @param string $password
     * @param string $ftoken
     * @return boolean
     */
    public function login($username, $password, $ftoken){
        $user= $this->get("WHERE (`username` ='$username' OR `email`='$username') AND `password`= '$password'");
        if (count($user)>0){
            $user= $user[0];
            $token= $this->genToken();
            $user['token']= $token;
            $user['ftoken']= $ftoken;
            $id= $user['id'];
            System::get('db')->Update('users', array('token'=>$token, 'ftoken'=>$ftoken), "WHERE `id`= $id");
            $this->userInfo= $user;
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Login web user
     * Check if the user of given username and password is found in database
     * If found then generate token and update token in the database and update userInfo using new token and firebase token
     * Else return false [don't login]
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function webLogin($email, $password){
        $user= $this->get("WHERE (`email`='$email') AND `password`= '$password'");
        if (count($user)>0){
            if ($user[0]['is_approved']){
                $this->userInfo= $user[0];
                return TRUE;
            }
        }
        return FALSE;
    }

        /**
     * Returns the data of the logged in user to store in session or send token [one row]
     * @return array
     */
    public function getUserInfo(){
        return $this->userInfo;
    }
    
    /**
     * Generate time based random token for the user
     * @return string
     */
    public function genToken (){
        $token = openssl_random_pseudo_bytes(8);
        $token = bin2hex($token).date("YmdHis");
        return $token;
    }
    
    /**
     * Check if the token provided by the mobile user is found in database
     * @param string $token
     * @return boolean
     */
    public function chkToken ($token){
        $user= $this->get("WHERE `token` = '$token'");
        if (count($user)>0){
            $user= $user[0];
            $this->userInfo= $user;
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Check if this data is found in this column in the `users` table
     * Used while registration to check if the username or email is used [if used it is not available
     * @param string $column
     * @param string $data
     * @return boolean
     */
    public function isFound ($column, $data){
        $user= $this->get("WHERE `{$column}` = '$data'");
        if (count($user)>0)
            return TRUE;
        return FALSE;
    }
    
    /**
     * Get list of devices firebase tokens
     * @return array2D
     */
    public function getDevices (){
        $users= $this->get("WHERE `ftoken` IS NOT NULL");
        $devices= array();
        if (count($users)>0){
            foreach ($users as $user) {
                array_push($devices, $user['ftoken']);
            }
        }
        return $devices;
    }
}