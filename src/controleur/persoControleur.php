<?php
function persoControleur($twig, $db){
    $form = array();
    $perso = new Perso($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec=$perso->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table perso';
            }
        }
    }
    if(isset($_GET['id'])){
        $exec=$perso->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table perso';
        }else{
            $form['valide'] = true;
            $form['message'] = 'Perso supprimé avec succès';
        }
    }
    $limite=20;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $perso->selectCount();
    $nb = $r['nb'];
    
    $liste = $perso->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;
    echo $twig->render('perso.html.twig', array('form'=>$form,'liste'=>$liste));

   }

function persoAjoutControleur($twig, $db){
    $form = array();

    if(isset($_POST['btAjouter'])){
        $photo =null;
        $perso = new Perso($db);
        $nom = $_POST['inputNom'];
        $titre = $_POST['inputTitre'];
        $type = $_POST['inputType'];
        $rarete = $_POST['inputRarete'];


        $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg', 'webp'), 'images', 700000);
        $photo = $upload->enregistrer('photo');
        
        $exec=$perso->insert($nom, $titre, $type, $rarete, $photo['nom']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table perso ';
        }else{
            $form['valide'] = true;
        }
    }
    echo $twig->render('perso-ajout.html.twig', array('form'=>$form));
}

function persoModifControleur($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
    $perso = new Perso($db);
    $unPerso = $perso->selectById($_GET['id']); 
    if ($unPerso!=null){
        $form['perso'] = $unPerso;
    }
    else{
        $form['message'] = 'perso incorrect';
    }
    }
    else{
        if(isset($_POST['btModifier'])){
            $id = $_POST['id'];
            $nom = $_POST['inputNom'];
            $titre = $_POST['inputTitre'];
            $type = $_POST['inputType'];
            $rarete = $_POST['inputRarete'];
            $photo = $_POST['photo'];
            
            if(empty($nom)){
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            }
            else{
            $perso = new Perso($db);
            $exec=$perso->update($id, $nom, $titre, $type, $rarete, $photo);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
        }
    }
    }else{
        $form['message'] = 'perso non précisé';
    }
}
    echo $twig->render('perso-modif.html.twig', array('form'=>$form));
}

function persoFicheControleur($twig, $db){
    $form = array();

    $perso = new Perso($db);
    {
    if(isset($_GET['id'])){
        $unPerso = $perso->selectByIdCat($_GET['id']);
        if($unPerso!=null){
            $form['perso'] = $unPerso;
        }
        else{
            $form['message'] = 'perso incorrect';
        }
    }
    else{
        $form['message'] = 'perso non précisé';
    }

}

    echo $twig->render('perso-fiche.html.twig', array('form'=>$form, 'perso' => $perso));
}


?>