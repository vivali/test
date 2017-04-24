<?php 

namespace Buildings;

/**
* 
*/
class GardeManger
{
	private $RatioStock = 2;
	private $StockageBase = 2000;
	private $StockageCourant;

	private $RatioPrix = 2;
	private $PrixBoisBase = 1000;
	private $PrixBoisCourant;
	private $PrixNourritureBase = 1000;
	private $PrixNourritureCourant;

	private $RatioTemps = 1.6;
	private $TempsBase = 25;
	private $TempsCourant; 
	
	private $Niveau = 5;

	public function SetStock () {
		if ($this->Niveau !== 0) {
			$this->StockageCourant = round($this->StockageBase * pow($this->RatioStock, ($this->Niveau - 1)) + $this->StockageBase);
		} else {
			$this->StockageCourant = $this->StockageBase;
		}
	}

	public function GetStock () {
		return $this->StockageCourant;
	}

	public function SetPrix () {
		if ($this->Niveau !== 0) {
			$this->PrixBoisCourant = round($this->PrixBoisBase * pow($this->RatioPrix, ($this->Niveau - 1)) + $this->PrixBoisBase);
		} else {
			$this->PrixBoisCourant = $this->PrixBoisBase;
		}

		if ($this->Niveau !== 0) {
			$this->PrixNourritureCourant = round($this->PrixNourritureBase * pow($this->RatioPrix, ($this->Niveau - 1)) + $this->PrixNourritureBase);
		} else {
			$this->PrixNourritureCourant = $this->PrixNourritureBase;
		}
	}

	public function GetPrixBois () {
		return $this->PrixBoisCourant;
	}

	public function GetPrixNourriture () {
		return $this->PrixNourritureCourant;
	}

	public function SetTemps () {
		if ($this->Niveau !== 0) {
			$this->TempsCourant = round($this->TempsBase * pow($this->RatioTemps, ($this->Niveau - 1)) + $this->TempsBase);
		} else {
			$this->TempsCourant = $this->TempsBase;
		}
	}

	public function GetTemps () {
		return $this->TempsCourant;
	}
}

$GardeManger = new GardeManger();
$GardeManger->SetStock();
var_dump($GardeManger->GetStock());

$GardeManger->SetPrix();
var_dump($GardeManger->GetPrixBois());
var_dump($GardeManger->GetPrixNourriture());

$GardeManger->SetTemps();
var_dump($GardeManger->GetTemps());