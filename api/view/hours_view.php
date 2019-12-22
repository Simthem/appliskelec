<?php

function value_h($pretotal, $rm_25, $rm_50, $absence, $temp) {

    $m25 = $rm_25;

    $m25_ok = $m25 + ($m25 / 4); 

    $m50 = $rm_50;

    $m50_ok = $m50 + ($m50 / 2);

    $flag = 0;
    $flag_25 = 0;
    $flag_50 = 0;

    if ($temp == 1) {
        $total = $pretotal;

        if ($absence != 0) {
            $flag_50 = 1;

            if ($m50_ok > 0) {

                if ($m50_ok - $absence <= 0) {

                    if ($m25_ok >= 0) {
                        $flag_25 = 1;
                        $m50_ok = 0;
                        $absence -= $m50;
                        $m25_ok -= $absence;
                        if ($m25_ok >= 0 && isset($total)) {
                            $m25_ok = 0;
                            $total -= $absence;
                        }
                    }
                } else {
                    $m50_ok -= $absence;
                }
            } elseif ($m25_ok > 0) {
                if ($m25_ok - $absence <= 0) {
                    if ($total >= 0) {
                        $m25_ok = 0;
                        $total -= $absence;
                    }
                } else {
                    $m25_ok -= $absence;
                }
            } elseif ($total >= 0) {
                $total -= $absence;
            }
        }
    }

    if (isset($total) && !empty($total)) {
        return array($total, $m25, $flag_25, $m25_ok, $m50, $flag_50, $m50_ok, $absence, $temp);
    } else {
        return array($pretotal, $m25, $flag_25, $m25_ok, $m50, $flag_50, $m50_ok, $absence, $temp);
    }
}

function calc_hours($pretotal, $rm_25, $rm_50, $rh_n_tot, $absence, $temp) {

    $result = value_h($pretotal, $rm_25, $rm_50, $absence, $temp);

    echo '<td class="small align-middle p-1 w-25" style="word-wrap: break-word;">';
        echo $result[0];
    echo '</td>';

    echo '<td class="small align-middle border-left border-right p-1 w-25" style="word-wrap: break-word;">';
        if ($result[1] > 0) {

            echo $result[1];
            echo ' <br /> = ';
            
            if (!isset($result[2]) && (!empty($result[2]) || $result[2] != 0)) {
                echo $result[3] . ' maj.-abs.';
            } else {
                echo $result[3] . ' maj.';
            }
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';
    echo '<td class="small align-middle p-1 w-25" style="word-wrap: break-word;">';
        if ($result[4] > 0) {

            echo number_format($result[4], 1);

            if ($rh_n_tot != 0) {
                $hnight = number_format($rh_n_tot, 1) . ' h';
                echo '<div class="d-inline small text-success">&nbsp;&nbsp;&nbsp;[nuit =&nbsp;' . $hnight . ']</div>';
            }
            if (isset($result[5]) && !empty($result[5]) || $result[5] != 0) {
                echo '<div>= ' . $result[6] . ' maj.-abs.</div>';
            } else {
                echo '<div>= ' . $result[6] . ' maj.</div>';
            }
        } else {
            echo '<div class="bg-secondary w-100 h-100"></div>';
        }
    echo '</td>';

    return true;
}

function convert_h($dec_h) {
    
    $total = $dec_h;
    $hours = (int)$total;
    $minutes = ($total - $hours) * 60;

    if ($minutes > 59) {
        $hours += 1;
        $minutes -= 60;
    }
    if ($minutes > 10) {
        $minutes = $minutes;
    } elseif ($minutes < 10 and $minutes > 0) {
        $minutes = "0" . $minutes;
    } else {
        $minutes = "00";
    }
    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $hours . ':' . $minutes . '</td>';
}

?>