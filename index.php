<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 28.12.2018
 * Time: 10:09
 */

session_start();
$uzivatel=$id="";
require_once "config.php";

if(!isset($page))
    $page="domu";
else
$page=$_GET["page"];
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    //header("location: login.php");
    //exit;



}else{
    header("location: index_pr.php?page=domu");
    exit;
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
            <b>Bez přihlášení</b><br>


            <a  href="login.php">Přihlásit se</a>
        </div>

    </div>
    <hr>
    <nav id="menu">
        <a href="index.php?page=domu"> Domů </a>
        <a href="index.php?page=rezervace"> Rezervace</a>
        <a href="login.php"> O nás</a>
        <a href="register.php">Kontakt</a>
    </nav>
</header>

<div id="menuPravo">
<h3>Vaše Automobily</h3>
    <a href="login.php" id="pridatAuto">Pro přidání automobilu se musíte přihlásit</a>
</div>

<div id="hlavni">
    <section>

        <?php


            if (isset($_GET['page'])) {
                $file = "./stranky/" . $_GET["page"] . ".php";
            } else {
                $file = "./stranky/domu.php";
            }



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
