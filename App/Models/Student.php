<?php

namespace App\Models;

use PDO;

/**
 * Example student model
 *
 * PHP version 7.0
 */
class Student extends \Core\Model
{
    public $table = 'student';
    public $primaryKey = 'id';

    /**
     * Get all the students as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name, age FROM student');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}