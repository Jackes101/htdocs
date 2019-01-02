<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 28.12.2018
 * Time: 10:09
 */

session_start();
$uzivatel=$id="";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    //header("location: login.php");
    //exit;

}else{
    $uzivatel=($_SESSION["username"]);
    $id=($_SESSION["id"]);
}
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Autojirman</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>






<header>
    <div id="banner">
        <div id="divPrihl">
        <b>Přihlášený uživatel:</b><br>
        <?php echo $uzivatel; ?><br>

        <a  href="logout.php">Odhlásit se</a>
        </div>

    </div>
    <hr>
    <nav id="menu">
        <a href="index_pr.php?page=domu"> Domů </a>
        <a href="index_pr.php?page=rezervace_pr"> Rezervace</a>
        <a href="login.php"> O nás</a>
        <a href="register.php">Kontakt</a>
    </nav>
</header>

<div id="menuPravo">
<h3>Vaše Automobily</h3>


    <?php
    require_once "config.php";
    // Prepare a select statement
    $sql = "SELECT spz,znacka,model FROM automobil WHERE uzivatel_id_uzivatel = ".$id;


        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                echo "<a class='notDec' href='upravAuto.php?spzin=".$row['spz']."'><div class='autaMenu'>" . $row['spz'] . "<br>";
                echo  $row['znacka'] . " ";
                echo  $row['model'] . "</div></a>";


            }

        }

    // Close statement

    ?>

    <a id="pridatAuto" href="pridatAuto.php">Přidat Automobil</a>
</div>

<div id="hlavni">
<section>

    <?php
    $file = "./stranky/" . $_GET["page"] . ".php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "<h1>This is home page</h1>";
    }

    ?>

</section>
</div>


<aside>
    <h2>About section</h2>
    <p>Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
</aside>



<footer>
    <p>Copyright 2009 Your name</p>
</footer>

</body>

</html>
