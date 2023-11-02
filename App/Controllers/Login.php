<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Login/index.html');
    }

    /**
     * Show the show page
     *
     * @return void
     */
    public function authAction()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {

            RedirectPage::redirect("login/index");
            exit();
        }

        $checked = \App\Models\Login::checkUser($_POST);

        if ($checked) {

            RedirectPage::redirect("home/index");

        } else {

            RedirectPage::redirect('login/index');
        }
    } 

    public function logout() {

        session_destroy();
        RedirectPage::redirect('login/index');
    }
}
