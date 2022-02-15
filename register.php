<?php

// Include config file
require_once "config.php";

// Initialize variables with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing logic when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    }else{
        // Statement to check if username is already taken
        $statement = $pdo->prepare("SELECT id FROM users WHERE username = '{$username}'");

        // Execute the prepared statement
        $statement->execute();

        $validate = $statement->fetch(PDO::FETCH_OBJ);
        if(!empty($validate)){
            $username_err = "Username is already taken.";
        }else{
            $username = trim($_POST["username"]);
        }
    }

    //Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    }elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have alteast 6 characters.";
    }else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password = "Please confirm password.";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $statement = $pdo->prepare("INSERT INTO users(username, password) VALUES('{$username}', '{$password}')");

        // Execute prepared statement
        $statement->execute();

        // Redirect to login page
        header("location: login.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Auth PDO</title>
    <link rel="stylesheet" href="public/css/app.css">
</head>

<body class="h-screen w-full flex items-center justify-center bg-gray-200">
    <div class="w-full max-w-lg shadow-lg rounded-xl bg-white px-6 py-4">
        <h1 class="text-2xl mb-2">Sign Up</h1>
        <div class="bg-gradient-to-r from-teal-400 to-teal-700 h-1 rounded-full mb-2"></div>
        <form action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="mb-2">
                <div class="mb-1">
                    <label for="username" class="text-sm font-semibold">Username</label>
                </div>
                <div>
                    <input type="text" name="username"
                        class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:shadow-outline <?=(!empty($username_err)) ? 'border-red-500' : '';?> ">
                    <span class="text-red-500 text-sm font-semibold"><?= $username_err; ?></span>
                </div>
            </div>
            <div class="mb-2">
                <div class="mb-1">
                    <label for="password" class="text-sm font-semibold">Password</label>
                </div>
                <div>
                    <input type="password" name="password"
                        class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:shadow-outline <?=(!empty($password_err)) ? 'border-red-500' : '';?> ">
                    <span class="text-red-500 text-sm font-semibold"><?= $password_err; ?></span>
                </div>
            </div>
            <div class="mb-4">
                <div class="mb-1">
                    <label for="confirm_password" class="text-sm font-semibold">Confirm Password</label>
                </div>
                <div>
                    <input type="password" name="confirm_password"
                        class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:shadow-outline <?=(!empty($confirm_password_err)) ? 'border-red-500' : '';?> ">
                    <span class="text-red-500 text-sm font-semibold"><?= $confirm_password_err; ?></span>
                </div>
            </div>
            <div class="space-x-1 flex items-center mb-2">
                <button type="submit"
                    class="px-8 py-2 bg-teal-600 border border-teal-600 text-white rounded-md hover:bg-teal-700 focus:bg-teal-800">Submit</button>
                <button type="reset"
                    class="px-8 py-2 border border-gray-500 rounded-md hover:border-gray-700 focus:border-gray-900">Reset</button>
            </div>
            <div class="text-sm">
                <h1>Already have an account? <a href="login.php"
                        class="text-teal-600 font-semibold hover:text-teal-800 hover:underline">Login here.</a></h1>
            </div>
        </form>
    </div>
</body>

</html>