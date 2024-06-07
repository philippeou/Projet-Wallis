<?php
function rareteControleur($twig,$db){
    $form = array();
    $rarete = new Rarete($db);

    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id){
            $exec=$rarete->delete($id);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table rarete';
            }
        }
    }
    if(isset($_GET['id'])){
        $exec=$rarete->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table rarete';
        }else{
            $form['valide'] = true;
            $form['message'] = 'Rareté supprimé avec succès';
        }
    }
    $limite=5;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $rarete->selectCount();
    $nb = $r['nb'];
    
    $liste = $rarete->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
    $form['nopage'] = $nopage;
    echo $twig->render('rarete.html.twig', array('form'=>$form,'liste'=>$liste));

}

function rareteAjoutControleur($twig, $db){
    $form = array();

    if(isset($_POST['btAjouter'])){
        $rarete = new Rarete($db);
        $libelle = $_POST['inputLibelle'];
        $exec = $rarete->insert($libelle);
        if(!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table rarete';
        }
        else{
            $form['valide'] = true;
            $form['message'] = 'Rareté ajouté avec succès';
        }
    }


    echo $twig->render('rarete-ajout.html.twig', array());
    
}

function rareteModifControleur($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
    $rarete = new Rarete($db);
    $unRarete = $rarete->selectById($_GET['id']); 
    if ($unRarete!=null){
        $form['rarete'] = $unRarete;
    }
    else{
        $form['message'] = 'Rareté incorrect';
    }
    }
    else{
        if(isset($_POST['btModifier'])){
            $id = $_POST['id'];
            $libelle = $_POST['inputLibelle'];
            if(empty($libelle)){
                $form['valide'] = false;
                $form['message'] = 'Le champ est vide';
            }
            else{
            $rarete = new Rarete($db);
            $exec=$rarete->update($id, $libelle);
            if(!$exec){
                $form['valide'] = false;
                $form['message'] = 'Echec de la modification';
            }else{
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';
        }
    }
    }else{
        $form['message'] = 'Rareté non précisé';
    }
}
    echo $twig->render('rarete-modif.html.twig', array('form'=>$form));
   }


?>