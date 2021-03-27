<?php
// MAMP CONF TYPE : 
$pdo = new PDO('mysql:host=localhost:8889;dbname=intro_pdo', 'root', 'root');
// UBUNTU CONF TYPE : 
//$pdo = new PDO('mysql:host=localhost;dbname=intro_pdo', 'root', 'root');

// recupération en BDD de tous les students
$students = $pdo->query("SELECT * FROM student")->fetchAll(PDO::FETCH_ASSOC);

// init erreur vide
$error = "";

// si Request POST : traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // si tous les champs ne sont pas vide
    if (!empty($_POST['name']) && !empty($_POST['age'])) {
        // Insertion en BDD a l'aide d'une requete préparée 
        $prepareInsert = $pdo->prepare("INSERT INTO student (name, age) VALUES (:name, :age)");
        $prepareInsert->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
        $prepareInsert->bindValue(':age', $_POST['age'], PDO::PARAM_INT);
        $prepareInsert->execute();
        // redirect to / pour recharger la page et mettre à jour la liste des wilders
        header('Location: /');
    // sinon erreur
    } else {
        $error = "Tous les champs sont requis !";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intro PDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>

<body>
    <div class="container p-5">
        <h1>Les wilders</h1>
        <!-- J'affiche une liste de wilder, -->
        <ul>
            <!-- Je boucles sur students -->
            <?php foreach ($students as $student) {
                // et pour chaque student j'affiche son nom et son age dans une balise <li>
                echo "<li>" . $student['name'] . " - Age : " . $student['age'] . " ans. </li>";
            } ?>
        </ul>

        <div class="border p-5">
            <h2>Insérer un Wilder en BDD</h2>
            <form method="POST">
                <!-- Gestion d'erreur du formulaire -->
                <?php
                // Si erreur n'est pas vide alors j'affiche une alert html 
                //et le message d'erreur depuis la variable $error en PHP
                if (!empty($error)) { ?>
                    <div class="alert">
                        <p><?php echo $error; ?></p>
                    </div>
                <?php }
                ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>