<?php

namespace App\Controllers;

use \Core\View;
use \Core\RedirectPage;

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
        $liste = \App\Models\Auction::getMyAuctions();
        View::renderTemplate('Auction/myauctions.html',['auctions'=>$liste]);
    }    
}
