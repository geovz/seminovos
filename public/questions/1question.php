<?php

 
 function equation($a=1, $b=1, $c = 0){
     
    if(!validade($a,$b,$c)){
        echo 'Valor invalido';
        exit();
    }
    $delta = ($b*$b)-((4*$a)*$c); 
    if($delta<0){
        echo "Impossivel calcular o valor,
        delta negativo {$delta}";
        exit();
    }
    $x1 = (-$b + sqrt ($delta)) / (2 * $a);
    $x2 = (-$b - sqrt ($delta)) / (2 * $a);
    echo "x1 = {$x1}<br>";
    echo "x2 = {$x2}";
}
function validade($a,$b,$c){
   if(!(is_numeric($a) && is_numeric($b) && is_numeric($c))){   
    return false;
   }
    return true;     

}
equation(4,0,1);