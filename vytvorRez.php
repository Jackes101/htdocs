<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 29.12.2018
 * Time: 20:16
 *

 */
require_once "config.php";
session_start();

if (isset($_GET['zacas'])){
    $_SESSION["zacasVyt"]=$_GET['zacas'];
    $_SESSION["autoVyt"]=$_GET['auto'];
    $_SESSION["datumVyt"]=$_GET['datum'];
}



$id=($_SESSION["id"]);
$zacatek = $_SESSION["zacasVyt"];
$spz=$_SESSION["autoVyt"];
$d=$_SESSION["datumVyt"];




if($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($_POST['sluzba'] as $selected) {
        echo "<p>" . $selected . "</p>";
    }

    echo $_POST['lz'];


    $sql = "SELECT * FROM sluzba ";

    $link->query('set ames utf8');
    $result = $link->query($sql);
    //  echo "<form >";
    $cenaCelkem = 0;
    $casCnelkem = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            foreach ($_POST['sluzba'] as $selected) {
                if ($selected == $row['id_sluzba']) {
                    $cenaCelkem = $cenaCelkem + $row['cena'];
                    $casCelkem = $casCelkem + $row['cas'];
                }
            }
        }

        echo "Cena celkem: " . $cenaCelkem;
        echo "<br>Cas celkem: " . $casCelkem;
        echo "<br>Cas pul: " . ceil($casCelkem / 30);


        $sql = "SELECT * FROM rezervace  ";

        $link->query('set names utf8');
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

            }
        }


    }
//
    // Kontrola zda se rezervace s něčím nepřekrývá
    $casOdesli = date('o-m-d ', $d);
    $casUkonci = date('o-m-d ', $d);
    $odDo;
    if (strlen($zacatek) <= 2) {
        $casOdesli = $casOdesli . $zacatek . ":00:00 ";
        $odDo=$zacatek . ":00 ";
    } else {
        $casOdesli = $casOdesli . substr($zacatek, 0, -2) . ":30:00";
        $odDo=substr($zacatek, 0, -2) . ":30 ";
    }


    $zacatekKon = $zacatek + (ceil($casCelkem / 30) / 2);

    if (strlen($zacatekKon) <= 2) {
        $casUkonci = $casUkonci . $zacatekKon . ":00:00 ";
        $odDo=$odDo.$zacatekKon . ":00 ";

    } else {
        $casUkonci = $casUkonci . substr($zacatekKon, 0, -2) . ":30:00";
        $odDo=$odDo.substr($zacatek, 0, -2) . ":30 ";
    }


    $sql = "SELECT * FROM rezervace WHERE datum_rezervace  >'" . $casOdesli . "' AND datum_rezervace  < '".$casUkonci."' OR 
            rezervace_do > '" . $casOdesli . "' AND rezervace_do < '".$casUkonci."'";

    $link->query('set names utf8');
    $result = $link->query($sql);


        if ($result->num_rows > 0) {
            echo"na tuto dobu není možné rezervovat ...";
        }

        //echo $_POST[]

    else{
$idAuto=0;
        $sql = "SELECT id_pneuservis FROM automobil WHERE spz='".$spz."'";

        $link->query('set names utf8');
        $result = $link->query($sql);


        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $idAuto=$row['id_pneuservis'];
            }

        }


        /// VLožení rezervace
        $sql = "INSERT INTO rezervace ( datum_rezervace, rezervace_do, status, automobil_id_pneuservis,faktura_id_faktura) VALUES ( ?,?,?,?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssii", $casOdesli,$casUkonci, $stat, $idAuto,$idFak);

            $idFak=1;
            $stat="Prijato";

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index_pr.php?page=rezervace");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);

        $idRezervace=0;

        $sql = "SELECT * FROM rezervace WHERE datum_rezervace  = '" . $casOdesli ."'";

        $link->query('set names utf8');
        $result = $link->query($sql);


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idRezervace=$row['id_rezervace'];
            }
        }


        /// Vložení asociace se službou
        $sql = "INSERT INTO rezervace_sluzba ( rezervace_id_rezervace, sluzba_id_sluzba) VALUES ( ?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $idRezervace, $idSluzba);


            foreach ($_POST['sluzba'] as $selected) {
                $idSluzba=$selected;
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    header("location: index_pr.php?page=rezervace_pr");
                } else{
                    echo "Something went wrong. Please try again later.";
                }

            }
            // Attempt to execute the prepared statement

        }

        // Close statement
        mysqli_stmt_close($stmt);
    }


}

?>

<!DOCTYPE html>
<html lang="cs" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/rezervace.css">

</head>
<body>

<h2>&nbsp;</h2>
<div id="menuPravo">
    <h3>Vybraný Automobil</h3>


    <?php
    require_once "config.php";
    // Prepare a select statement
    $sql = "SELECT spz,znacka,model,letni,zimni FROM automobil WHERE spz='".$spz."'";


    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<a class='notDec vis' ><div class='autaMenu'>SPZ: " . $row['spz'] . "<br><br>";
            echo  $row['znacka'] . " ";
            echo  $row['model'] . "<br><br>";
            echo  "Letní kola: <br>".$row['letni'] . "<br> ";
            echo  "Zimní kola: <br>".$row['zimni'] . "</div></a>";


        }

    }

    // Close statement

    ?>


</div>
<div id="menuLevoNRez">

<?php
if(strlen($zacatek)<=2) {
    echo "<h3>Začátek <br>" . $zacatek . ":00 </h3>";

}
else{
    echo "<h3>Začátek <br>" . substr($zacatek, 0, -2) . ":30 </h3>";
}
echo "<h3>".date('l', $d) ."<br>". date('d.m.', $d)."</h3>" ;
?>
</div>

<div id="newRezMiddle">
    <H2>Nová rezervace</H2>

    <a>Pneumatiky:</a>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <label class="btnNewRez"><input type="radio"  name="lz" value="letni" checked="checked">Letní<br></label>
        <label class="btnNewRez"> <input type="radio"  name="lz" value="zimni">Zimní<br></label>


    <input type="submit" class="cudl" id="checkBtn"value="Uložit Rezervaci">


    <script type="text/javascript">
        $(document).ready(function () {
            $('#checkBtn').click(function() {
                checked = $("input[type=checkbox]:checked").length;

                if(!checked) {
                    alert("Musíte vybrat alespoň jednu službu.");
                    return false;
                }

            });
        });

    </script>


    <div id="sub"></div>
    <a>Služby:</a>
    <div id="sl">
    <?php

    // Prepare a select statement
    $sql = "SELECT * FROM sluzba ";

    $link->query('set names utf8');
    $result = $link->query($sql);
  //  echo "<form >";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo" <label class='btnNewRez'>  ";
            echo "<input type='checkbox'  name='sluzba[]' value='".$row['id_sluzba']."' > ".$row['sluzba']. "";
            echo "<br> cas: " . $row['cas']. " min, cena: ".$row['cena']. "Kč";
            echo "<br> ";
            echo $row['popis']." </label>";
            //echo  "Letní kola: <br>".$row['letni'] . "<br> ";


        }

    }

    // Close statement

    ?>
    </div>
    </form
</div>

</body>
</html>




