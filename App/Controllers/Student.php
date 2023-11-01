<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Student extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $liste = \App\Models\Student::getAll();
        View::renderTemplate('Student/index.html',['students'=>$liste]);
    }

        /**
     * Show the show page
     *
     * @return void
     */
    public function showAction()
    {
        //print_r($this->route_params);
        $id = $this->route_params['id'];

        View::renderTemplate('Student/show.html',['id'=>$id]);
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function createAction()
    {       
        View::renderTemplate('Student/create.html');
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function storeAction()
    {
        if (!empty($_POST)) {
            $liste = \App\Models\Student::insert($_POST);
            header("location:/php-mvc/public");
            exit();
        }
        
        View::renderTemplate('Student/create.html');
    }     
}