<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!--include header.php pas utilisé car l'onglet: Modifier Note est ajouté temporairement-->
    <header>
        <ul>
            <li><a style="text-decoration: none; color: white;" href="ListeDeNotes.php">Liste de Notes</a></li>
            <li><a style="text-decoration: none; color: Red;">Modifier Note</a></li>
            <li><a style="text-decoration: none; color: white;" href="index.php">Créer une Note</a></li>
        </ul>
    </header>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $filename = $_POST['filename'] ?? '';
        $titre = $_POST['titre'] ?? '';
        $contenu = $_POST['contenu'] ?? '';

        echo "<form class='formModifier' method='POST' action='ListeDeNotes.php'>";
        echo "<input type='hidden' name='filename' value='" . htmlspecialchars($filename) . "'>";
        echo "Titre: <input id='titre' type='text' name='titre' value='" . htmlspecialchars($titre) . "'><br><br>";
        echo "Contenu:<br><textarea id='contenu' name='contenu'>" . htmlspecialchars($contenu) . "</textarea><br><br>";
        echo "<button class='submitBtn' type='submit' name='edit'>Enregistrer les modifications</button>";
        echo "</form>";
    } else {
        echo "Aucune donnée reçue pour modifier la note.";
    }
    ?>
</body>

</html>