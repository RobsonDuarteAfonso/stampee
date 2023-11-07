<?php

namespace App\Models;

use PDO;
use \Core\Validation;

class User extends \Core\Model
{

    public static function getAll()
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT * FROM users');

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        

    }


    public static function insert($data)
    {
        try {

            if ($data) {
    
                $db = static::getDB();
                $fieldsName = implode(", ", array_keys($data));
                $fieldsValue = ":".implode(", :", array_keys($data));

                $sql = "INSERT INTO user ($fieldsName) VALUES ($fieldsValue)";
                $stmt = $db->prepare($sql);

                foreach($data as $key=>$value) {
                    $stmt->bindValue(":$key", $value);
                }
                
                $stmt->execute();
                return $db->lastInsertId();
            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }


    public static function checkUser($data) 
    {
        try {

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

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }


    public static function checkUsernameExists($email) 
    {
        try {

            $db = static::getDB();
            $sql = "SELECT * FROM user WHERE email = :email ";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $count = $stmt->rowCount();
            return $count;

        } catch (Exception $ex) {
            throw new Exception($stmt->errorInfo(), 1);
        }
    }

}
