<?php
require_once __DIR__ . '/../app/models/Client.php';
require_once __DIR__ . '/../app/models/CompteCourant.php';
require_once __DIR__ . '/../app/models/CompteEpargne.php';
require_once __DIR__ . '/../app/models/Transaction.php';

echo "<pre>";

try {
    // email unique
    $email = "test" . time() . "@gmail.com";

    $client = new Client("abdi", "abdelilah", $email);
    $client->save();
    echo "Client saved. ID = " . $client->getId() . "\n";

    // compte unique
    $cc = new CompteCourant("CC-" . time(), $client->getId(), 100);
    $cc->save();

    $ce = new CompteEpargne("CE-" . time(), $client->getId(), 0);
    $ce->save();

    echo "\n--- Compte Courant ---\n";
    $cc->deposer(50);
    $cc->retirer(200);
    echo "Solde courant: " . $cc->getSolde() . "\n";

    echo "\n--- Compte Epargne ---\n";
    $ce->deposer(100);

    try {
        $ce->retirer(200);
    } catch (Throwable $e) {
        echo "Retrait 200 refuse: " . $e->getMessage() . "\n";
    }

    $ce->retirer(30);
    echo "Solde epargne: " . $ce->getSolde() . "\n";

    //  Historique depuis DB
    echo "\n--- Historique Courant (compte_id=" . $cc->getId() . ") ---\n";
    $histCC = Transaction::forCompte($cc->getId());
    foreach ($histCC as $row) {
        echo $row["date"] . " | " . $row["type"] . " | " . $row["montant"] . "\n";
    }

    echo "\n--- Historique Epargne (compte_id=" . $ce->getId() . ") ---\n";
    $histCE = Transaction::forCompte($ce->getId());
    foreach ($histCE as $row) {
        echo $row["date"] . " | " . $row["type"] . " | " . $row["montant"] . "\n";
    }

} catch (Throwable $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "</pre>";


// $allClients = Client::all();
// echo "Total clients: " . count($allClients) . "\n";

// $one = Client::find($client->getId());
// echo "Find client email: " . $one->getemail() . "\n";



// $one->delete();
// echo "Client deleted.\n";



echo "\n--- Historique Courant ---\n";
$histCC = Transaction::forCompte($cc->getId());
foreach ($histCC as $row) {
    echo $row["date"] . " | " . $row["type"] . " | " . $row["montant"] . "\n";
}




echo "\n--- Historique Epargne ---\n";
$histCE = Transaction::forCompte($ce->getId());
foreach ($histCE as $row) {
    echo $row["date"] . " | " . $row["type"] . " | " . $row["montant"] . "\n";
}




$one = Client::find($client->getId());
echo "Find OK: " . $one->getemail() . "\n";

$all = Client::all();
echo "All clients count: " . count($all) . "\n";
