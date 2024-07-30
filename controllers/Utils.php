<?php

    require_once('../models/User.php');

    function registerUser() {

        $user = new User();

        //Check if same username is not present in db

        //Validate & assign inputs here
        $user->username = "";
        $user->password_hash = "";
        $user->email = "";
        $user->first_name = "";
        $user->last_name = "";
        $user->created_at = "";
        $user->updated_at = "";

        $user->create();

        //Set the userId in session

    }

    

?>