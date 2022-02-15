<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to welcome page
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("location: welcome.php");
    exit;
}

// Include the config file
require_once "config.php";

// Initialize the variables with empty values
$username = $password = "";
$username_err = $password_err = "";

// Process logic when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    }else {
        $username = trim($_POST["username"]);
    }

    // Check if the password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    }else {
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($username_err) && empty($password_err)){

        // Prepare sql statement to fetch data from the database
        $statement = $pdo->prepare("SELECT id, username, password FROM users WHERE username = '{$username}'");

        // Attemp to execute prepared statement
        $statement->execute();

        // Fetch result set and store to variable
        $login = $statement->fetch(PDO::FETCH_OBJ);

        // Check if the username exists, if yes then verify password
        if(!empty($login)) {
            if(password_verify($password, $login->password))
            {
                // Password is correct, start a new session
                session_start();

                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $login->id;
                $_SESSION["username"] = $login->username;

                // Redirect to welcome page
                header("location: welcome.php");
            }else {
                // Display an error message if password is not valid
                $password_err = "The password you entered was not valid.";
            }
        }else{
            // Display an error message if username does not exists
            $username_err = "No account found with that username.";
        }
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
        <h1 class="text-2xl mb-2">Sign in:</h1>
        <div class="bg-gradient-to-r from-emerald-400 to-emerald-700 h-1 rounded-full mb-2"></div>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="mb-2">
                <div class="mb-1">
                    <label for="username" class="text-sm font-semibold">Username</label>
                </div>
                <div>
                    <input type="text" name="username" class=" w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:shadow-outline
                        <?=(!empty($username_err)) ? 'border-red-500' : ''; ?>">
                    <span class="text-red-500 text-sm font-semibold"><?=$username_err;?></span>
                </div>
            </div>
            <div class="mb-4">
                <div class="mb-1">
                    <label for="password" class="text-sm font-semibold">Password</label>
                </div>
                <div>
                    <input type="password" name="password" class=" w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:shadow-outline
                        <?= (!empty($password_err)) ? 'border-red-500' : ''; ?>">
                    <span class=" text-red-500 text-sm font-semibold"><?=$password_err;?></span>
                </div>
            </div>
            <div class="space-x-1 flex items-center mb-2">
                <button class=" px-8 py-2 bg-teal-600 border border-teal-600 text-white rounded-md hover:bg-teal-700
                    focus:bg-teal-800">Submit</button>
                <button type="reset"
                    class="px-8 py-2 border border-gray-500 rounded-md hover:border-gray-700 focus:border-gray-900">Reset</button>
            </div>
            <div class="text-sm">
                <h1>Don't have an account yet? <a href="register.php"
                        class="text-teal-600 font-semibold hover:text-teal-800 hover:underline">Register here</a></h1>
            </div>
        </form>
    </div>
</body>

</html>