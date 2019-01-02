<?php
ob_start();
session_start();
include 'config.php';

function __autoload($className)
{
    if (file_exists('./class/' . $className . '.php')) {
        require_once './class/' . $className . '.php';
        return true;
    }
    return false;
}

?>
<html lang="en">
<head>
    <title>PHP OOP Example</title>
</head>
<body>
<nav>
    <a href="<?= BASE_URL ?>">Home</a>
    <?php if (Authentication::getInstance()->hasIdentity()) : ?>
        <a href="<?= BASE_URL . "?page=user&action=read-all" ?>">Read all user</a>
        <a href="<?= BASE_URL . "?page=user&action=by-email" ?>">By email</a>
        <a href="<?= BASE_URL . "?page=logout" ?>">Logout</a>
    <?php else : ?>
        <a href="<?= BASE_URL . "?page=login" ?>">Login</a>
    <?php endif; ?>

</nav>

<main>
    <?php
    $file = "./page/" . $_GET["page"] . ".php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "<h1>This is home page</h1>";
    }
    ?>
</main>
</body>
</html>