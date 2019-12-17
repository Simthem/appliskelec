<?php

function calc_hours($rm_25, $rm_50, $rh_n_tot) {

    echo '<td class="align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
        $m25 = $rm_25;
        if ($m25 > 0) {
            echo $m25;
            echo ' <br /> = ';
            echo $m25 + ($m25 / 4);
            echo ' maj.';
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';
    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">';
        $m50 = $rm_50;
        if ($m50 > 0) {
            echo number_format($m50, 1);
            if ($rh_n_tot != 0) {
                $hnight = number_format($rh_n_tot, 1) . ' h';
                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
            } 
            echo '<br />= ';
            echo $m50 + ($m50 / 2) . ' maj.';
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';

    return true;
}

?>