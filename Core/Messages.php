<?php

namespace Core;

    class Messages {

         public static function getMessage($type, $extra = null) {

            switch ($type) {
                case 'createdSuccess':
                    return "$extra créé avec succès !";
                    break;

                case 'bidSentSuccess':
                    return "Offre faite avec succès !";
                    break;                    

                case 'updatedSuccess':
                    return "$extra modifié avec succès !";
                    break;

                case 'deletedSuccess':
                    return "$extra supprimé avec succès !";
                    break;

                case 'deletedError':
                    return "Une erreur s'est produite lors de la tentative de suppression $extra !";
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

                case 'invalidAccess':
                    return "Vous essayez d'accéder à un $extra qui ne vous appartient pas !";
                    break;                 

                case 'invalidTypeImage':
                    return "Type de fichier non valide pour le téléchargement, uniquement autorisé: jpg, jpeg, png et gif.";
                    break;

                case 'uploadError':
                    return "Erreur lors du téléchargement de fichiers";
                    break;

                case 'lowestBidError':
                    return "Le montant de l'offre que vous essayez de soumettre est inférieur à l'offre actuelle.";
                    break;

                case 'lowestBidPriceInitialError':
                    return "Le montant de l'offre que vous essayez de soumettre est inférieur au montant de l'enchère initiale.";
                    break;

                case 'addFavoriteSuccess':
                    return "Enchère ajoutée avec succès à votre liste de favoris !";
                    break;
            }
        }
    }
    

?>