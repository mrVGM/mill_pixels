<?php

$login_controller = '/\A\/login\/{0,1}\z/';
$pixels_controller = '/\A\/pixels(\/\d+){0,1}\/{0,1}\z/';
$users_controller = '/\A\/users\/{0,1}\z/'; 

require_once('../authorize.php');

if (preg_match($login_controller, $_SERVER['REQUEST_URI']) === 1) {
    require('../controllers/logins/logins.php');
}
else if (preg_match($pixels_controller, $_SERVER['REQUEST_URI']) === 1) {
    if (authorize()) {
        require('../controllers/pixels/pixels.php');
    }
    else {
        header('Location: ' . '/login', true, 301);
        die();
    }
}
else if (preg_match($users_controller, $_SERVER['REQUEST_URI']) === 1) {
    require('../controllers/users/users.php');
}
else {
    
    if (authorize()) {
        require('../html/main_signed.php');
    }
    else {
        require('../html/main_unsigned.php');
    }
}

?>