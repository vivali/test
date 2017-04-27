<?php 

namespace Buildings;

use \Model\UserModel;
use \Model\DefaultModel;

/**
* 
*/
class Puit
{
	private $nom = "water_farm";
	private $RatioProd = 1.2;
	private $ProductionBase = 0;
	private $ProductionCourante;

	public $barre = '';
	public $action = 1;

	private $RatioPrix = 1.5;
	private $PrixBoisBase = 225;
	private $PrixBoisCourant; 
	private $PrixNourritureBase = 155;
	private $PrixNourritureCourant;
	private $PrixEauBase = 100;
	private $PrixEauCourant;

	private $RatioTemps = 1.5;
	private $TempsBase = 43;
	private $TempsCourant;

	private $Niveau = 0;
	private $Vitesse = 20;

	public function __construct () {
		$this->Niveau = $_SESSION["buildings"]->water_farm;
		$this->SetProd();
		$this->SetPrix();
		$this->SetTemps();
		$nom 	= $this->nom;
		$id_user = $_SESSION["user"]["id"];
		$UserModel = new UserModel();
		$DefaultModel = new DefaultModel();
		if (!empty($_SESSION["construct"]->$nom)){
			$this->barre = "<div id='bar'>".$DefaultModel->buttonConstruct($_SESSION["construct"]->$nom, $this->GetTemps())."</div>";
			if (($_SESSION["construct"]->$nom - date_format(date_create(),'U')) <= 0){
                $_SESSION["construct"]->$nom = null;
                $this->Niveau = $this->Niveau + 1;
                $_SESSION["buildings"]->$nom = $this->Niveau;
                $UserModel->refreshBuildings($this->nom, ":".$this->nom, $this->Niveau, $id_user);
            }
            else{
            	$this->barre = "<div id='bar'>".$DefaultModel->buttonConstruct($_SESSION["construct"]->$nom, $this->GetTemps())."</div>";
            }
		}
	}

	public function SetProd () {
		$niveau = ($this->Niveau < 20) ? $this->Niveau : 20; 
		if ($niveau != 0) {

		$resultat = 10 * $niveau;
		$resultat *= (1.5 + pow(($niveau / 1000), $niveau));
		$resultat *= (1 + ($niveau / 100));
		$resultat *= $this->Vitesse + (20 * $this->Vitesse);
		$resultat = $resultat / 3600;

		$this->ProductionCourante = $resultat;
		} else {
			$this->ProductionCourante = $this->ProductionBase / 3600;
		}

	}

	public function GetProd () {
		return $this->ProductionCourante;
	}

	public function SetPrix () {
		if ($this->Niveau != 0) {
			$this->PrixBoisCourant = round($this->PrixBoisBase * pow($this->RatioPrix, ($this->Niveau - 1)) + $this->PrixBoisBase);
		} else {
			$this->PrixBoisCourant = $this->PrixBoisBase;
		}

		if ($this->Niveau != 0) {
			$this->PrixNourritureCourant = round($this->PrixNourritureBase * pow($this->RatioPrix, ($this->Niveau - 1)) + $this->PrixNourritureBase);
		} else {
			$this->PrixNourritureCourant = $this->PrixNourritureBase;
		}

		if ($this->Niveau != 0) {
			$this->PrixEauCourant = round($this->PrixEauBase * pow($this->RatioPrix, ($this->Niveau - 1)) + $this->PrixEauBase);
		} else {
			$this->PrixEauCourant = $this->PrixEauBase;
		}
	}

	public function GetPrixBois () {
		return $this->PrixBoisCourant;
	}

	public function GetPrixNourriture () {
		return $this->PrixNourritureCourant;
	}

	public function GetPrixEau () {
		return $this->PrixEauCourant;
	}

	public function SetTemps () {
		if ($this->Niveau != 0) {
			$this->TempsCourant = round($this->TempsBase * pow($this->RatioTemps, ($this->Niveau - 1)) + $this->TempsBase);
		} else {
			$this->TempsCourant = $this->TempsBase;
		}
	}

	public function GetTemps () {
		return $this->TempsCourant;
	}

	public function SetNiveau () {

		if ($_SESSION["ressources"]->wood >= $this->PrixBoisCourant && $_SESSION["ressources"]->food >= $this->PrixNourritureCourant && $_SESSION["ressources"]->water >= $this->PrixEauCourant) {
			// Requête augmentation du niveau en bdd !
			$UserModel = new UserModel();
			$DefaultModel = new DefaultModel();
			$id_user = $_SESSION["user"]["id"];
			// Requête suppression des ressources en fonction du prix
			$wood 	= &$_SESSION["ressources"]->wood;
            $water 	= &$_SESSION["ressources"]->water;
	        $food 	= &$_SESSION["ressources"]->food;
	        $camper = &$_SESSION["ressources"]->camper;

	        $wood 	-= $this->PrixBoisCourant;
            $water 	-= $this->PrixEauCourant;
            $food 	-= $this->PrixNourritureCourant;
            $nom 	= $this->nom;


			$date = date_create();
			$_SESSION["construct"]->$nom = date_format($date, 'U') + $this->GetTemps();

			$UserModel->refreshRessources($wood, $water, $food, $camper, $id_user);
		} else {
			// Afficher message manque de ressource dans une div 
			echo "Manque de ressource";

		}
		// 	if($UserModel->refreshRessources($wood, $water, $food, $id_user)){

		// 		if (empty($_SESSION["construct"]->$nom)){
		//             $this->action = 1;
		//             // $_SESSION["buildings"]->water_farm = 0;
		//         }
		//         else{      		            
		//             if (($_SESSION["construct"]->$nom - date_format(date_create(),'U')) <= 0){
		//                 $button.$_nom = "<div>"."Batiment Construit."."</div>";
		//                 $_SESSION["construct"]->$nom = null;
		//                 $this->niveau += 1;
		//                 $_SESSION["buildings"]->$nom = $this->niveau;
		//                 $UserModel->refreshBuildings($this->nom, ":".$this->nom, $this->Niveau, $id_user);
		//             }
		//             else{
		//             	$this->barre = "<div id='bar'>".$DefaultModel->buttonConstruct($_SESSION["construct"]->$nom, $this->GetTemps())."</div>";
		//             }
		//         }
		// 	}

		// } else {
		// 	// Afficher message manque de ressource dans une div
		// 	$this->barre = "b";
		// 	echo "Manque de ressource";
		// }
	}

	public function GetNiveau () {
		return $this->Niveau;
	}
}







