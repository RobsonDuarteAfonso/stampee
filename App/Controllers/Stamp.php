<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;
use \Core\Messages;
use \Core\Validation;
use App\Config;


class Stamp extends \Core\Controller
{

    public function indexAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html',['stamps'=>$liste]);
            
        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;

            RedirectPage::redirect('user/myspace');
        }
    }


    public function detailsAction()
    {
        View::renderTemplate('stamp/details.html');
    }


    public function createAction()
    {
        try {
            
            CheckSession::sessionAuth();
            
            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/create.html',['states'=>$states, 'countries'=>$countries]);

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;

            RedirectPage::redirect('stamp/index');
        }
    }


    public function editAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $id = $this->route_params['id'];

            if (\App\Models\Stamp::checkStampUser($id)) {
            
                $states = \App\Models\stamp::getStates();
                $countries = \App\Models\stamp::getCountries();

                $liste = \App\Models\stamp::getStampForId($id);

                View::renderTemplate('stamp/edit.html',['data'=>$liste, 'states'=>$states, 'countries'=>$countries]);

            } else {

                $_SESSION['error'] = Messages::getMessage("invalidAccessStamp");
                
                RedirectPage::redirect('stamp/index'); 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;
                
            RedirectPage::redirect('stamp/index'); 
        }
    }    


    public function storeAction()
    {
        try {

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Titre')->value($_POST['title'])->max(45)->min(2)->pattern('alphanum2')->required();
                $val->name('Description')->value($_POST['description'])->required()->min(5);
                $val->name('Pays')->value($_POST['country_id'])->required();
                $val->name('State')->value($_POST['state_id'])->required();

                if($val->isSuccess()){

                    $inserted = \App\Models\Stamp::insert($_POST);
                    $_SESSION['success'] = Messages::getMessage("createdSuccess", "Timbre");

                    RedirectPage::redirect("stamp/index");

                } else {

                    $_SESSION['error'] = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/create.html', ['data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
                } 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;

            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/create.html', ['data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
        }        
    }


    public function updateAction()
    {
        try {

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Titre')->value($_POST['title'])->max(45)->min(2)->pattern('alphanum2')->required();
                $val->name('Description')->value($_POST['description'])->required()->min(5);
                $val->name('Pays')->value($_POST['country_id'])->required();
                $val->name('State')->value($_POST['state_id'])->required();

                if($val->isSuccess()){

                    \App\Models\Stamp::update($_POST);
                    $_SESSION['success'] = Messages::getMessage("updatedSuccess", "Timbre");

                    RedirectPage::redirect("stamp/index");

                } else {

                    $_SESSION['error'] = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/edit.html', ['data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
                } 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;
            
            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/edit.html', ['data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
        }        
    }    


    public function addimageAction()
    {
        try {

            CheckSession::sessionAuth();

            $id = $this->route_params['id'];

            if (\App\Models\Stamp::checkStampUser($id)) {

                $images = \App\Models\stamp::getImages($id);
                View::renderTemplate('stamp/addimage.html',['id'=>$id, 'images'=>$images]);
            
            } else {

                $_SESSION['error'] = Messages::getMessage("invalidAccessStamp");
                
                RedirectPage::redirect('stamp/index'); 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;
                
            RedirectPage::redirect('stamp/index'); 
        } 
    }

    
    public function uploadAction()
    {
        try {

            CheckSession::sessionAuth();

            if(isset($_POST)) {

                $stamp_id = $_POST['stamp_id'];
                $fold_base = Config::PATH_UPLOAD;
                $substring = "stampee";
                $position = strpos($fold_base, $substring);
                $path_upload = substr($fold_base, 0, $position + strlen($substring));

                $folder_destination = $path_upload."\public\assets\stamp\\". $stamp_id;

                if (!is_dir($folder_destination)) {
                    mkdir($folder_destination, 0755, true);
                }

                if (isset($_FILES['images'])) {

                    $total_files = count($_FILES['images']['name']);
                    $erros = array();
                    
                    for ($i = 0; $i < $total_files; $i++) {

                        $lenght = 20;
                        $string_created = bin2hex(random_bytes($lenght));

                        $file_name = $_FILES['images']['name'][$i];
                        $extention = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                        $new_file_name = "$string_created.$extention";

                        if (in_array($extention, $allowed_extensions)) {

                            $inserted = \App\Models\stamp::insertImage($stamp_id, $new_file_name);

                            $file_temporary = $_FILES['images']['tmp_name'][$i];
                            $path_destination = $folder_destination . '/' . $new_file_name;
                
                            if (move_uploaded_file($file_temporary, $path_destination)) {

                                $_SESSION['success'] = Messages::getMessage("uploadSuccess");
                                RedirectPage::Redirect('stamp/addimage/'.$stamp_id);

                            } else {

                                $message = Messages::getMessage("uploadError");
                                $errors[] = $message;  
                            }

                        } else {

                            $message = Messages::getMessage("invalidTypeImage");
                            $errors[] = $message;                           
                        }
                    }
    
                    $_SESSION['error'] = $errors;

                   RedirectPage::Redirect('stamp/addimage/'.$stamp_id);                       
                }
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            $_SESSION['error'] = $errors;
                
            RedirectPage::Redirect('stamp/index'); 
        } 
    }  
}
