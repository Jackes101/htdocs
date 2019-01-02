<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 28.12.2018
 * Time: 18:24
 */


//SELECT * FROM rezervace WHERE DATE(datum_rezervace) = '2018-12-12'


$sql = "SELECT id_rezervace,datum_rezervace,rezervace_do FROM rezervace";


$result = $link->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

      /*  $letni=$row['letni'];
        $zimni=$row['zimni'];
        $znacka=$row['znacka'];
        $model=$row['model'];
        $spz=$row['spz'];
*/

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="css/rezervace.css">
    <link rel="stylesheet" href="css/main.css">

</head>
<H1>Rezervace</H1>


<?php
$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$week = (isset($_GET['week'])) ? $_GET['week'] : date('W');
if($week > 52) {
    $year++;
    $week = 1;
} elseif($week < 1) {
    $year--;
    $week = 52;
}
?>

<a class="malyBtn" href="<?php echo $_SERVER['PHP_SELF'].'?page=rezervace&week='.($week == 1 ? 52 : $week -1).'&year='.($week == 1 ? $year - 1 : $year); ?>">Předchozí týden</a> <!--Previous week-->
<a class="malyBtn" href="<?php echo $_SERVER['PHP_SELF'].'?page=rezervace&week='.($week == 52 ? 1 : 1 + $week).'&year='.($week == 52 ? 1 + $year : $year); ?>">Další týden</a> <!--Next week-->

<table border="1px" class="rozvrh">
    <tr class='trCas'>
        <td>Rezervace</td>
        <?php
        for($cas= 9; $cas <= 16; $cas++) {
            if ($cas<10){
                echo "<td >0".$cas.":00 </td>";
                echo "<td >0".$cas.":30 </td>";
            }
            else {
                echo "<td >" . $cas . ":00 </td>";
                echo "<td >" . $cas . ":30 </td>";
            }
        }
        ?>

    </tr>
    <?php
    if($week < 10) {
        $week = '0'. $week;
    }
    for($day= 1; $day <= 5; $day++) {
        $d = strtotime($year ."W". $week . $day);
        echo "<tr><td>". date('l', $d) ."<br>". date('d.m.', $d) ."</td>";

    $sql = "SELECT id_rezervace,datum_rezervace,rezervace_do FROM rezervace WHERE DATE(datum_rezervace) ='".date('o-m-d',$d). "'";
    $result = $link->query($sql);


        for($mez= 9; $mez <= 16.5; $mez=$mez+0.5) {
            $result->data_seek(0);
            $naslo=false;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {


                $datetimeZac = new DateTime($row['datum_rezervace']);
                 $casZac=$datetimeZac->format('G');
                  $minZac=$datetimeZac->format('i');

                 $datetimeKon = new DateTime($row['rezervace_do']);
                 $casKon=$datetimeKon->format('G');
                    $minKon=$datetimeKon->format('i');

                    if ($casZac==$mez && $minZac==0 ) {
                        $naslo=true;
                        $casMax=(($casKon-$casZac)*2);
                        if($casMax<1) {

                                echo "<td class='tdCas vyb' >" . $casZac . " </td>";
                            }
                        else {
                            if($minKon==0) {
                                echo "<td class='tdCas vyb' colspan='" . $casMax . "'>" . $casZac . " </td>";
                                $mez = $mez + (0.5 * $casMax)-0.5;
                            }
                            else{
                                echo "<td class='tdCas vyb' colspan='" . ($casMax+1) . "'>" . $casZac . " </td>";
                                $mez = $mez + (0.5 * $casMax);

                            }

                        }
                    }
                    else{
                        if($casZac==($mez-0.5) && $minZac!=0 ){
                            $naslo=true;
                            $casMax=(($casKon-$casZac)*2);
                            if($casMax<1) {

                                echo "<td class='tdCas vyb' >" . $casZac . " </td>";
                            }
                            else {
                                if($minKon==0) {
                                    echo "<td class='tdCas vyb' colspan='" . $casMax . "'>" . $casZac . " </td>";
                                    $mez = $mez + (0.5 * $casMax);
                                }
                                else{
                                    echo "<td class='tdCas vyb' colspan='" . ($casMax) . "'>" . $casZac . " </td>";
                                    $mez = $mez + (0.5 * $casMax)-0.5;
                                    echo "";
                                }

                            }
                        }
                    }





            }

        }
            if(!$naslo){
                echo "<td class='tdCas'></td>";
            }
    }



        echo "</tr>";
    }
    ?>

</table>