<?php

// Menu
require_once('menu.php');

//préparation de la requete 
$requete = 'SELECT * FROM habitations';
//requete
$q = $db->prepare($requete) or die(print_r($db->errorInfo()));
$url = '';

$position = ($_GET['id']) ?? null;

// pour vérifier que ça existe :
//print_r($position);
if (isset($position)) {
    if (!is_numeric($position)) {
        echo alert("Que du numérique mon ami :)");
        exit;
    }
    //echo "j'ai un enregistrement";
    $listehabitations = $db->query('select*from habitations where id=' . $position . '') or die(print_r($db->errorInfo()));
    while ($donnees = $listehabitations->fetch()) {
        $habitation = $donnees;
        //print_r($habitation);
        $url = '?position=' . $position;
    }
    if ($listehabitations->rowCount() === 0) {
        echo alert("Cette habitation n'existe pas ! :)");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveillances habitations</title>
</head>

<body>
    <form action="sauvegardehabitation.php" method="POST" enctype="multipart/form-data">
<h1>Inscription surveillances habitations </h1>
        <fieldset>
            <?php if ($position === null) : ?>
                <legend>Ajouter une surveillance d'habitation</legend>
            <?php else : ?>
                <legend>Modification d'une habitation</legend>
            <?php endif ?>
            <table>

            <div class="m-1">
                <tr>
                    <td>
                        ID :
                    </td>
                    <td><input type="text" class="form-control m-1" name="id" value="<?= $habitation['id'] ?? '' ?>"></td>
                </tr>
                <tr>
                    <td>Adresse : </td>
                    <td><div class="input-group m-1">
                    <input type="text" class="form-control m-1" maxlength="50" name="adresse" required autofocus placeholder="Obligatoire" value="<?= $habitation['adresse'] ?? '' ?>"></div></td>
                </tr>
                <tr>
                    <td>Localité : </td>
                    <td><input type="number" class="form-control m-1" maxlength="10" name="localite" required placeholder="Obligatoire" value="<?= $habitation['localite'] ?? '' ?>"></td>
                </tr>
                <tr>
                    <td>Date début : </td>
                    <td><input type="date" class="form-control m-1" name="datedebut" value="<?= $habitation['datedebut'] ?? '' ?>"></td>
                </tr>

                <tr>
                    <td>Date de fin : </td>
                    <td><input type="date" class="form-control m-1" name="datefin" value="<?= $habitation['datefin'] ?? '' ?>"></td>
                </tr>

                <tr>
                    <td>Mesures: </td>
                    <td><input type="text" class="form-control m-1" maxlength="50" name="mesures" placeholder="Système d'alarme, éclairage, chien, société de gardiennage, présence d'un tiers" value="<?= $habitation['mesures'] ?? '' ?>"></td>
                </tr>
                <tr>
                    <td>Véhicule : </td>
                    <td><input type="text" class="form-control m-1" maxlength="50" name="vehicule" placeholder="Ex. Marque Modèle Plaque garage" value="<?= $habitation['vehicule'] ?? '' ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" m-1>
                        <?php if (isset($position)) echo 'Dernière modification : ' .  $habitation['dateupdate'] ?? ''; ?>
                    </td>
                </tr>

                <table>
                    <tr>
                        <td>
                            <input type="reset" name="bInit" value="Réinitialiser" class="btn btn-secondary m-1" />
                        </td>
                        <td>
                            &nbsp;<input type="submit" name="bAjouthabitation" value="<?= $position ? "Modifier" : "Ajouter" ?>" class="btn btn-primary m-1">
                        </td>
                    </tr>

                </table>

        </fieldset>
    </form>



    <?php

    // Exécution de la requête
    echo '<div class="right_Side">';
    echo '<h1><i class="fa fa-map-marker" aria-hidden="true"></i> Liste des habitations </h1><br>';
    echo '<table border="1" class="table table-light">';
    echo '<thead class="thead-light">
                                <tr>
                                <th>id</th>
                                <th>Adresse</th>
                                <th>Localité</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Mesures</th>
                                <th>Véhicule</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                                    </tr>
                                </thead>';
    $listehabitations = $db->query('select*from habitations order by adresse asc') or die(print_r($db->errorInfo()));
    while ($habitation = $listehabitations->fetch()) {

        echo '<tr>';
        echo '<td>' . $habitation['id'] . '</td>';
        echo '<td>' . $habitation['adresse'] . '</td>';
        echo '<td>' . $habitation['localite'] . '</td>';
        echo '<td>' . $habitation['datedebut'] . '</td>';
        echo '<td>' . $habitation['datefin'] . '</td>';
        echo '<td>' . $habitation['mesures'] . '</td>';
        echo '<td>' . $habitation['vehicule'] . '</td>';
        echo '<td><a href="habitations.php?id=' . $habitation['id'] . '">Modifier</a></td>';
        echo '<td><a onclick="return confirm(\'Voulez-vous vraiment supprimer cet élement ?\')" href="habitations.php?delhabitations=' . $habitation['id'] . ' " >Supprimer</a></td></tr>';
    }


    echo '</table><br><br><br>';
    

    //modifier un agent
    if (isset($_GET['id'])) {
        if(!is_numeric($_GET['id']))
        {
            echo alert("Que du numérique mon ami :)");
            exit;
        }

       
        $req = $db->query('select*from habitations where id=' . $_GET['id'] . '') or die(print_r($db->errorInfo()));
        if ($req->rowCount() === 0) {
            echo alert("Cette habitation n'existe pas ! :)");
            exit;
        }

        while ($donnees = $listehabitations->fetch()) {
            $habitation = $donnees;
  
        }  
    }

    //supprimer une habitation
    if (!empty(isset($_GET['delhabitations']))) {
        $id = verify_input($_GET['delhabitations']) ?? null;
        $query = $db->query('delete from habitations where id=' . $id . '');
        if ($query->rowCount() === 0) {
            echo alert('Cette habitation n\'existe pas !');
            exit;
        }
        header("Refresh:1");
        exit;
        
    }
    ?>

</body>
</html>