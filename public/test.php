<?php
$source_code = file('https://dev-sae301grp5.users.info.unicaen.fr/safetyDataSheet');

// 1. traversing through each element of the array 
// 2.printing their subsequent HTML entities 
foreach ($source_code as $line_number => $last_line) {
    echo nl2br(htmlspecialchars($last_line) . "\n");
}

?>