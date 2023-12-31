<?php

namespace App\Models;

use PDO;

class Auction extends \Core\Model
{

    public static function getAllInProgress()
    {   
        try {

            $db = static::getDB();

            $stmt = $db->query('SELECT
            auc.id AS id,
            date_start,
            auc.stamp_id AS stamp_id,
            date_end,
            price,
            status,
            img.file_name AS file_name,
            title,
            auc.user_id,
            COALESCE((SELECT MAX(bid.value) FROM bid WHERE bid.auction_id = auc.id), 0) AS best_bid
            FROM auction auc
            INNER JOIN stamp stp ON stp.id = auc.stamp_id
            INNER JOIN status stu ON stu.id = auc.status_id
            INNER JOIN image img ON img.stamp_id = stp.id
            WHERE auc.status_id = 2
            ORDER BY auc.id');

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getMyAuctions()
    {
        try {

            $db = static::getDB();

            $stmt = $db->query('SELECT auc.id as id, date_start, auc.stamp_id as stamp_id,
                date_end, price, status, img.file_name as file_name, title
                FROM auction auc
                INNER JOIN stamp stp ON stp.id = auc.stamp_id
                INNER JOIN status stu ON stu.id = auc.status_id
                INNER JOIN image img ON img.stamp_id = stp.id
                WHERE auc.user_id = ' . $_SESSION['user_id'].'
                ORDER BY auc.id');

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getMaxBid($auction_id, $user_id)
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT max(value) as BestValue 
                FROM bid
                WHERE auction_id = '. $auction_id . ' AND user_id = '.$user_id);
                
            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['BestValue'];
            }

            return 0;

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getPriceInicial($auction_id)
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT price 
                FROM auction
                WHERE id = '. $auction_id);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['price'];

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }    


    public static function getStatus()
    {
        try {

            $db = static::getDB();
            $stmt = $db->query('SELECT * FROM status');
                
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getAuctionForId($id)
    { 
        try {
        
            $db = static::getDB();
            $sql = 'SELECT * FROM auction WHERE id = :id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function getMyFavorite($user)
    { 
        try {

            $db = static::getDB();

            $stmt = $db->query('SELECT
            auc.id AS id,
            date_start,
            auc.stamp_id AS stamp_id,
            date_end,
            price,
            status,
            img.file_name AS file_name,
            title,
            auc.user_id,
            COALESCE((SELECT MAX(bid.value) FROM bid WHERE bid.auction_id = auc.id), 0) AS best_bid
            FROM favorite fav
            INNER JOIN auction auc ON auc.id = fav.auction_id
            INNER JOIN stamp stp ON stp.id = auc.stamp_id
            INNER JOIN status stu ON stu.id = auc.status_id
            INNER JOIN image img ON img.stamp_id = stp.id
            WHERE fav.user_id = '. $user .
            ' ORDER BY auc.id');

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

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function insertFavorite($data)
    {
        try {

            if ($data) {

                $db = static::getDB();
                $fieldsName = implode(", ", array_keys($data));
                $fieldsValue = ":".implode(", :", array_keys($data));

                $sql = "INSERT INTO favorite ($fieldsName) VALUES ($fieldsValue)";
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

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }     


    public static function insertBid($data)
    {

        try {

            if ($data) {

                $db = static::getDB();
                $fieldsName = implode(", ", array_keys($data));
                $fieldsValue = ":".implode(", :", array_keys($data));

                $sql = "INSERT INTO bid ($fieldsName) VALUES ($fieldsValue)";
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

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }        
    }


    public static function checkAuctionUser($id) 
    {
        try {

            $db = static::getDB();
            $sql = "SELECT * FROM auction WHERE id = :id AND user_id = :user";

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


    public static function update($data)
    {
        try {

            if ($data) {

                extract($data);

                $db = static::getDB();

                $sql = "UPDATE auction SET 
                        price = :price,
                        date_start = :dateStart,
                        date_end = :dateEnd,
                        stamp_id = :stamp,
                        status_id = :status
                        WHERE id = :id";

                $stmt = $db->prepare($sql);

                $stmt->bindValue(":price", $price);
                $stmt->bindValue(":dateStart", $date_start);
                $stmt->bindValue(":dateEnd", $date_end);
                $stmt->bindValue(":stamp", $stamp_id);
                $stmt->bindValue(":status", $status_id);
                $stmt->bindValue(":id", $id);

                $stmt->execute();

            }

        } catch (Exception $ex) {            
            throw new Exception($stmt->errorInfo(), 1);
        }
    }


    public static function delete($id) 
    {
        try {

            $db = static::getDB();

            $sql = "DELETE FROM auction WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);

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
}