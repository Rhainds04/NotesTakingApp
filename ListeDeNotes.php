<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste des Notes</title>
    <!--script pour la confirmation de suppression d'une note-->
    <!--partie en javascript car aucune solution trouver en php-->
    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cette note ?");
        }
    </script>
</head>

<?php
class Note
{
    public $titre;
    public $categories;
    public $contenu;

    public function __construct($titre, $categories, $contenu)
    {
        $this->titre = $titre;
        $this->categories = $categories;
        $this->contenu = $contenu;
    }
}

// Fonction pour afficher toutes les notes
function unserializedNotes($directory)
{
    $notes = [];
    foreach (glob($directory . "*.txt") as $filename) {
        $serialized_note = file_get_contents($filename);
        $note = unserialize($serialized_note);
        if ($note !== false) {  // Vérification si la désérialisation a échoué
            $notes[] = ['filename' => $filename, 'note' => $note];
        }
    }
    return $notes;
}
// Afficher toutes les notes enregistrées
$notes = unserializedNotes("notes/");

// Vérifier si une note doit être supprimée
if (isset($_POST['delete'])) {
    $fileToDelete = $_POST['delete_file'] ?? '';

    if (file_exists($fileToDelete)) {
        unlink($fileToDelete); // Supprimer le fichier

        $notes = unserializedNotes("notes/");
    }
}

function saveNoteToFile($filename, $note)
{
    $serialized_note = serialize($note);
    return file_put_contents($filename, $serialized_note);
}

if (isset($_POST['edit'])) {
    $fileToEdit = $_POST['filename'] ?? '';
    $newTitre = $_POST['titre'] ?? '';
    $newContenu = $_POST['contenu'] ?? '';

    foreach ($notes as $item) {
        if ($item['filename'] === $fileToEdit) {
            $note = $item['note'];
            $note->titre = $newTitre;
            $note->contenu = $newContenu;

            saveNoteToFile($fileToEdit, $note);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete']) && !isset($_POST['edit'])) {

    $titre = $_POST['titre'] ?? '';
    $categories = [];
    if (isset($_POST['categorie'])) {
        $categorie_selectionnee = $_POST['categorie'];
        foreach ($categorie_selectionnee as $categorie) {
            $categories[] = $categorie;
        }
    }
    $contenu = $_POST['contenu'] ?? '';

    $newNote = new Note($titre, $categories, $contenu);

    // Vérifier si la nouvelle note existe déjà
    $noteExists = false;
    foreach ($notes as $item) {
        $note = $item['note'];
        if ($note->titre === $newNote->titre && implode(", ", $note->categories) === implode(", ", $newNote->categories) && $note->contenu === $newNote->contenu) {
            $noteExists = true;
            break;
        }
    }

    // Si la note n'existe pas encore, on l'ajoute
    if (!$noteExists) {
        $serialized_note = serialize($newNote);
        $id = uniqid();
        $filename = "notes/note_" . $id . ".txt";

        if (file_put_contents($filename, $serialized_note) !== false) {
            $notes[] = ['filename' => $filename, 'note' => $newNote];  // Ajouter la nouvelle note à la liste
        }
    }
}

// Inclure le header à partir du fichier header.php
include 'header.php';

// Afficher la liste des notes
if (!empty($notes)) {
    echo "<div class='notesConteneur'>
    <ul>";
    foreach ($notes as $item) {
        $note = $item['note'];
        $filename = $item['filename'];
        echo "<li>";
        echo "<div class='titreNote'>" . htmlspecialchars($note->titre) . "</div><br>";
        echo "Catégorie(s): " . htmlspecialchars(implode(", ", $note->categories)) . "<br>";
        echo "<textarea class='contenuNote' readonly>" . htmlspecialchars($note->contenu) . "</textarea><br><br>";

        //Formulaire pour supprimer la note
        echo "<div class='buttonContainer'>";
        echo "<form method='POST' action='' onsubmit='return confirmDelete()'>";//utilisation du script javascript pour confirmer suppression
        echo "<input type='hidden' name='delete_file' value='" . htmlspecialchars($filename) . "'>";
        echo "<button class='deleteBtn' type='submit' name='delete'>Supprimer</button>";
        echo "</form>";

        //Formulaire pour modifier la note
        echo "<form method='POST' action='modifierNote.php'>";
        echo "<input type='hidden' name='filename' value='" . htmlspecialchars($filename) . "'>";
        echo "<input type='hidden' name='titre' value='" . htmlspecialchars($note->titre) . "'>";
        echo "<input type='hidden' name='contenu' value='" . htmlspecialchars($note->contenu) . "'>";
        echo "<button class='editBtn' type='submit'>Modifier</button>";
        echo "</form>";
        echo "</div>";

        echo "</li>";
    }
    echo "</ul>
</div>";
}
?>

</html>