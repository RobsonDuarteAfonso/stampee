<?php

namespace App\Controllers;

use \Core\View;

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
        View::renderTemplate('Auction/index.html',['url_root'=>$this->url_root]);
    }

        /**
     * Show the index page
     *
     * @return void
     */
    public function detailsAction()
    {
        View::renderTemplate('Auction/details.html',['url_root'=>$this->url_root]);
    }
}
