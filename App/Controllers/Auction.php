<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;


class Auction extends \Core\Controller
{

    public function indexAction()
    {
        View::renderTemplate('Auction/index.html');
    }


    public function detailsAction()
    {
        View::renderTemplate('Auction/details.html');
    }


    public function createAction()
    {
        try {
            
            CheckSession::sessionAuth();

            //getMyStamps()

            View::renderTemplate('Auction/create.html');

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('Auction/create.html', ['errors'=>$errors]);
        }
    }


    public function storeAction()
    {
        try {

            if (!empty($_POST)) {

                $inserted = \App\Models\Auction::insert($_POST);
                RedirectPage::redirect("auction/myauctions");
                exit();
            }
            
            View::renderTemplate('Auction/create.html');

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('Auction/create.html', ['errors'=>$errors]);
        }        
    }


    public function myauctionsAction()
    {
        CheckSession::sessionAuth();

        $liste = \App\Models\Auction::getMyAuctions();
        View::renderTemplate('Auction/myauctions.html',['auctions'=>$liste]);
    }

}
