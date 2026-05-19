<?php

namespace Framework;

class Validation {
   public static function string($value, $min = 1, $max = INF) {

    if (!is_string($value)) {
        return false;
    }

    $value = trim($value);
    $length = strlen($value);

    return $length >= $min && $length <= $max;
}



public static function email($value) {
  $value = trim($value);
  return filter_var($value, FILTER_VALIDATE_EMAIL);

}

public static function match($value1, 
$value2) {
    $value1 = trim($value1);
    $value2 = trim($value2);
    
    return $value1 === $value2;
}

}