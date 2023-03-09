<?php

// ---------------------- VARIABLES ----------------------------
const TAUX_TVA = 20 ;

/** -------------------------- FONCTION calculerPrixTtcAnnuel ----------------------------
 * 
 * fonction permettant de ressortir le prix TTC à l’année de
 * l’abonnement, et qui sera utilisée pour générer un devis. Le calcul du prix se base sur trois
 * paramètres : 
 * 
 **- Le nombre d’adhérents du club
 **- Le nombre de sections désirées (la section est un découpage du club en plus petites entités pour
 ** faciliter la gestion, séparer les responsables ou encore séparer les paiements)
 **- La fédération dont le club est membre ( N, G, B , autre)
 */

function calculerPrixTtcAnnuel($nbAdherents, $nbSections, $federation)
{


    //--------- calcul prix TTC sur le nombre d'adherent sur 12 mois :
    $prixTTCAdherents = $prixHTAdherents = 0;

    //prix ht à l'année selon le nombre d'adherents
    switch ($nbAdherents) {
        case ($nbAdherents <= 100):
            # De 0 à 100 -> 10€/mois HT
            $prixHTAdherents = 10 * 12;
            break;

        case ($nbAdherents >= 101 and $nbAdherents <= 200):
            # De 101 à 200 -> 0.10€/adhérent/mois HT 
            $prixHTAdherents = 0.10 * $nbAdherents * 12;
            break;

        case ($nbAdherents >= 201 and $nbAdherents <= 500):
            # De 201 à 500 -> 0.09€/adhérent/mois HT 
            $prixHTAdherents = 0.09 * $nbAdherents * 12;
            break;

        case ($nbAdherents >= 501 and $nbAdherents <= 1000):
            # De 501 à 1000 -> 0.08€/adhérent/mois HT 
            $prixHTAdherents = 0.08 * $nbAdherents * 12;
            break;

        case ($nbAdherents >= 1001 and $nbAdherents <= 10000):
            # A partir de +1000 -> 70€ HT par tranche de 1000 adhérents (une tranche entamée est une tranche  comptée)
            // nombre de tranche compté
            $nbTranches = ceil($nbAdherents/1000);
            $prixHTAdherents = 70 * $nbTranches * 12;
            break;

        case ($nbAdherents >= 10000):
            # Au-dessus de 10000 -> 1000€/mois HT 
            $prixHTAdherents = 1000 * 12;
            break;

    }


    // prix si reduction " Fédération de Gymnastique (“G”) -> 15% de réduction sur le cout des adhérents "
    if($federation == "G"){
        $prixHTAdherents = $prixHTAdherents - ($prixHTAdherents*15/100);
    }

    // prix TTC adherents :
    $prixTTCAdherents = $prixHTAdherents + ($prixHTAdherents * TAUX_TVA /100 );


    //--------- calcul prix TTC des section sur 12 mois :
    
    $prixHTsections = 0;
    $prixTTCsections = 0;
    $nbSectionApayer = $nbSections;

    # - une section est offerte si le club possède  plus de 1000 adhérents. 
    if($nbAdherents>1000 ){
        $nbSectionApayer--;
    }
    # - Fédération de Natation (“N”) -> 3 sections offertes 
    if($federation =="N"){
        $nbSectionApayer=$nbSectionApayer-3;
    }

    if($nbSectionApayer>0){
        //calcul du prix HT des section sur 12 mois 
        # 5€/section/mois HT
        $prixHTsections = $nbSectionApayer * 5 * 12;

        #- Fédération de Basketball (“B”) -> 30% de réduction sur le cout des sections
        if($federation == "B"){
            $prixHTsections = $prixHTsections - ($prixHTsections*30/100);
        }
    }

        // prix TTC section :
        $prixTTCsections = $prixHTsections + ($prixHTsections * TAUX_TVA /100 );

    //test
    return $prixTTCsections;

}

//test pour calcul prix adherents
// echo "pour 60 adherents attendu :144 , obetenu: ". calculerPrixTtcAnnuel(60,0,"autre") ."<br>";
// echo "pour 100 adherents attendu :144 , obetenu: ". calculerPrixTtcAnnuel(100,0,"autre") ."<br>";
// echo "pour 100 adherents et fed G attendu :122,40  , obetenu: ". calculerPrixTtcAnnuel(100,0,"G") ."<br>";
// echo "pour 101 adherents attendu :145.44 , obetenu: ". calculerPrixTtcAnnuel(101,0,"autre") ."<br>";
// echo "pour 503 adherents attendu :579.45 , obetenu: ". calculerPrixTtcAnnuel(503,0,"autre") ."<br>";
// echo "pour 3005 adherents attendu :4032 , obetenu: ". calculerPrixTtcAnnuel(3005,0,"autre") ."<br>";
// echo "pour 3005 adherents et fed G attendu :3427.2 , obetenu: ". calculerPrixTtcAnnuel(3005,0,"G") ."<br>";
// echo "pour 10001 adherents attendu :14400 , obetenu: ". calculerPrixTtcAnnuel(10001,0,"autre") ."<br>";
// echo "pour 10001 adherents et fed G attendu :12240 , obetenu: ". calculerPrixTtcAnnuel(10001,0,"G") ."<br>";

//test calcul prix section 
echo "pour 60 adherents 10 section ,prix section attendu :720 , obetenu: ". calculerPrixTtcAnnuel(60,10,"autre") ."<br>";
echo "pour 1001 adherents (1section offerte) 10 section ,prix section attendu :648 , obetenu: ". calculerPrixTtcAnnuel(1001,10,"autre") ."<br>";
echo "pour 60 adherents 10 section fed N ( 3 section off.) ,prix section attendu :504 , obetenu: ". calculerPrixTtcAnnuel(60,10,"N") ."<br>";
echo "pour 60 adherents 15 section fed B ( -30%) ,prix section attendu :756 , obetenu: ". calculerPrixTtcAnnuel(60,15,"B") ."<br>";
echo "pour 1001 adherents fed N (4section offerte) 3 section ,prix section attendu :0 , obetenu: ". calculerPrixTtcAnnuel(1001,3,"N") ."<br>";


?>