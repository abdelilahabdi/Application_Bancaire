<?php
require_once __DIR__ . '/BaseModel.php';

class Transaction extends BaseModel
{
    protected static $table = "transactions";

    protected $compte_id = 0;
    protected $type = "";    
    protected $montant = 0;
    protected $date = null;   

    public function __construct($compte_id = 0, $type = "", $montant = 0)
    {
        if ($compte_id) $this->compte_id = (int)$compte_id;
        if ($type !== "") $this->type = $type;
        $this->montant = (float)$montant;
    }

    public function toArray(): array
    {
        return [
            "compte_id" => $this->compte_id,
            "type"      => $this->type,
            "montant"   => $this->montant,
        ];
    }

   
    public static function forCompte($compte_id): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE compte_id = :id ORDER BY date DESC");
        $stmt->execute(["id" => $compte_id]);
        return $stmt->fetchAll(); 
    }
}
