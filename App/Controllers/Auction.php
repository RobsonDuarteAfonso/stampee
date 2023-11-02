<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Auction extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Auction/index.html');
    }

     /**
     * Show the index page
     *
     * @return void
     */
    public function detailsAction()
    {
        View::renderTemplate('Auction/details.html');
    }

         /**
     * Show the index page
     *
     * @return void
     */
    public function createAction()
    {
        CheckSession::sessionAuth();

        $states = \App\Models\Auction::getStates();
        $status = \App\Models\Auction::getStatus();

        View::renderTemplate('Auction/create.html',['states'=>$states, 'status'=>$status]);
    }

    public function storeAction()
    {
        if (!empty($_POST)) {

            $inserted = \App\Models\Auction::insert($_POST);
            RedirectPage::redirect("auction/myauctions");
            exit();
        }
        
        View::renderTemplate('Auction/create.html');
    }


     /**
     * Show the index page
     *
     * @return void
     */
    public function myauctionsAction()
    {
        CheckSession::sessionAuth();

        $liste = \App\Models\Auction::getMyAuctions();
        View::renderTemplate('Auction/myauctions.html',['auctions'=>$liste]);
    }


    public function addimageAction()
    {
        CheckSession::sessionAuth();

        $id = $this->route_params['id'];

        View::renderTemplate('Auction/addimage.html',['id'=>$id]);
    }

    
    public function uploadAction()
    {
        CheckSession::sessionAuth();

        var_dump($_SERVER);

        if(isset($_POST)) {

            $auction_id = $_POST['auction_id'];
            $folder_destination = $_SERVER['DOCUMENT_ROOT']."/stampee/assets/user/". $auction_id;

            //$folder_destination = str_replace("/", "\", $folder_destination);
        
            // Loop através das imagens enviadas
            foreach ($_FILES["images"]["tmp_name"] as $key => $path_temporary) {

                $inserted = \App\Models\Auction::insertImage($auction_id);
                
                if (!is_dir($folder_destination)) {
                    mkdir($folder_destination, 0755, true); // Cria a pasta com permissões de escrita
                }

                $file_name = $_FILES["images"]["name"][$key];
 
                $extention = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
                // Verifica se o arquivo é uma imagem
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
                if(in_array($extention, $allowed_extensions)) {

                    $new_file_name = "$auction_id-$inserted.$extention";
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
