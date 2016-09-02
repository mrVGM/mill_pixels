<?php

if (preg_match('/\A\/pixels\/{0,1}\z/', $_SERVER['REQUEST_URI']) === 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['x'] && $_POST['y'] && $_POST['w'] && $_POST['h']) {
            require_once("../db/repository.php");
            require_once("../authorize.php");
            $username = authenticate();
            $res = create_pixels($username, intval($_POST['x']), intval($_POST['y']), intval($_POST['w']), intval($_POST['h']));
            if ($res === false) {
                http_response_code(400);
            }
            else {
                echo $res;
                http_response_code(201);
                die();
            }
        }
        else {
            http_response_code(400);
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        require('../controllers/pixels/pixels_of_user.php');
    }
    else {
        header('Location: ' . '/', true, 302);
        die();
    }
}
else if (preg_match('/\A\/pixels(\/\d+){0,1}\/{0,1}\z/', $_SERVER['REQUEST_URI'], $id) === 1) {
    $id = substr($id[1], 1);
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        require('../controllers/pixels/pixels_update_page.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('../db/repository.php');
        require_once('../authorize.php');
        
        $pixels = get_specific_pixels(authenticate(), $id);
        update_pixels(authenticate(), $id, $pixels['pic_id']);
        
        
        move_uploaded_file($_FILES['picture']['tmp_name'], 'pictures/' . $pixels['pic_id']);
        
        
        
        header('Location: ' . '/', true, 302);
        die();
    }
    
}
else {
    header('Location: ' . '/', true, 302);
    die();
}

?>