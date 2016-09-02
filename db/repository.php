<?php

function get_connection() {
    $db = new SQLite3('../db/database.sqlite3');
    $db->enableExceptions(true);
    return $db;
}

function create_user($username, $password) {
    $db = get_connection();
    try {
        $statement = $db->prepare("insert into users values(:username, :password, 0);");
        $statement->bindValue(':username', $username, SQLITE3_TEXT);
        $statement->bindValue(':password', $password, SQLITE3_TEXT);
        $statement->execute();
    }
    catch (Exception $e) {
        $db->close();
        return false;
    }
    
    $db->close();
    return true;
}

function get_user($username) {
    $db = get_connection();
    
    $sql = <<<SQL
select *
from users
where (username = :u)
SQL;
    $statement = $db->prepare($sql);
    $statement->bindValue(':u', $username, SQLITE3_TEXT);
    
    $res = $statement->execute()->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    
    if (! $res) {
        return null;
    }
    
    return $res;
}

function rectangle_intersects($r1, $r2) {
    $left = &$r1;
    $right = &$r2;
    
    if ($right['x'] < $left['x']) {
        $left = &$r2;
        $right = &$r1;
    }
    
    if ($left['x'] + $left['w'] <= $right['x']) {
        return false;
    }
    
    $up = &$r1;
    $down = &$r2;
    
    if ($up['y'] > $down['y']) {
        $up = &$r2;
        $down = &$r1;
    }
    
    if ($up['y'] + $up['h'] <= $down['y']) {
        return false;
    }
    return true;
}

function get_pixels() {
    $db = get_connection();
    
    $rows = $db->query('select * from pixels');
    
    $cur = $rows->fetchArray(SQLITE3_ASSOC);
    
    if (! $cur) {
        return [];
    }
    
    $res = [];
    $i = 0;
    do {
        $res[$i++] = $cur;
        $cur = $rows->fetchArray(SQLITE3_ASSOC);
    }
    while ($cur);
    
    $db->close();
    
    return $res;
}

function get_pixels_of_user($username) {
    $db = get_connection();
    
    $rows = $db->query("select * from pixels where username = '" . $username . "'");
    
    $cur = $rows->fetchArray(SQLITE3_ASSOC);
    
    if (! $cur) {
        return [];
    }
    
    $res = [];
    $i = 0;
    do {
        $res[$i++] = $cur;
        $cur = $rows->fetchArray(SQLITE3_ASSOC);
    }
    while ($cur);
    
    $db->close();
    
    return $res;
}

function get_specific_pixels($username, $id) {
    $db = get_connection();
    
    $rows = $db->query("select * from pixels where username = '" . $username . "' and id = " . $id);
    
    $res = $rows->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    return $res;
}

function update_pixels($username, $id, $pid_id) {
    $db = get_connection();
    
    $db->exec("update pixels set picture = " . $pid_id . " where username = '" . $username . "' and id = " . $id);
    
    $db->close();
}

function create_pixels($username, $x, $y, $w, $h) {
    $user = get_user($username);
    if (is_null($user)) {
        return false;
    }
    
    $rect = [];
    $rect['x'] = $x;
    $rect['y'] = $y;
    $rect['w'] = $w;
    $rect['h'] = $h;
    
    $pixels = &get_pixels();
    
    foreach($pixels as $p) {
        if (rectangle_intersects($rect, $p)) {
            return false;
        }
    }
    
    $db = get_connection();
    
    
    $sql = <<<SQL
insert into pixels(username, id, x, y, w, h, pic_id)
values(:u, :id, :x, :y, :w, :h,
(SELECT IFNULL(MAX(pic_id), 0) + 1 FROM pixels))
SQL;

    try {
        $statement = $db->prepare($sql);
        $statement->bindValue(':u', $username, SQLITE3_TEXT);
        $statement->bindValue(':id', $user['number_of_pixel_zones'], SQLITE3_INTEGER);
        $statement->bindValue(':x', $x, SQLITE3_INTEGER);
        $statement->bindValue(':y', $y, SQLITE3_INTEGER);
        $statement->bindValue(':w', $w, SQLITE3_INTEGER);
        $statement->bindValue(':h', $h, SQLITE3_INTEGER);
        $statement->execute();
    }
    catch (Exception $e) {
        $db->close();
        return false;
    }
    
    $db->query("update users set number_of_pixel_zones = " . ($user['number_of_pixel_zones'] + 1) . " where username = '" . $user['username'] . "'");
    
    $db->close();
    return $user['number_of_pixel_zones'];
}

function generateToken() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 50; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_login($username) {
    $db = get_connection();
    
    $statement = $db->prepare("select * from logins where username = :u");
    $statement->bindValue(":u", $username, SQLITE3_TEXT);
    $res = $statement->execute()->fetchArray(SQLITE3_ASSOC);
    
    $db->close();
    
    return $res;
}

function create_login($username, $password) {
    $user = get_user($username);
    if (!$user) {
        return null;
    }
    
    if ($user['password'] !== $password) {
        return null;
    }
    
    $db = get_connection();
    $login = get_login($username);
    
    date_default_timezone_set('UTC');

    #$now = new DateTime(date("Y-m-d H:i:s"));
    #$after = $now->add(new DateInterval("PT10M"));
    
    $tok = generateToken();
        
    if (! $login) {
        $statement = $db->prepare("insert into logins values(:u, :t, :e)");
        $statement->bindValue(':u', $username, SQLITE3_TEXT);
        $statement->bindValue(':t', $tok, SQLITE3_TEXT);
        $statement->bindValue(':e', time() + 600, SQLITE3_INTEGER);
        $statement->execute();
        $db->close();
        return $tok;
    }
    else {
        $statement = $db->prepare("update logins set expiration = :e where username = :u");
        $statement->bindValue(':u', $username, SQLITE3_TEXT);
        $statement->bindValue(':e', time() + 600, SQLITE3_INTEGER);
        $statement->execute();
        $db->close();
        
        return $login['token'];
    }
    
}

function delete_login($username) {
    $db = get_connection();
    
    $statement = $db->prepare('delete from logins where username = :u');
    $statement->bindValue(':u', $username, SQLITE3_TEXT);
    $statement->execute();
    
    $db->close();
}

function refresh_login($username) {
    $db = get_connection();

    $db->exec("update logins set expiration = " . (time() + 600) . " where username = '" . $username . "'");

    $db->close();
}

?>