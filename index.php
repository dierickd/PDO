<?php
require_once 'connect.php';
$pdo = PDO();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $error = [];
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = trim($value);
    }

    if (empty($data['firstname'])) {
        $error[] = 'Le prénom n\'est pas valide';
    }
    if (empty($data['lastname'])) {
        $error[] = 'Le nom de famille n\'est pas valide';
    }
    if (strlen($data['firstname']) > 45) {
        $error[] = 'Le prénom doit être inférieur à 45 caractères';
    }
    if (strlen($data['lastname']) > 45) {
        $error[] = 'Le nom de famille doit être inférieur à 45 caractères';
    }

    if (empty($error)) {
        $lastname = $data['lastname'];
        $firstname = $data['firstname'];

        $query = $pdo->prepare("INSERT INTO friend (lastname, firstname) VALUE(:lastname, :firstname)");

        $query->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $query->bindValue(':firstname', $firstname, \PDO::PARAM_STR);

        $query->execute();
        header('Location: index.php');
    }

}


$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Requete PDO</title>
</head>
<body>
    <div class="container">
        <h1>Mes amis</h1>
        <section class="list">
            <ul>
               <?php for ($i=0; $i < count($friends); $i++): ?>
               <li><?= $friends[$i]->firstname ?> <?= $friends[$i]->lastname ?></li>
               <?php endfor; ?>
            </ul>
        </section>
        <section class="formular">
            <h3>Enregistrer un nouvel ami !</h3>
                <?php if (isset($error)): ?>
                <div class="error">
                    <?php for ($i=0; $i < count($error); $i++): ?>
                        <?= $error[$i] ?>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            <form novalidate action="" method="post">
                <label for="firstname">Prénom</label>
                <input type="text"name="firstname" id="firstname" required>
                <label for="lastname">Nom</label>
                <input type="text"name="lastname" id="lastname" required>
                <button type="submit" title="Enregistrer les données">Enregistrer</button>
            </form>
        </section>
    </div>
</body>
</html>
