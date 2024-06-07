<?php
function lienControleur($twig,$db){
    $form = array();
    $lien = new Lien($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec=$lien->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table lien';
            }
        }
    }
    if(isset($_GET['id'])){
        $exec=$lien->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table lien';
        }else{
            $form['valide'] = true;
            $form['message'] = 'Lien supprimé avec succès';
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
    $r = $lien->selectCount();
    $nb = $r['nb'];
    
    $liste = $lien->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;
    echo $twig->render('lien.html.twig', array('form'=>$form,'liste'=>$liste));

}

function lienAjoutControleur($twig, $db){
    $form = array();

    if(isset($_POST['btAjouter'])){
        $lien = new Lien($db);
        $libelle = $_POST['inputLibelle'];
        $descrip1 = $_POST['inputDescrip1'];
        $descrip10 = $_POST['inputDescrip10'];
        $exec = $lien->insert($libelle,$descrip1,$descrip10,);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table lien';
        }
        else{
            $form['valide'] = true;
            $form['message'] = 'Lien ajouté avec succès';
        }
    }


    echo $twig->render('lien-ajout.html.twig', array());
    
}

function lienModifControleur($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
    $lien = new Lien($db);
    $unlien = $lien->selectById($_GET['id']); 
    if ($unlien!=null){
        $form['lien'] = $unlien;
    }
    else{
        $form['message'] = 'Lien incorrect';
    }
    }
    else{
        if(isset($_POST['btModifier'])){
            $id = $_POST['id'];
            $libelle = $_POST['inputLibelle'];
            $descrip1 = $_POST['inputDescrip1'];
            $descrip10 = $_POST['inputDescrip10'];
            if(empty($libelle)){
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            }
            else{
            $lien = new Lien($db);
            $exec=$lien->update($id, $libelle,$descrip1,$descrip10);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
        }
    }
    }else{
        $form['message'] = 'Lien non précisé';
    }
}
    echo $twig->render('lien-modif.html.twig', array('form'=>$form));
   }


?>