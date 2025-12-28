
<?php

require_once __DIR__ . '/Compte.php';

class CompteCourant extends Compte
{
    public function __construct($numero = "", $client_id = 0, $solde = 0)
    {
        parent::__construct($numero, $client_id, $solde);
        $this->type = "courant";
        $this->decouvert_max = -500;
    }

    public function deposer($montant)
    {
        $montant = (float)$montant;
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant depot doit etre > 0");
        }

        if ($montant <= 1) {
            throw new InvalidArgumentException("Montant depot doit etre > 1 (frais = 1$)");
        }

        $this->solde = $this -> solde + ($montant - 1);


        $this->save();
        $this->logTransaction("depot", ($montant - 1)); 

    }

    public function retirer($montant)
    {
        $montant = (float)$montant;
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant retrait doit etre > 0");
        }

        $nouveau = $this->solde - $montant;

        if ($nouveau < -500) {
            throw new InvalidArgumentException("Retrait refuse: decouvert max = -500");
        }


        $this->solde = $nouveau;

        $this->save();
        $this->logTransaction("retrait", $montant);

    }
}
