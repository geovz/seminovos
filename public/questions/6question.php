
<?php
/**
 * Em sudoku, o objetivo � preencher uma grade 9x9 subdivida em quadrantes 3x3
com n�meros de 1 a 9 de tal forma que n�o hajam n�meros repetidos em uma
mesma coluna, linha ou quadrante. Escreva um procedimento que gere uma matriz
9x9 v�lida como resultado de sudoku considerando uma grade vazia.
 */
$n = 3;
$field = [];
for ($i = 0; $i < $n*$n; $i++)
    for ($j = 0; $j < $n*$n; $j++)
        $field[$i][$j] = ($i*$n + $i/$n + $j) % ($n*$n) + 1;

    for ($i = 0; $i <= count($field); $i++){
        for ($j = 0; $j <= count($field); $j++){
            if($j == 9){
                echo "<br>";
            }
            echo $field[$i][$j];

        }
    }
/**
 * Segue uma matriz valida de Sudoku, devido �o prazo n�o conseguir
 * implementar um front end para exibir de uma forma melhor o resultado
 *
 */
?>
