Es kann momentan nur elmamun.gap als Grammatik benutzt werden.
<br><br>

<form action="index.php" method="get">
Gib die Formel ein, deren Klammerstellung du mit Elmamun prüfen wilst <br> <input type="text" name="input" value='<?php echo isset($_GET['input']) ? $_GET['input'] : '' ?>'>
<br><br>
Wähle die Instanz mit den Algebren die du verwenden möchtest.
<select name="algebra">
<option <?php if ($_GET['algebra'] == 'pp') { echo " selected "; };?> value="pp" >alg_pretty</option>
<option <?php if ($_GET['algebra'] == 'enum') { echo " selected "; };?>value="enum">alg_enum</option>
<option <?php if ($_GET['algebra'] == 'sellerpp') { echo " selected "; };?>value="sellerpp">alg_seller * alg_pretty</option>
<option <?php if ($_GET['algebra'] == 'buyerpp') { echo " selected "; };?>value="buyerpp">alg_buyer * alg_pretty</option>
<option <?php if ($_GET['algebra'] == 'ppbuyer') { echo " selected "; };?>value="ppbuyer">alg_pretty * alg_buyer</option>
<option <?php if ($_GET['algebra'] == 'timepp') { echo " selected "; };?>value="timepp">alg_time * alg_pretty</option>
</select>
<br><br>
<!--
<input type="submit">
</form>
-->

<!--
<p>Algebra: 
<form action="" method="post">
<SELECT NAME="algebra1">
<OPTION value="alg_pretty" SELECTED>alg_pretty</OPTION><OPTION value="alg_enum">alg_enum</OPTION><OPTION value="alg_seller">alg_seller</OPTION><OPTION value="alg_buyer">alg_buyer</OPTION><OPTION value="alg_time">alg_time</OPTION>
</SELECT>
<input type="submit" name="submit" value="Choose.">
</form>
und das ist
<?php 
echo bla;
$algebra1 = $_POST['algebra1']; 
echo $algebra1;
?>
***
<form action="" method=post">
<SELECT NAME="algebra2">
<OPTION SELECTED 
<?php
if ($algebra1 == "alg_pretty") {
    echo 'value="-">"-"';
    echo '</OPTION><OPTION value= "alg_buyer">alg_buyer';
} elseif ($algebra1 == "alg_enum") {
    echo 'value="-">"-"';
} else {
    echo 'value="alg_pretty">alg_pretty';
}
?>
</OPTION></SELECT>
<input type ="submit" name="submit" value="Choose">
</form>
&nbsp;&nbsp;&nbsp;
<INPUT TYPE=SUBMIT NAME="CommandButton1" VALUE="Go!">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
-->

<!-- Instanzen in elmamun.gap:
        instance pp = gra_elmamun(alg_pretty);
        instance enum = gra_elmamun(alg_enum);
        instance sellerpp = gra_elmamun(alg_seller * alg_pretty);
        instance buyerpp = gra_elmamun(alg_buyer * alg_pretty);
        instance ppbuyer = gra_elmamun(alg_pretty * alg_buyer);
        instance timepp = gra_elmamun(alg_time * alg_pretty);
-->


Wähle aus welche Grammatik du verwenden willst.
<select name="Grammatikdatei">
    <option value="elmamun.gap">Elmamun</option>
</select>

<input type="submit">
</form>

<?php
$input = $_GET['input'];
echo "<pre>"; 
/*   alter Code
$algebra = $_GET['algebra']; 
if ($algebra == "alg_pretty") {
    $instance = pp;
}   elseif ($algebra == "alg_enum") {
    $instance = enum;
}   elseif ($algebra == "alg_seller * alg_pretty") {
    $instance = sellerpp;
}   elseif ($algebra == "alg_buyer * alg_pretty") {
    $instance = buyerpp;
}   elseif ($algebra == "alg_pretty * alg_buyer") {
    $instance = ppbuyer;
}   elseif ($algebra == "alg_time * alg_pretty") {
    $instance = timepp;
}
*/
$instance = $_GET['algebra'];
$Grammatikdatei = $_GET['Grammatikdatei'];
if (!file_exists($instance."gapc.cc")){
    shell_exec("gapc -i ".$instance." -o ".$instance."gapc.cc ".$Grammatikdatei);
}
shell_exec("make -f ".$instance."gapc.mf");
$result = shell_exec("./".$instance."gapc ". $input);
echo $result;
echo "</pre>";
?>
