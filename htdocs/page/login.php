<h1>Login</h1>

<?php

if (!empty($_POST) && !empty($_POST["loginMail"]) && !empty($_POST["loginPassword"])) {
    $authService = Authentication::getInstance();
    if ($authService->login($_POST["loginMail"], $_POST["loginPassword"])) {
        header("Location:" . BASE_URL);
    } else {
        echo "user not found";
    }
} else if (!empty($_POST)) {
    echo "Username and password are required";
}

?>


<form method="post">

    <input type="email" name="loginMail" placeholder="Insert your email">
    <input type="password" name="loginPassword" placeholder="Password">
    <input type="submit" value="Log in">

</form>