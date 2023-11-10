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

            View::renderTemplate('user/myspace.html', ['errors'=>$errors]);
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

            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]);
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

                $stamp = \App\Models\stamp::getStampForId($id);

                View::renderTemplate('stamp/edit.html',['data'=>$stamp, 'states'=>$states, 'countries'=>$countries]);

            } else {

                $errors = Messages::getMessage("invalidAccess", "Timbre");

                $liste = \App\Models\Stamp::getMyStamps();

                View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]);
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
                
            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]); 
        }
    }


    public function confirmAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $id = $_POST['id'];

            if (\App\Models\Stamp::checkStampUser($id)) {
            
                $states = \App\Models\stamp::getStates();
                $countries = \App\Models\stamp::getCountries();

                $stamp = \App\Models\stamp::getStampForId($id);

                View::renderTemplate('stamp/confirm.html',['data'=>$stamp, 'states'=>$states, 'countries'=>$countries]);

            } else {

                $errors = Messages::getMessage("invalidAccessStamp");

                $liste = \App\Models\Stamp::getMyStamps();

                View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]);
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
                
            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]); 
        }
    }     


    public function deleteAction()
    {
        try {

            if (!empty($_POST)) {

                $deleted = \App\Models\Stamp::delete($_POST['id']);

                if ($deleted) {

                    $this->deleteDirectory($this->pathBase(). $_POST['id']);

                    $message = Messages::getMessage("deletedSuccess", "Timbre");
                    $liste = \App\Models\Stamp::getMyStamps();

                    View::renderTemplate('stamp/index.html', ['message'=>$message, 'stamps'=>$liste]);

                } else {

                    $errors = Messages::getMessage("deletedError");

                    $liste = \App\Models\Stamp::getMyStamps();
    
                    View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]);

                }

            } else {

                    $errors = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/confirm.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
            } 

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/confirm.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
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

                if ($val->isSuccess()) {

                    $inserted = \App\Models\Stamp::insert($_POST);
                    $message = Messages::getMessage("createdSuccess", "Timbre");

                    $liste = \App\Models\Stamp::getMyStamps();

                    View::renderTemplate('stamp/index.html', ['message'=>$message, 'stamps'=>$liste]);

                } else {

                    $errors = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/create.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
                } 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/create.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
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

                if ($val->isSuccess()) {

                    \App\Models\Stamp::update($_POST);
                    $message = Messages::getMessage("updatedSuccess", "Timbre");

                    $liste = \App\Models\Stamp::getMyStamps();

                    View::renderTemplate('stamp/index.html', ['message'=>$message, 'stamps'=>$liste]);

                } else {

                    $errors = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/edit.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
                } 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            
            $states = \App\Models\stamp::getStates();
            $countries = \App\Models\stamp::getCountries();

            View::renderTemplate('stamp/edit.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
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

                $errors = Messages::getMessage("invalidAccessStamp");
                
                $liste = \App\Models\Stamp::getMyStamps();

                View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]); 
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
                
            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]); 
        } 
    }

    
    public function uploadAction()
    {
        try {

            CheckSession::sessionAuth();

            if (isset($_POST)) {

                $stamp_id = $_POST['stamp_id'];

                $folder_destination = $this->pathBase(). $stamp_id;

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
                
            $liste = \App\Models\Stamp::getMyStamps();

            View::renderTemplate('stamp/index.html', ['errors'=>$errors, 'stamps'=>$liste]);
        } 
    }


    function deleteDirectory($dir) {

        if (!is_dir($dir)) {
            return false;
        }
    
        $files = array_diff(scandir($dir), array('.', '..'));
    
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
    
        rmdir($dir);
    }
    

    function pathBase() {

        $fold_base = Config::PATH_UPLOAD;
        $substring = "stampee";
        $position = strpos($fold_base, $substring);
        $path_upload = substr($fold_base, 0, $position + strlen($substring));
        return $path_upload."\public\assets\stamp\\";
    } 
}
