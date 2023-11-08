<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;
use \Core\Messages;
use \Core\Validation;

class User extends \Core\Controller
{

    public function indexAction()
    {
        View::renderTemplate('user/index.html');
    }


    public function createAction()
    {       
        View::renderTemplate('user/create.html');
    }


    public function storeAction()
    {
        try {

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Nom')->value($_POST['name'])->max(45)->min(2)->pattern('alphanum2')->required();
                $val->name('Utilisateur')->value($_POST['email'])->pattern('email')->required()->max(45);
                $val->name('Mot de Passe')->value($_POST['password'])->pattern('alphanum')->required()->min(6)->max(20);

                if($val->isSuccess()){

                    $total = \App\Models\User::checkUsernameExists($_POST['email']);

                    if ($total > 0) {
                        $val->value($_POST['email'])->userExist();
                    }

                    if($val->isSuccess()){

                        $options = ['cost' => 10];
                        $hashPassword= password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
                        $_POST['password'] = $hashPassword;

                        $inserted = \App\Models\User::insert($_POST);

                        $message = Messages::getMessage("createdSuccess", "Utilisateur");
                        View::renderTemplate('user/login.html', ['message'=>$message]);
                        
                    } else {

                        $errors = $val->getErrors();
                        View::renderTemplate('user/create.html', ['data'=>$_POST, 'errors'=>$errors]);
                    }

                } else {

                    $errors = $val->getErrors();
                    View::renderTemplate('user/create.html', ['data'=>$_POST, 'errors'=>$errors]);
                } 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            View::renderTemplate('user/create.html', ['data'=>$_POST, 'errors'=>$errors]);
        }
    }


    public function loginAction()
    {
        View::renderTemplate('user/login.html');
    }


    public function authAction()
    {
        try {

            if ($_SERVER["REQUEST_METHOD"] != "POST") {

                $errors = Messages::getMessage("notMethodPost");

                View::renderTemplate('user/login.html', ['errors'=>$errors]);
            }

            $val = new \Core\Validation();
            $val->name('Utilisateur')->value($_POST['email'])->pattern('email')->required()->max(45);
            $val->name('Mot de Passe')->value($_POST['password'])->pattern('alphanum')->required()->min(6)->max(20);

            if($val->isSuccess()){

                $checked = \App\Models\User::checkUser($_POST);

                if ($checked) {
                    RedirectPage::redirect("user/myspace");

                } else {

                    $errors = Messages::getMessage("invalidUsernamePassword");
                    View::renderTemplate('user/login.html', ['errors'=>$errors]);  
                }

            } else {
                
                $errors = $val->getErrors();
                View::renderTemplate('user/login.html', ['errors'=>$errors]);
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            View::renderTemplate('user/login.html', ['errors'=>$errors]);
        }
    } 


    public function logout() {

        session_destroy();
        RedirectPage::redirect('user/login');
    }


    public function mySpaceAction()
    {
        CheckSession::sessionAuth();
        
        View::renderTemplate('user/myspace.html');
    }
}
