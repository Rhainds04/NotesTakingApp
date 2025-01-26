<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Nouvelle Note</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <form class="formCreate" action="ListeDeNotes.php" method="POST">
        <label for="titre">Titre:</label>
        <input type="text" id="titre" name="titre" autocomplete="off">
        <br>
        <div class="categories">
            <span>Cuisine:</span> <input type="checkbox" name="categorie[]" value="Cuisine">
            <span>Santé:</span> <input type="checkbox" name="categorie[]" value="Sante">
            <span>Sport:</span> <input type="checkbox" name="categorie[]" value="Sport">
            <br>
            <span>Programmation:</span> <input type="checkbox" name="categorie[]" value="Programmation">
            <span>Voyage:</span> <input type="checkbox" name="categorie[]" value="Voyage">
        </div>
        <textarea id="contenu" name="contenu" rows="10" cols="45" placeholder="Écrire une note:"></textarea>
        <br>
        <input class="submitBtn" type="submit" value="nouvelle Note">
    </form>
</body>

</html>