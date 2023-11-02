<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Login extends \Core\Model
{

    public static function checkUser($data) {

        extract($data);

        $db = static::getDB();

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));

        $count = $stmt->rowCount();

        if ($count === 1) {

            $user = $stmt->fetch();
        
            if (password_verify($password, $user['password'])) {

                session_regenerate_id();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['name'];
                $_SESSION['access'] = $user['access_id'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
                return true;

            } else {

                return false;
            }

        } else {

            return false;
        }
    } 
}