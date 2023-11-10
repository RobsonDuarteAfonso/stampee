<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;
use \Core\CheckSession;
use \Core\Messages;
use \Core\Validation;


class Auction extends \Core\Controller
{

    public function indexAction()
    {
        try {

            $liste = \App\Models\Auction::getAllInProgress();

            View::renderTemplate('Auction/index.html',['auctions'=>$liste]);

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('user/myspace.html', ['errors'=>$errors]);
        }
    }


    public function detailsAction()
    {
        View::renderTemplate('Auction/details.html');
    }


    public function createAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $liste = \App\Models\Stamp::getMyStampsWithImage();

            View::renderTemplate('Auction/create.html', ['stamps'=>$liste]);

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('Auction/create.html', ['errors'=>$errors]);
        }
    }


    public function storeAction()
    {
        try {

            CheckSession::sessionAuth();

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Timbre')->value($_POST['stamp_id'])->required();
                $val->name('Prix')->value($_POST['price'])->required()->pattern('float')->min(1);
                $val->name('Date Initiale')->value($_POST['date_start'])->required();
                $val->name('Date Finale')->value($_POST['date_end'])->required();

                if ($val->isSuccess()) {                

                    $inserted = \App\Models\Auction::insert($_POST);
                    $message = Messages::getMessage("createdSuccess", "Enchère");
                    
                    $liste = \App\Models\Auction::getMyAuctions();

                    View::renderTemplate('auction/myauctions.html', ['message'=>$message, 'auctions'=>$liste]);

                } else {

                    $errors = $val->getErrors();
                    $liste = \App\Models\Stamp::getMyStampsWithImage();

                    View::renderTemplate('auction/create.html', ['errors'=>$errors, 'data'=>$_POST, 'stamps'=>$liste]);
                }
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('Auction/create.html', ['errors'=>$errors]);
        }        
    }


    public function myauctionsAction()
    {
        try {

            CheckSession::sessionAuth();

            $liste = \App\Models\Auction::getMyAuctions();
            View::renderTemplate('Auction/myauctions.html',['auctions'=>$liste]);

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('user/myspace.html', ['errors'=>$errors]);
        }         
    }


    public function bidAction()
    {
        try {

            CheckSession::sessionAuth();

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Offre')->value($_POST['value'])->required()->pattern('float')->min(1);

                if ($val->isSuccess()) {                

                    $inserted = \App\Models\Auction::insertBid($_POST);
                    $message = Messages::getMessage("bidSentSuccess");
                    
                    $liste = \App\Models\Auction::getAllInProgress();

                    View::renderTemplate('auction/index.html', ['message'=>$message, 'auctions'=>$liste]);

                } else {

                    $errors = $val->getErrors();
                    $liste = \App\Models\Auction::getAllInProgress();

                    View::renderTemplate('auction/index.html', ['errors'=>$errors, 'data'=>$_POST, 'auctions'=>$liste]);
                }
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            $liste = \App\Models\Auction::getAllInProgress();

            View::renderTemplate('auction/index.html', ['errors'=>$errors, 'data'=>$_POST, 'auctions'=>$liste]);
}        
    }    


    public function editAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $id = $this->route_params['id'];

            if (\App\Models\Auction::checkAuctionUser($id)) {
            
                $status = \App\Models\Auction::getStatus();
                $liste = \App\Models\Stamp::getMyStampsWithImage();

                $auctions = \App\Models\Auction::getAuctionForId($id);
                View::renderTemplate('auction/edit.html',['data'=>$auctions, 'status'=>$status, 'stamps'=>$liste]);

            } else {

                $errors = Messages::getMessage("invalidAccess", "Enchère");
                $liste = \App\Models\Auction::getMyAuctions();

                View::renderTemplate('Auction/myauctions.html', ['errors'=>$errors, 'auctions'=>$liste]);
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
                
            $liste = \App\Models\Auction::getMyAuctions();

            View::renderTemplate('Auction/myauctions.html', ['errors'=>$errors, 'auctions'=>$liste]);
        }
    }


    public function updateAction()
    {
        try {

            CheckSession::sessionAuth();

            if (!empty($_POST)) {

                $val = new \Core\Validation();
                $val->name('Timbre')->value($_POST['stamp_id'])->required();
                $val->name('Prix')->value($_POST['price'])->required()->pattern('float')->min(1);
                $val->name('Date Initiale')->value($_POST['date_start'])->required();
                $val->name('Date Finale')->value($_POST['date_end'])->required();
                $val->name('Status')->value($_POST['status_id'])->required();

                if ($val->isSuccess()) {                

                    $inserted = \App\Models\Auction::update($_POST);
                    $message = Messages::getMessage("updatedSuccess", "Enchère");
                    
                    $liste = \App\Models\Auction::getMyAuctions();

                    View::renderTemplate('auction/myauctions.html', ['message'=>$message, 'auctions'=>$liste]);

                } else {

                    $errors = $val->getErrors();
                    $liste = \App\Models\Stamp::getMyStampsWithImage();

                    View::renderTemplate('auction/edit.html', ['errors'=>$errors, 'data'=>$_POST, 'stamps'=>$liste]);
                }
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
            View::renderTemplate('Auction/edit.html', ['errors'=>$errors]);
        }        
    }
    
    
    public function confirmAction()
    {
        try {
            
            CheckSession::sessionAuth();

            $id = $_POST['id'];

            if (\App\Models\Auction::checkAuctionUser($id)) {
            
                $status = \App\Models\Auction::getStatus();
                $liste = \App\Models\Stamp::getMyStampsWithImage();

                $auctions = \App\Models\Auction::getAuctionForId($id);

                View::renderTemplate('auction/confirm.html',['data'=>$auctions, 'status'=>$status, 'stamps'=>$liste]);

            } else {

                $errors = Messages::getMessage("invalidAccess", "Enchère");
                $liste = \App\Models\Auction::getMyAuctions();

                View::renderTemplate('Auction/myauctions.html', ['errors'=>$errors, 'auctions'=>$liste]);
            }

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();
                
            $liste = \App\Models\Auction::getMyAuctions();

            View::renderTemplate('Auction/myauctions.html', ['errors'=>$errors, 'auctions'=>$liste]);
        }
    }


    public function deleteAction()
    {
        try {

            if (!empty($_POST)) {

                $deleted = \App\Models\Auction::delete($_POST['id']);

                if ($deleted) {

                    $message = Messages::getMessage("deletedSuccess", "Enchère");
                    $liste = \App\Models\Auction::getMyAuctions();

                    View::renderTemplate('Auction/myauctions.html', ['message'=>$message, 'auctions'=>$liste]);

                } else {

                    $errors = Messages::getMessage("deletedError");

                    $liste = \App\Models\Auction::getMyAuctions();

                    View::renderTemplate('Auction/myauctions.html', ['errors'=>$errors, 'auctions'=>$liste]);

                }

            } else {

                    $errors = $val->getErrors();

                    $status = \App\Models\Auction::getStatus();
                    $liste = \App\Models\Stamp::getMyStampsWithImage();
    
                    $auctions = \App\Models\Auction::getAuctionForId($id);
    
                    View::renderTemplate('auction/confirm.html',['data'=>$auctions, 'status'=>$status, 'stamps'=>$liste]);
            } 

        } catch (Exception $ex) {
            $erros = array();
            $errors[] = $ex->getMessage();

            $status = \App\Models\Auction::getStatus();
            $liste = \App\Models\Stamp::getMyStampsWithImage();

            $auctions = \App\Models\Auction::getAuctionForId($id);

            View::renderTemplate('auction/confirm.html',['data'=>$auctions, 'status'=>$status, 'stamps'=>$liste]);
        }        
    }     
}
