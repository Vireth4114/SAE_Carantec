<?php
    foreach($dives as $dive) {
        $date = $dive->DIV_DATE;

        echo "<input type='checkbox'>";
        echo date_format($date, 'l j F Y');
        echo " de ".$dive->period->PER_LABEL."<br>";
        echo "  Site prevu : ".$dive->site->SIT_NAME;
        echo "  (".$dive->site->SIT_DESCRIPTION.")";
        echo "<br>";
    }
?>
