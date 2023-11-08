<?php

namespace Core;

    class Messages {

         public static function getMessage($type, $extra = null) {

            switch ($type) {
                case 'createdSuccess':
                    return "$extra créé avec succès !";
                    break;

                case 'updatedSuccess':
                    return "$extra modifié avec succès !";
                    break;                    

                case 'uploadSuccess':
                    return "Le téléversement de l'image s'est terminé avec succès !";
                    break;                    

                case 'notMethodPost':
                    return "Méthode d'envoi de données incorrecte. Veuillez utiliser le formulaire !";
                    break;

                case 'invalidUsernamePassword':
                    return "Nom d'utilisateur ou mot de passe invalide !";
                    break;

                case 'invalidAccessStamp':
                    return "Vous essayez d'accéder à un timbre qui ne vous appartient pas !";
                    break;

                case 'invalidTypeImage':
                    return "Type de fichier non valide pour le téléchargement, uniquement autorisé : jpg, jpeg, png et gif.";
                    break;

                case 'uploadError':
                    return "Erreur lors du téléchargement de fichiers";
                    break;
            }
        }
    }
    

?>