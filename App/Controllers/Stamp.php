<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;
use \Core\Messages;
use \Core\Validation;


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
            View::renderTemplate('stamp/index.html', ['errors'=>$errors]);
        }
    }


    public function storeAction()
    {
        try {

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Titre')->value($_POST['title'])->max(45)->min(2)->pattern('words')->required();
                $val->name('Description')->value($_POST['description'])->required()->min(5);
                $val->name('Pays')->value($_POST['country_id'])->required();
                $val->name('State')->value($_POST['state_id'])->required();

                if($val->isSuccess()){

                    $inserted = \App\Models\Stamp::insert($_POST);
                    RedirectPage::redirect("stamp/index");
                    exit();

                } else {
                    $errors = $val->getErrors();

                    $states = \App\Models\stamp::getStates();
                    $countries = \App\Models\stamp::getCountries();

                    View::renderTemplate('stamp/create.html', ['errors'=>$errors, 'data'=>$_POST, 'states'=>$states, 'countries'=>$countries]);
                } 
            }
            
            View::renderTemplate('stamp/create.html');

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('stamp/create.html', ['errors'=>$errors]);
        }        
    }

/* 
    public function mystampsAction()
    {
        CheckSession::sessionAuth();

        $liste = \App\Models\Stamp::getMyStamps();
        var_dump($liste);
        die;
        View::renderTemplate('stamp/mystamps.html',['stamps'=>$liste]);
    } */


    public function addimageAction()
    {
        CheckSession::sessionAuth();

        $id = $this->route_params['id'];

        if (\App\Models\Stamp::checkStampUser($id)) {

            View::renderTemplate('stamp/addimage.html',['id'=>$id]);
        
        } else {
        
            $message = Messages::getMessage("invalidAccessStamp");
            $erros = array();
            $errors[] = $message;
            
            View::renderTemplate('stamp/index.html', ['errors'=>$errors]); 
        }

        
        
    }

    
    public function uploadAction()
    {
        CheckSession::sessionAuth();

        var_dump($_SERVER);

        if(isset($_POST)) {

            $stamp_id = $_POST['stamp_id'];
            $folder_destination = $_SERVER['DOCUMENT_ROOT']."/stampee/assets/user/". $stamp_id;

            //$folder_destination = str_replace("/", "\", $folder_destination);
        
            // Loop através das imagens enviadas
            foreach ($_FILES["images"]["tmp_name"] as $key => $path_temporary) {

                $inserted = \App\Models\stamp::insertImage($stamp_id);
                
                if (!is_dir($folder_destination)) {
                    mkdir($folder_destination, 0755, true); // Cria a pasta com permissões de escrita
                }

                $file_name = $_FILES["images"]["name"][$key];
 
                $extention = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
                // Verifica se o arquivo é uma imagem
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
                if(in_array($extention, $allowed_extensions)) {

                    $new_file_name = "$stamp_id-$inserted.$extention";
                    // Move o arquivo para o diretório de destination
                    $path_destination = $folder_destination;

                    if(move_uploaded_file($path_temporary, $path_destination)) {
                        echo "A imagem $file_name foi enviada com sucesso.<br>";
                    } else {
                        echo "Ocorreu um erro ao enviar a imagem $file_name.<br>";
                    }
                } else {
                    echo "Somente arquivos de imagem (jpg, jpeg, png, gif) são permitidos para a imagem $file_name.<br>";
                }
            }
        }
    }  

}
