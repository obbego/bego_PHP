<?php
function passaValore($numero1, $numero2){
    $numero1 = $numero1+100;
    $numero2 = $numero2+100;
}
function passaIndirizzo(&$numero1, &$numero2){
    $numero1 = $numero1+100;
    $numero2 = $numero2+100;
}
$a=20;
$b=30;
echo"<HR> Prima della chiamata a passaValore<BR>";
echo"$a, $b <br>";
passaValore($a, $b);
echo "$a, $b <br>";
passaIndirizzo($a, $b);
echo "$a, $b <br>";
