<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 29.12.2018
 * Time: 11:57
 */

require_once "config.php";
// Define variables and initialize with empty values
$letni = $zimni = $znacka = $model = $spz= $majitel ="";
$letni_err = $zimni_err = $znacka_err = $model_err = $spz_err= $majitel_err= "";

if($_SERVER["REQUEST_METHOD"] != "POST") {

        $spzout = $_GET['spzin'];
    $sql = "SELECT spz,znacka,model,zimni,letni FROM automobil WHERE spz = '".$spzout."'";


    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $letni=$row['letni'];
            $zimni=$row['zimni'];
            $znacka=$row['znacka'];
            $model=$row['model'];
            $spz=$row['spz'];


        }

    }

}

if(isset($_POST["zpet"])){
    header("location: index.php");
}

if(isset($_POST["vymaz"])){

    $spz=trim($_POST["spz"]);
if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "DELETE FROM automobil WHERE spz='".$spz."'";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
            header("location: index.php");

        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }




    // Close connection
    mysqli_close($link);

}







// Processing form data when form is submitted
if(isset($_POST["uprav"])){

    $spz=trim($_POST["spz"]);




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


        if (!$link) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "UPDATE automobil SET zimni='".$zimni."', letni='".$letni."', znacka='".$znacka."', model='".$model."'WHERE spz='".$spz."'";

        if (mysqli_query($link, $sql)) {
            echo "Record updated successfully";
            header("location: index.php");

        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

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
    <h2>Úprava Automobilu</h2>
    <p>Prosím vyplňte informace o automobilu.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($spz_err)) ? 'has-error' : ''; ?>">
            <label>SPZ</label>
            <input type="text" name="spz" class="form-control" value="<?php echo $spz; ?>" readonly="readonly">
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
            <input type="submit" name="uprav" class="btn btn-primary" value="Uložit změny">
            <input type="submit" name="vymaz" class="btn btn-default" value="Odstranit Automobil ">
            <input type="submit" name="zpet" class="btn btn-default" value="Zpět">
        </div>



    </form>
</div>
</body>
</html>

