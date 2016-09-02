<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require ('../controllers/logins/login.php');
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    require_once('../db/repository.php');
    
    if ($username && $password) {
        $tok = create_login($username, $password);
        if ($tok) {
            setcookie("username", $username);
            setcookie("token", $tok);
            header('Location: ' . '/', true, 302);
            die();
        }
        else {
            require("../controllers/logins/try_again.php");
        }
    }
    else {
        require('../controllers/logins/try_again.php');
    }
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $username = $_COOKIE['username'];
    
    if ($username) {
        require('../db/repository.php');
        delete_login($username);
        setcookie('username', '', 0);
        setcookie('token', '', 0);
    }
    
}
else {
    header('Location: ' . '/', true, 302);
    die();
}

?>