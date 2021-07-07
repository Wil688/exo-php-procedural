<?php 
    // on test la presence d'erreurs
    $user = 'root';
    $password = '';
    try
    {   // on établie la connexion à la bdd colyseum
        $bdd = new PDO('mysql:host=localhost;dbname=colyseum;charset=utf8', $user, $password);
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }
    //ex1
    $exo1 = $bdd->query('SELECT * FROM clients');// on effectue notre requête dans notre bdd
    //$exo1 = $bdd->query('SELECT UPPER(clients.lastName) AS clients.lastName,clients.firstName,DATE_FORMAT(clients.birthDate,\'%d/%m/%Y\'),clients.card,clients.cardNumber FROM `clients`')
    //var_dump($clients); // pour afficher, vérifier la structure et le contenu du tableau.
    $clients = $exo1->fetchAll(PDO::FETCH_OBJ);// Fetch permet de parcourir le résultat de la requête ligne par ligne tandis que fetchAll() renvoie toutes les lignes sous forme de tableau et le mode PDO::FETCH_OBJ permet de renvoyer mon tableau d'objet
    
    //ex2
    $exo2 = $bdd->query('SELECT id,`type` FROM showtypes');
    $genres = $exo2->fetchAll(PDO::FETCH_OBJ);

    //ex3
    $exo3 = $bdd->query('SELECT * FROM clients LIMIT 20');
    $first20 = $exo3->fetchAll(PDO::FETCH_OBJ);

    //ex4
    $exo4 = $bdd->query('SELECT clients.lastName,clients.firstName
    FROM clients
        INNER JOIN cards ON cards.cardNumber = clients.cardNumber
        INNER JOIN cardtypes ON cardtypes.id = cards.cardTypesId
    WHERE cardtypes.type = \'Fidélité\'');
    $clientsCards = $exo4->fetchAll(PDO::FETCH_OBJ);

    //ex5
    $exo5 = $bdd->query('SELECT clients.lastName,clients.firstName FROM clients WHERE lastName LIKE \'M%\' ORDER BY clients.lastName ASC');
    $clientsNameStartByM = $exo5->fetchAll(PDO::FETCH_OBJ);

    //ex6
    $exo6 = $bdd->query('SELECT shows.title,shows.performer,shows.date,shows.startTime FROM shows ORDER BY title ASC');
    $showCalendar = $exo6->fetchAll(PDO::FETCH_OBJ);

    //ex7
    $exo7 = $bdd->query('SELECT clients.lastName,clients.firstName,clients.birthDate,clients.card,clients.cardNumber,
    IF(clients.card = \'0\', \'Non\', \'Oui\') AS `cardFid`,clients.cardNumber
    FROM `clients`');
    $showUsersInfo = $exo7->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Colyseum</title>
</head>
<body>
    <pre>
        <?= var_dump($showCalendar) ?>
    </pre>
    <h1>exo1</h1>
    <p>Afficher tous les clients.</p>
    <table>
        
        <tr>
            <th>id</th>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        <?php foreach ($clients as $key => $value) { // pour parcourir mon tableau d'informations transformer en objet?> 
            <tr>
                <td><?= $value->id ?><br></td>
                <td><?= $value->lastName ?><br></td>
                <td><?= $value->firstName ?><br></td>
            </tr>
        <?php } ?>
    </table>
    <h2>exo2</h2>
    <p>Afficher tous les types de spectacles possibles.</p>
    <table>
        <select name="" id="">
        <?php foreach ($genres as $key => $value) { ?> 
            <option value="<?= $value->id ?>"><?= $value->type ?></option>
            <?php } ?>
        </select>
    </table>
    <h2>exo3</h2>
    <p>Afficher les 20 premiers clients.</p>
    <table>
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        <?php foreach ($first20 as $key => $value) { ?> 
            <tr>
                <td><?= $value->id ?><br></td>
                <td><?= $value->lastName ?><br></td>
                <td><?= $value->firstName ?><br></td>
            </tr>
        <?php } ?>
    </table>
    </table>
    <h2>exo4</h2>
    <p>N'afficher que les clients possédant une carte de fidélité.</p>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        <?php foreach ($clientsCards as $key => $value) { ?> 
            <tr>
                <td><?= $value->lastName ?><br></td>
                <td><?= $value->firstName ?><br></td>
            </tr>
        <?php } ?>
    </table>
    <h2>exo5</h2>
    <p>Afficher uniquement le nom et le prénom de tous les clients dont le nom commence par la lettre "M".
        Les afficher comme ceci : <br>Nom : Nom du client <br>Prénom : Prénom du client <br>Trier les noms par ordre alphabétique.</p>
    <?php foreach ($clientsNameStartByM as $key => $value) { ?> 
        <p><b>Nom :</b><?= $value->lastName ?></p>
        <p><b>Prénom :</b> <?= $value->firstName ?></p>
    <?php } ?>
    <h2>exo6</h2>
    <p>Afficher le titre de tous les spectacles ainsi que l'artiste, la date et l'heure. Trier les titres par ordre alphabétique. Afficher les résultat comme ceci : Spectacle par artiste, le date à heure.</p>
    <?php foreach ($showCalendar as $key => $value) { ?> 
        <p>Spectacle <?= $value->title ?> par <?= $value->performer ?>, le <?= $value->date ?> à <?= $value->startTime ?>.</p>
    <?php } ?>
    <h2>exo7</h2>
    <p>Afficher tous les clients comme ceci : <br>
    Nom : Nom de famille du client <br>
    Prénom : Prénom du client <br>
    Date de naissance : Date de naissance du client<br>
    Carte de fidélité : Oui (Si le client en possède une) ou Non (s'il n'en possède pas)<br>
    Numéro de carte : Numéro de la carte fidélité du client s'il en possède une.</p>
    <?php foreach ($showUsersInfo as $key => $value) { ?> 
        <p>Nom : <?= $value->lastName ?></p><br>
        <p>Prénom : <?= $value->firstName ?></p><br>
        <p>Date de naissance : <?= $value->birthDate ?></p><br>
        <p>Carte de fidélité : <?= $value->cardFid ?></p><br>
        <p>Numéro de carte : <?= $value->cardNumber ?></p><br>
    <?php } ?>
</body>
</html>