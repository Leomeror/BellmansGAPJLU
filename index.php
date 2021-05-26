Es wird immer elmamun.gap für die Algebra und die Grammatik benutzt.
<br><br>
<form action="index.php" method="get">
Gib die Formel ein, deren Klammerstellung du mit Elmamun prüfen wilst <br> <input type="text" name="input">
<input type="submit">
</form>

<?php
$input = $_GET['input'];
echo "<pre>";
shell_exec("gapc elmamun.gap");
shell_exec("make -f out.mf");
$result = shell_exec("./out " . $input);
echo $result;
echo "</pre>";
?>
