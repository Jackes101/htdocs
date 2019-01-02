<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 29.12.2018
 * Time: 20:16
 *

 */

session_start();
$id=($_SESSION["id"]);
$zacatek = $_GET['zacas'];
$d=$_GET['datum'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>


    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/rezervace.css">

</head>
<body>


<div id="menuLevoVybAuto">
    <H2>Vyberte Automobil </H2>


    <?php
    require_once "config.php";
    // Prepare a select statement
    $sql = "SELECT spz,znacka,model,letni,zimni FROM automobil WHERE uzivatel_id_uzivatel = ".$id;


    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<a class='notDec vis' href='vytvorRez.php?zacas=".$zacatek."&datum=".$d."&auto=".$row['spz']."'><div class='autaMenu'>" ."SPZ: ". $row['spz'] . "<br>";
            echo  $row['znacka'] . " ";
            echo  $row['model'] . "<br>";
            echo  "Letní kola: ".$row['letni'] . "<br> ";
            echo  "Zimní kola: ".$row['zimni'] . "</div></a>";

        }

    }

    // Close statement

    ?>

    <a id="pridatAuto" href="pridatAuto.php">Přidat Automobil</a>
</div>


</body>
</html>