<?php

require_once __DIR__ . '/Compte.php';

class CompteEpargne extends Compte
{
    public function __construct($numero = "", $client_id = 0, $solde = 0)
    {
        parent::__construct($numero, $client_id, $solde);
        $this->type = "epargne";
        $this->decouvert_max = null;
    }

    public function deposer($montant)
    {
        $montant = (float)$montant;
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant depot doit etre > 0");
        }

         $this->solde = $this -> solde + $montant; 

         $this->save();
         $this->logTransaction("depot", $montant);

    }

    public function retirer($montant)
    {
        $montant = (float)$montant;
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant retrait doit etre > 0");
          
        }
        $nouveau = $this->solde - $montant;
        if ($nouveau < 0) {
            throw new InvalidArgumentException("Retrait refuse: pas de decouvert en epargne");
        }
        
        $this->solde = $nouveau;

        $this->save();
        $this->logTransaction("retrait", $montant);

    }
}



