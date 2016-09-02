<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once('../controllers/users/users_html.php');
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    require_once('../db/repository.php');
    
    if ($username && $password && create_user($username, $password)) {    
        header('Location: ' . '/login', true, 302);
        die();
    }
    else {
        require_once('../controllers/users/try_again.php');
    }
}
else {
    header('Location: ' . '/', true, 302);
    die();
}

?>