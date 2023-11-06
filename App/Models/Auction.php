<?php

namespace App\Models;

use PDO;

class Auction extends \Core\Model
{

    public static function getMyAuctions()
    {
        $db = static::getDB();

        $stmt = $db->query('SELECT * FROM auction auc
            INNER JOIN state ste ON ste.id = auc.state_id
            INNER JOIN status stu ON stu.id = auc.status_id
            WHERE user_id = ' . $_SESSION['user_id']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function getStatus()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM status');
            
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getStates()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM state');
            
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function insert($data)
    {

        if ($data) {

            $db = static::getDB();
            $fieldsName = implode(", ", array_keys($data));
            $fieldsValue = ":".implode(", :", array_keys($data));

            $sql = "INSERT INTO auction ($fieldsName) VALUES ($fieldsValue)";
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


    public static function insertImage($id)
    {

        if ($id) {

            $db = static::getDB();
            $sql = "INSERT INTO image (auction_id) VALUES (:id)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
        }
        
        if($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            throw new Exception($stmt->errorInfo(), 1);
        }
    }     
}