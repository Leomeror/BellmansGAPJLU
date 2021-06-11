<?php
    session_start();
    if(!isset($_SESSION['Grammatikdatei'])){$_SESSION['Grammatikdatei']="";}
    if(!isset($_SESSION['algebras'])){$_SESSION['algebras']=["error"];}
    if(!isset($_GET['Grammatikdatei'])){$_GET['Grammatikdatei']="";}
    if(!isset($_GET['algebra1'])){$_GET['algebra1']="";}
    if(!isset($_GET['algebra2'])){$_GET['algebra2']="";}
    if(!isset($_GET['input'])){$_GET['input']="";}
?>
Es kann momentan nur elmamun.gap als Grammatik benutzt werden.
<br><br>

<form id="form1" action="index.php" method="get">
Wähle zuerst aus welche Grammatik du verwenden willst.<br>
Durch drücken auf "Parsen" werden dir weiter unten alle möglichen Algebren zur Auswahl angezeigt.<br>
Grammar:
<select name="Grammatikdatei" >
    <option <?php if ($_SESSION['Grammatikdatei'] == '-') { echo " selected "; };?> value="-">-</option>
    <?php
        $shellreturn = shell_exec('find . -type f -name "*.gap" -printf "%f\n"');
        $gapfilenames = explode("\n", $shellreturn);
        foreach($gapfilenames as $gapfilename){
        if ($gapfilename != ""){
            echo "<option ";
            if ($_SESSION['Grammatikdatei'] == $gapfilename) { echo " selected "; };
            echo 'value="'.$gapfilename.'">'.$gapfilename.'</option>';
        }
        }
    ?>
</select>
<!--
    Muss Seite davon abhalten, die shell_exec Befehle auszuführen, bis der untere Submit Button gedrückt wurde. DONE
    Der obere Submit-Button soll nur das Parsen aktivieren.
    Mi 2.6.2021-18:00-19:15 : 1 Stunde 15 min
    Do 3.6.2021 14:42-17:42 : 3 Stunden
    Sa 5.6.2021 Docker-Videos 11:30-13:10 : 1 Std. 40 min 
    Sa 5.6.2021 14:00-14:20 : 20 min
    Sa 5.6.2021 18:27-20:12 : 1 Std. 45 min
    Di 8.6.2021 16:20-16:45 : 25 min
    Fr 11.6.2021 11:32-
    
-->
<input type="submit" name="parse" value="Parsen">
<?php

    if(!isset($_SESSION['Grammatikdatei'])){
    $_SESSION['Grammatikdatei']=$_GET['Grammatikdatei'];}
    
    if($_SESSION['Grammatikdatei']==""){
    $_SESSION['Grammatikdatei']=$_GET['Grammatikdatei'];}
    
    if(isset($_GET['Grammatikdatei'])){
    $_SESSION['Grammatikdatei'] = $_GET['Grammatikdatei'];}
?>
<br>
</form>
<?php
    
    $Grammatikdatei = $_SESSION['Grammatikdatei'];
    
    if(!isset($algebras))
    {
        $algebras = [];
    }
    
    if(array_key_exists('parse',$_GET)){
        if($Grammatikdatei=="-"){
            echo '<span style="color:red">';
            echo "Wähle bitte eine Grammatik aus!";
            echo "</span>";
        }
        else{
            $gap_file= file_get_contents($Grammatikdatei);
            $rows = explode("\n", $gap_file);
            foreach($rows as $row){
                $row_content=explode(" ", $row);
                if($row_content[0]=="algebra"){
                    $algebras[] = $row_content[1];
                }
            }
        }
        $_SESSION['algebras']=$algebras;
    }
?>

<form id="form2" action="" method="get">
Wähle die Algebren die du verwenden möchtest.
<br>Algebra:

<select name="algebra1">
<?php
    foreach($_SESSION['algebras'] as $algebra){
        echo "<option ";
        if ($_GET['algebra1'] == $algebra) { echo " selected "; };
        echo 'value="'.$algebra.'">'.$algebra.'</option>';
    }
?>
</select>

***

<select name="algebra2">
<option <?php if ($_GET['algebra2'] == '-') { echo " selected "; };?> value="-">-</option>
<?php
    foreach($_SESSION['algebras'] as $algebra){
        echo "<option ";
        if ($_GET['algebra2'] == $algebra) { echo " selected "; };
        echo 'value="'.$algebra.'">'.$algebra.'</option>';
    }
?>
</select>
<br>

Und gib die Formel ein, deren Klammerstellung du mit Elmamun prüfen willst.<br>
Input:<input type="text" name="input" placeholder="2+1*3" value='<?php echo isset($_GET['input']) ? $_GET['input'] : '' ?>'>
(z.B.: 2+1*3)
<br>

&nbsp &nbsp

<input type="submit" name="submit">

</form>
<?php
    if(isset($_GET["submit"])){
        if(($_GET['input']!="")){
            echo '<span style="color:green">';
            echo "Die Einträge wurden an die Website übermittelt.";
            echo "</span>";
        }
        else{
            echo '<span style="color:red">';
            echo "Gib einen Input ein!";
            echo "</span>";
        }
    }
?>
<br><br>

<form method="post">
Mit Klicken auf "Submit" werden deine Einträge in der Website gespeichert.
<br>
Du kannst jetzt auf "RUN" klicken um alles mit dem gapc Compiler auszurechnen.
<br>
    <input type="submit" name="go" id="go" value="RUN" /><br/>
</form>

<?php
$input = $_GET['input'];
$algebra1 = $_GET['algebra1'];
$algebra2 = $_GET['algebra2'];

function useGapcSingleAlgebra($algebra1, $Grammatikdatei, $input)
{
echo "<pre>"; 
if (!file_exists($algebra1."_gapc.cc")){
    echo shell_exec('gapc -p '.$algebra1.' -o '.$algebra1.'_gapc.cc '.$Grammatikdatei.' 2>&1');
}
shell_exec("make -f ".$algebra1."_gapc.mf"." 2>&1");
$result = shell_exec("./".$algebra1."_gapc ".$input." 2>&1");
echo $result;
echo "</pre>";
}

function useGapcAlgebraProduct($algebra1, $algebra2, $Grammatikdatei, $input)
{
echo "<pre>"; 
$operator= '*';

if (!file_exists($algebra1."_".$algebra2."_gapc.cc")){
    echo shell_exec('gapc -p '.$algebra1.$operator.$algebra2.' -o '.$algebra1.'_'.$algebra2.'_gapc.cc '.$Grammatikdatei.' 2>&1');
}
shell_exec("make -f ".$algebra1."_".$algebra2."_gapc.mf"." 2>&1");
$result = shell_exec("./".$algebra1."_".$algebra2."_gapc ".$input." 2>&1");
echo $result;
echo "</pre>";
}

//if(array_key_exists('submit',$_GET)){
if(array_key_exists('go',$_POST)){
    //Ist unnötig, weil oben schon abgefragt:
    if($Grammatikdatei =="-"){
        $Grammatikdatei="";
    }
    //Wenn nur eine Algebra angegeben, benutze -p mit nur Algebra1(also: useGapcSingleAlgebra)
    if($algebra2 == "-"){
        useGapcSingleAlgebra($algebra1, $Grammatikdatei, $input);
    }
    else{
        useGapcAlgebraProduct($algebra1, $algebra2, $Grammatikdatei, $input);
    }
}

?>
