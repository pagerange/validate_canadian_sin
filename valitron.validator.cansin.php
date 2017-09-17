<?php

/**
  * Validate Canadian SIN - Valtron\Validator version
  * Uses LUHN/Mod-10
  * @author Steve George <steve@pagerange.com>
  * @updated 2017-09-17
 */
Valitron\Validator::addRule('canSin', function($field, $value) {
    $sin = preg_replace('/[^0-9]/s', '', $value);
    $doubled = [0, 2, 4, 6, 8, 1, 3, 5, 7, 9];
    $total = 0;
    for($i = 0; $i < strlen($sin); $i++) {
        $digit = (int) $sin[$i];
        $total += ($i % 2) ? $doubled[$digit] : $digit ;
    }
    return ((int) $sin && $total % 10 === 0) ? true : false;
}, 'Please enter a valid Canadian Social Insurance Number');