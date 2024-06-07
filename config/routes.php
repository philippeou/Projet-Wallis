<?php
function getPage($db){
    $lesPages['accueil'] = "accueilControleur;0";
    $lesPages['politique'] = "politiqueControleur;0";
    $lesPages['condition'] = "conditionControleur;0";
    $lesPages['inscrire'] = "inscrireControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur;0";
    $lesPages['connexion'] = "connexionControleur;0";
    $lesPages['deconnexion'] = "deconnexionControleur;0";
    $lesPages['utilisateur'] = "utilisateurControleur;1";
    $lesPages['utilisateurModif'] = "utilisateurModifControleur;1";
    $lesPages['perso'] = "persoControleur;1";
    $lesPages['persoAjout'] = "persoAjoutControleur;1";
    $lesPages['persoModif'] = "persoModifControleur;1";
    $lesPages['lien'] = "lienControleur;1";
    $lesPages['lienAjout'] = "lienAjoutControleur;1";
    $lesPages['lienModif'] = "lienModifControleur;1";
    $lesPages['rarete'] = "rareteControleur;1";
    $lesPages['rareteAjout'] = "rareteAjoutControleur;1";
    $lesPages['rareteModif'] = "rareteModifControleur;1";
    $lesPages['recherche'] = "rechercheControleur;0";
    $lesPages['persoFiche'] = "persoFicheControleur;0";




    

    if ($db!=null){
        if (isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])){
        // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'accueil';
        }
       
        $explose = explode(";",$lesPages[$page]);
        // Nous découpons la ligne du tableau sur le
        //caractère « ; » Le résultat est stocké dans le tableau $explose

        $role = $explose[1]; // Le rôle est dans la 2ème partie du tableau $explose

        if ($role != 0){ // Si mon rôle nécessite une vérification
            if(isset($_SESSION['login'])){ // Si je me suis authentifié
                if(isset($_SESSION['role'])){ // Si j’ai bien un rôle
                    if($role!=$_SESSION['role']){ // Si mon rôle ne correspond pas à celui qui est nécessaire //pour voir la page
                        $contenu = 'accueilControleur'; // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                    }else{
                        $contenu = $explose[0]; // Je récupère le nom du contrôleur, car il a le bon rôle
                    }
                }else{
                    $contenu = 'accueilControleur';;
                }
            }else{
                $contenu = 'accueilControleur';; // Page d’accueil, car il n’est pas authentifié
            }
        }else{
            $contenu = $explose[0]; // Je récupère le contrôleur, car il n’a pas besoin d’avoir un rôle
        }
    }else{
        $contenu = $lesPages['maintenance'];
    }
    return $contenu;
}

?>