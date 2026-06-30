<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php 
    $rowsLimit = 25;
    $colsLimit = 25;

    $generation = 1;

    ?>

    <table> 
        <tbody
        

    <?php

    for ($row = 0; $row < $rowsLimit; $row++) {
        echo '<tr data-row="'.$row.'">';
        for ($col = 0; $col < $colsLimit; $col++) {
            echo '<td class="dead" data-cols="'.$col.'">';

            echo '</td>';
        }
        echo "</tr>";
    
    }

    ?>

        </tbody>
    </table>





    
</body>
</html>