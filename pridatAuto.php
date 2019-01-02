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
$letni = $zimni = $znacka = $model = $spz= $majitel ="";
$letni_err = $zimni_err = $znacka_err = $model_err = $spz_err= $majitel_err= "";


if(isset($_POST["zpet"])){
    header("location: index.php");
}


// Processing form data when form is submitted
if(isset($_POST["uloz"])){

    // Validate username

    if(empty(trim($_POST["spz"]))){
        $spz_err = "Prosím zadejte SPZ.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id_pneuservis FROM automobil WHERE spz = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_spz);

            // Set parameters
            $param_spz = trim($_POST["spz"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $spz_err = "Tento automobil je již v systému.";
                } else{
                    $spz = trim($_POST["spz"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }




    //Kontrola letni
    if(empty(trim($_POST["letni"]))){
        $letni_err = "Zadejte rozměr letních kol.";
    }else{
        $letni = trim($_POST["letni"]);
    }



    //Kontrola zimni
    if(empty(trim($_POST["zimni"]))){
        $zimni_err = "Zadejte rozměr zimních kol.";
    }else{
        $zimni = trim($_POST["zimni"]);
        }

    //Kontrola Značka
    if(empty(trim($_POST["znacka"]))){
        $znacka_err = "Prosím zadejte Značku autonobilu.";
    }else{
        $znacka = trim($_POST["znacka"]);
    }

    //Kontrola Model
    if(empty(trim($_POST["model"]))){
        $model_err = "Prosím zadejte model autonobilu.";
    }else{
        $model = trim($_POST["model"]);
    }




    // Check input errors before inserting in database
    if(empty($spz_err) && empty($znacka_err) && empty($model_err) && empty($letni_err)&& empty($zimni_err) ){

        // Prepare an insert statement
        $sql = "INSERT INTO automobil ( zimni, letni, znacka, model, spz, uzivatel_id_uzivatel) VALUES ( ?,?,?,?,?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_zimni, $param_letni, $param_znacka, $param_model,
                $param_spz, $param_id_uzivatel);
            session_start();
            // Set parameters
            $param_zimni=$zimni;
            $param_letni=$letni;
            $param_znacka=$znacka;
            $param_model=$model;
            $param_spz=$spz;
            $param_id_uzivatel= $_SESSION["id"];


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index.php");
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
        <h2>Přidání Automobilu</h2>
        <p>Prosím vyplňte informace o automobilu.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($spz_err)) ? 'has-error' : ''; ?>">
                <label>SPZ</label>
                <input type="text" name="spz" class="form-control" value="<?php echo $spz; ?>">
                <label class="help-block"><?php echo $spz_err; ?></label>
            </div>


        <div class="vPravo">
                <div class="form-group <?php echo (!empty($znacka_err)) ? 'has-error' : ''; ?>">
                <label>Značka</label>
                <input type="text" name="znacka" class="form-control in" value="<?php echo $znacka; ?>">
                <label class="help-block"><?php echo $znacka_err; ?></label>
            </div>

            <div class="form-group uhni <?php echo (!empty($model_err)) ? 'has-error' : ''; ?>">
                <label >Model</label>
                <input type="text" name="model"  class="form-control in "  value="<?php echo $model; ?>">
                <label class="help-block "> <?php echo $model_err; ?></label>
            </div>
        </div>


            <div class="vPravo">
                <div class="form-group <?php echo (!empty($letni_err)) ? 'has-error' : ''; ?>">
                    <label>Rozměr letních kol</label>
                    <input type="text" name="letni" class="form-control in" value="<?php echo $letni; ?>">
                    <label class="help-block"><?php echo $letni_err; ?></label>
                </div>

                <div class="form-group uhni <?php echo (!empty($zimni_err)) ? 'has-error' : ''; ?>">
                    <label >Rozměr zimních kol</label>
                    <input type="text" name="zimni"  class="form-control in "  value="<?php echo $zimni; ?>">
                    <label class="help-block "> <?php echo $zimni_err; ?></label>
                </div>
            </div>




            <div class="form-group">
                <input type="submit" name="uloz" class="btn btn-primary" value="Uložit">
                <input type="submit" name="zpet" class="btn btn-default" value="Zpět">
            </div>



        </form>
    </div>
</body>
</html>