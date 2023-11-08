<?php

namespace Core;

    class RedirectPage {

        const URL_ROOT = \App\Config::URL_ROOT;

        public static function redirect($page) {
            header('location:'. self::URL_ROOT .'public/' . $page);
        }
    }

?>