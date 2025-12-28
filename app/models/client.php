<?php
require_once __DIR__ . '/BaseModel.php';

class Client extends BaseModel
{
    protected static $table = "clients";

    protected $nom = "";
    protected $prenom = "";
    protected $email = "";

    public function __construct($nom = "", $prenom = "", $email = "")
    {
        if ($nom !== "") $this->setnom($nom);
        if ($prenom !== "") $this->setprenom($prenom);
        if ($email !== "") $this->setemail($email);
    }

    public function getnom() { return $this->nom; }
    public function getprenom() { return $this->prenom; }
    public function getemail() { return $this->email; }

    public function setnom($nom): void { $this->nom = trim($nom); }
    public function setprenom($prenom): void { $this->prenom = trim($prenom); }

    public function setemail($email): void
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("email is not true");
        }
        $this->email = $email;
    }

    public function toArray(): array
    {
        return [
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "email" => $this->email,
        ];
    }
}
