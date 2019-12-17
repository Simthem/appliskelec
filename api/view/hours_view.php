<?php

function calc_hours($total, $rm_25, $rm_50, $rh_n_tot, $absence) {


    $m25 = $rm_25;

    $m25_ok = $m25 + ($m25 / 4); 

    $m50 = $rm_50;

    $m50_ok = $m50 + ($m50 / 2);

    $flag = 0;

    if ($absence != 0) {
        $flag_50 = 1;
        if ($m50_ok >= 0) {
            if ($m50_ok - $absence <= 0) {
                if ($m25_ok >= 0) {
                    $flag_25 = 1;
                    $m50_ok = 0;
                    $ab -= $m50;
                    $m25_ok -= $absence;
                    if ($m25_ok >= 0 && isset($total)) {
                        $m25_ok = 0;
                        $total -= $absence;
                    }
                }
            } else {
                $m50_ok -= $absence;
                $absence = 0;
            }
        }
    }

    echo '<td class="small align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
        if ($m25 > 0) {
            echo $m25;
            echo ' <br /> = ';
            if ($flag_25 != 0) {
                echo $m25_ok . ' maj.-abs.';
            } else {
                echo $m25_ok . ' maj.';
            }
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';
    echo '<td class="small align-middle p-1 w-25" style="word-wrap: break-word;">';
        if ($m50 > 0) {
            echo number_format($m50, 1);
            if ($rh_n_tot != 0) {
                $hnight = number_format($rh_n_tot, 1) . ' h';
                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
            } 
            //echo '';
            if ($flag_50 != 0) {
                echo '<div>= ' . $m50_ok . ' maj.-abs.</div>';
            } else {
                echo '<div>= ' . $m50_ok . ' maj.</div>';
            }
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';

    return true;
}

?>