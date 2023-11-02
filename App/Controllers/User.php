<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('user/index.html');
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function createAction()
    {       
        View::renderTemplate('user/create.html');
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function storeAction()
    {
        if (!empty($_POST)) {

            $options = [
                'cost' => 10
            ];

            $hashPassword= password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
            $_POST['password'] = $hashPassword;

            $inserted = \App\Models\User::insert($_POST);
            RedirectPage::redirect("login/index");
            exit();
        }
        
        View::renderTemplate('user/create.html');
    }
 
}
