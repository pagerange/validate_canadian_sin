<?php

/**
  * Validate Canadian SIN - plain PHP
  * Uses LUHN/Mod-10
  * @author Steve George <steve@pagerange.com>
  * @updated 2017-09-17
 */
function validate_sin($num) {
    $sin = preg_replace('/[^0-9]/s', '', $num);
    $doubled = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];
    $total = 0;
    for($i = 0; $i < strlen($sin); $i++) {
        $digit = (int) $sin[$i];
        $total += ($i % 2) ? $doubled[$digit] : $digit ;
    }
    return ((int) $sin && $total % 10 === 0) ? true : false;
}
