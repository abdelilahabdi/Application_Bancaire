<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Transaction.php';

abstract class Compte extends BaseModel
{
    protected static $table = "comptes";

    protected $numero = "";
    protected $client_id = 0;
    protected $solde = 0;
    protected $type = "";
    protected $decouvert_max = null;

    public function __construct($numero = "", $client_id = 0, $solde = 0)
    {
        if ($numero !== "") $this->numero = $numero;
        if ($client_id) $this->client_id = (int)$client_id;
        $this->solde = (float)$solde;
    }

    public function getSolde()
    {
        return $this->solde;
    }

    public function toArray(): array
    {
        return [
            "numero"        => $this->numero,
            "solde"         => $this->solde,
            "type"          => $this->type,
            "client_id"     => $this->client_id,
            "decouvert_max" => $this->decouvert_max,
        ];
    }

    protected function logTransaction($type, $montant): void
    {
        
        if ($this->id === null) {
            throw new RuntimeException("Compte must be saved before operations (save() first).");
        }

        $t = new Transaction($this->id, $type, $montant);
        $t->save();
    }

    abstract public function deposer($montant);
    abstract public function retirer($montant);
}
