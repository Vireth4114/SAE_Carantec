<?php
$data = view('safetySheet')->render();
$htmlContent = htmlentities($data);
echo $htmlContent;
?>