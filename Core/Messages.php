<?php

namespace Core;

    class Messages {

         public static function getMessage($type) {

            switch ($type) {
                case 'userSuccess':
                    return "Utilisateur créé avec succès!";
                    break;

                case 'notMethodPost':
                    return "Méthode d'envoi de données incorrecte. Veuillez utiliser le formulaire!";
                    break;

                case 'invalidUsernamePassword':
                    return "Nom d'utilisateur ou mot de passe invalide!";
                    break;
            }
        }
    }
    

?>