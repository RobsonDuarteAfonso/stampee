<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function insert($data)
    {

        if ($data) {

            $db = static::getDB();
            
            $fieldsName = implode(", ", array_keys($data));
            $fieldsValue = ":".implode(", :", array_keys($data));

            $sql = "INSERT INTO user ($fieldsName) VALUES ($fieldsValue)";

            $stmt = $db->prepare($sql);

            foreach($data as $key=>$value) {
                $stmt->bindValue(":$key", $value);
            }
        }
        
        if($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            throw new Exception($stmt->errorInfo(), 1);
        }
    }   
}
