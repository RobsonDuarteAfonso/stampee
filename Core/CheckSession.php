<?php

namespace Core;

use \Core\RedirectPage;

    class CheckSession {

         public static function sessionAuth() {

            if (isset($_SESSION['fingerPrint']) && $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])) {
                
                return true;
            
            } else {
                RedirectPage::redirect('login/index');
            }
        }
    }

?>