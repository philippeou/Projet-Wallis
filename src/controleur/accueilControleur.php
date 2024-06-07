<?php
function accueilControleur($twig){
    echo $twig->render('accueil.html.twig', array());
}
function contactControleur($twig){
    echo $twig->render('contact.html.twig', array());
}
function politiqueControleur($twig){
    echo $twig->render('politique.html.twig', array());
}
function conditionControleur($twig){
    echo $twig->render('condition.html.twig', array());
}
function aproposControleur($twig){
    echo $twig->render('apropos.html.twig', array());
}
function maintenanceControleur($twig){
    echo $twig->render('maintenance.html.twig', array());
}
function rechercheControleur($twig, $db){
    $form = array();
    $perso = new Perso($db);

    if (isset($_GET['recherche'])) {
        $recherche = $_GET['recherche'];
        $resultats = $perso->recherche($recherche);
        echo $twig->render('recherche.html.twig', array('form'=>$form,'resultats' => $resultats));
        return;
    }
}
?>