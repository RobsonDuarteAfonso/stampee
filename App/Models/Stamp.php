<?php

namespace App\Models;

use PDO;

class Stamp extends \Core\Model
{

    public static function getMyStamps()
    {
        try {

        $db = static::getDB();

            $stmt = $db->query('SELECT stp.id as id, title, description, name, iso, state, (
                    SELECT img.file_name
                    FROM image img
                    WHERE img.stamp_id = stp.id
                    LIMIT 1
                ) as file_name
                FROM stamp stp
                INNER JOIN state ste ON ste.id = stp.state_id
                INNER JOIN country ctr ON ctr.id = stp.country_id
                WHERE user_id = ' . $_SESSION['user_id']);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getMyStampsWithImage()
    {
        try {

            $db = static::getDB();

            $stmt = $db->query('SELECT stp.id as id, title, description, name, iso, state, (
                    SELECT img.file_name
                    FROM image img
                    WHERE img.stamp_id = stp.id
                    LIMIT 1
                ) as file_name
                FROM stamp stp
                INNER JOIN state ste ON ste.id = stp.state_id
                INNER JOIN country ctr ON ctr.id = stp.country_id
                WHERE user_id = ' . $_SESSION['user_id'].' AND
                (SELECT img.file_name FROM image img WHERE img.stamp_id = stp.id LIMIT 1) IS NOT NULL');

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }


    public static function getStampForId($id)
    {
        try {

            $db = static::getDB();
            $sql = 'SELECT * FROM stamp WHERE id = :id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getStates()
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT * FROM state');
                
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getCountries()
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT * FROM country');
                
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getImages($id)
    {
        try {

            $db = static::getDB();
            $sql = 'SELECT * FROM image WHERE stamp_id = :id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            $stmt->execute();
            
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

                $sql = "INSERT INTO stamp ($fieldsName) VALUES ($fieldsValue)";
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


    public static function update($data)
    {
        try {

            if ($data) {

                extract($data);

                $db = static::getDB();

                $sql = "UPDATE stamp SET 
                        title = :title, 
                        description = :description,
                        country_id = :country,
                        state_id = :state
                        WHERE id = :id";

                $stmt = $db->prepare($sql);

                $stmt->bindValue(":title", $title);
                $stmt->bindValue(":description", $description);
                $stmt->bindValue(":country", $country_id);
                $stmt->bindValue(":state", $state_id);
                $stmt->bindValue(":id", $id);

                $stmt->execute();

            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }    


    public static function insertImage($id, $name)
    {
        try {

            if ($id) {

                $db = static::getDB();
                $sql = "INSERT INTO image (file_name, stamp_id) VALUES (:file_name, :id)";

                $stmt = $db->prepare($sql);
                $stmt->bindValue(":file_name", $name);
                $stmt->bindValue(":id", $id);
            }
            
            if($stmt->execute()) {
                return $db->lastInsertId();
            } else {
                throw new Exception($stmt->errorInfo(), 1);
            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function checkStampUser($id) 
    {
        try {

            $db = static::getDB();
            $sql = "SELECT * FROM stamp WHERE id = :id AND user_id = :user";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":user", $_SESSION['user_id']);

            $stmt->execute();

            $count = $stmt->rowCount();

            if ($count === 1) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }

    public static function delete($id) 
    {
        try {

            $db = static::getDB();

            $sql = "DELETE FROM image WHERE stamp_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);

            $stmt->execute();            

            $sql2 = "DELETE FROM stamp WHERE id = :id AND user_id = :user";

            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(":id", $id);
            $stmt2->bindValue(":user", $_SESSION['user_id']);

            $stmt2->execute();

            $count = $stmt2->rowCount();

            if ($count === 1) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }
}