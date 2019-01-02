<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 21.12.2018
 * Time: 18:46
 */


// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $jmeno = $prijmeni= $telefon = $adresa= $mesto=$psc="";
$username_err = $password_err = $confirm_password_err = $jmeno_err = $prijmeni_err= $telefon_err= $adresa_err= $mesto_err= $psc_err="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username

    if(!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL)){
        $username_err = "Prosím zadejte email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id_uzivatel FROM uzivatel WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Tento email je již používán.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }



    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Prosím zadejte heslo.";
    } elseif(strlen(trim($_POST["password"])) < 4){
        $password_err = "Heslo musí mít alespoň 4 znaky.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Kontrola prijmeni
    if(empty(trim($_POST["prijmeni"]))){
        $prijmeni_err = "Prosím zadejte Přijmení.";
    }else{
        $prijmeni = trim($_POST["prijmeni"]);
    }
    // Kontrola jmeno
    if(empty(trim($_POST["jmeno"]))){
        $jmeno_err = "Prosím zadejte Jméno.";

    }else{
        $jmeno = trim($_POST["jmeno"]);

    }

    // Kontrola telefon
    if(preg_match('/^[0-9]{9}+$/', trim($_POST["telefon"]))) {
       $telefon = trim($_POST["telefon"]);
    }else{
        $telefon_err="Prosím zadejte platný telefon";
    }

    //Kontrola Adresa
    if(empty(trim($_POST["adresa"]))){
        $adresa_err = "Prosím zadejte Adresu.";
    }else{
        $adresa = trim($_POST["adresa"]);
        }

    //Kontrola Město
    if(empty(trim($_POST["mesto"]))){
        $mesto_err = "Prosím zadejte Město.";
    }else{
        $mesto = trim($_POST["mesto"]);
    }

    //Kontrola PSČ
    if(empty(trim($_POST["psc"]))){
        $psc_err = "Prosím zadejte Psč.";
    }else{
        $psc = trim($_POST["psc"]);
    }


    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Prosím zadejte heslo.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Hesla se neschodují.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($jmeno_err)&& empty($prijmeni_err) && empty($adresa_err)&&
    empty ($mesto_err)&& empty($psc_err)&& empty($telefon_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO uzivatel ( jmeno, prijmeni, adresa, role, email, telefon, heslo, Mesto, PSC) VALUES ( ?,?,?,?,?,?,?,?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssissi", $param_jmeno, $param_prijmeni, $param_adresa, $param_role,
                $param_username, $param_telefon, $param_password, $param_Mesto, $param_PSC);

            // Set parameters
            $param_jmeno=$jmeno;
            $param_prijmeni=$prijmeni;
            $param_adresa=$adresa;
            $param_role="Uzivatel";
            $param_username = $username;
            $param_telefon=$telefon;
            //$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_password=$password;
            $param_Mesto= $mesto;
            $param_PSC=$psc;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }


    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">-->
    <link rel="stylesheet" href="css/stylesheet.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Přihlášení</h2>
        <p>Prosím vyplňte registrační formulář.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <label class="help-block"><?php echo $username_err; ?></label>
            </div>


        <div class="vPravo">
                <div class="form-group <?php echo (!empty($jmeno_err)) ? 'has-error' : ''; ?>">
                <label>Jméno</label>
                <input type="text" name="jmeno" class="form-control in" value="<?php echo $jmeno; ?>">
                <label class="help-block"><?php echo $jmeno_err; ?></label>
            </div>

            <div class="form-group uhni <?php echo (!empty($prijmeni_err)) ? 'has-error' : ''; ?>">
                <label >Přijmení</label>
                <input type="text" name="prijmeni"  class="form-control in "  value="<?php echo $prijmeni; ?>">
                <label class="help-block "> <?php echo $prijmeni_err; ?></label>
            </div>
        </div>


            <div class="vPravo">
                <div class="form-group <?php echo (!empty($adresa_err)) ? 'has-error' : ''; ?>">
                    <label>Adresa</label>
                    <input type="text" name="adresa" class="form-control in" value="<?php echo $adresa; ?>">
                    <label class="help-block"><?php echo $adresa_err; ?></label>
                </div>

                <div class="form-group uhni <?php echo (!empty($mesto_err)) ? 'has-error' : ''; ?>">
                    <label >Město</label>
                    <input type="text" name="mesto"  class="form-control in "  value="<?php echo $mesto; ?>">
                    <label class="help-block "> <?php echo $mesto_err; ?></label>
                </div>
            </div>

            <div class="vPravo">


                <div class="form-group <?php echo (!empty($psc_err)) ? 'has-error' : ''; ?>">
                    <label >Psč</label>
                    <input type="text" name="psc"  class="form-control in "  value="<?php echo $psc; ?>">
                    <label class="help-block "> <?php echo $psc_err; ?></label>
                </div>

                <div class="form-group uhni <?php echo (!empty($telefon_err)) ? 'has-error' : ''; ?>">
                    <label>Telefon</label>
                    <input type="text" name="telefon" class="form-control in" value="<?php echo $telefon; ?>">
                    <label class="help-block"><?php echo $telefon_err; ?></label>
                </div>
            </div>



            <div class="vPravo">
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Heslo</label>
                    <input type="password" name="password" class="form-control in" value="<?php echo $password; ?>">
                    <label class="help-block"><?php echo $password_err; ?></label>
                </div>

                <div class="form-group uhni <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Potvrdit Heslo</label>
                    <input type="password" name="confirm_password" class="form-control in" value="<?php echo $confirm_password; ?>">
                    <label class="help-block"><?php echo $confirm_password_err; ?></label>
                </div>
            </div>



            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>


            <p>Již máte účet? <a href="login.php">Přihlašte se zde</a>.</p>
        </form>
    </div>
</body>
</html>