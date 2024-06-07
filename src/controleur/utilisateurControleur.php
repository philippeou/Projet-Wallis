<?php
function utilisateurControleur($twig, $db){
    $form = array();
    $utilisateur = new Utilisateur($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        $etat = true;
        foreach ( $cocher as $id){
            $exec=$utilisateur->delete($id);
            if (!$exec){
                $etat = false;
            }
        }
        header('Location: index.php?page=utilisateur&etat='.$etat);
        exit;
    }

    if(isset($_GET['id'])){
        $exec = $utilisateur->delete($_GET['id']);
    
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression de l\'utilisateur et de ses commandes';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Utilisateur et ses commandes supprimés avec succès';
        }
    }
    
    if(isset($_GET['etat'])){
        $form['etat'] = $_GET['etat'];
    }

    $liste = $utilisateur->select();
    echo $twig->render('utilisateur.html.twig', array('form'=>$form,'liste'=>$liste));
}


function utilisateurModifControleur($twig, $db){
    $form = array(); 
    if(isset($_GET['id'])){
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->selectById($_GET['id']);
        if ($unUtilisateur!=null){
            $form['utilisateur'] = $unUtilisateur;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles']=$liste;
        }
        else{
            $form['message'] = 'Utilisateur incorrect';
        }
    }
    else{
        if(isset($_POST['btModifier'])){
            $email = $_POST['inputEmail'];
            $mdp = $_POST['inputPassword'];
            $mdp2 = $_POST['inputPassword2'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $role = $_POST['role'];
            $id = $_POST['id'];

            if (empty($mdp2)==false) {
                if ($mdp!=$mdp2){
                    $form['valide'] = false;
                    $form['message'] = 'Les mots de passe sont différents';
                }
                else{
                    $utilisateur = new Utilisateur($db);
                    $exec=$utilisateur->update($id, $role, $nom, $prenom, $email, password_hash($mdp, PASSWORD_DEFAULT));
                    if(!$exec){
                        $form['valide'] = false;
                        $form['message'] = 'Echec de la modification';
                    }
                    else{
                        $form['valide'] = true;
                        $form['message'] = 'Modification réussie';
                    }
                }
            }
            else{
                $form['valide'] = false;
                $form['message'] = 'Le champ confirmation mot de passe est vide';
            }
        }
        else{
                $form['message'] = 'Utilisateur non précisé';
        }
    }
    echo $twig->render('utilisateur-modif.html.twig', array('form'=>$form));
}
?>