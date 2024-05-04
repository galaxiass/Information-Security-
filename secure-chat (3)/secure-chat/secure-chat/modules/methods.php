<?php

class RSA{

  function __construct() {}

  function findRandomPrime($p)
  {
      $min = 2;
      $max = 999;
      for ($i=rand($min, $max); $i < $max; $i++) {
          if ($this->isPrime($i) && $i != $p) {
              return $i;
          }
      }
  }
  
  function isPrime($num){
      if($num % 2 == 0) {
          return false;
      }
      
      for($i = 3; $i <= ceil(sqrt($num)); $i = $i + 2) {
          if($num % $i == 0)
              return false;
      }
      return true;
  }

  function compute_n($p, $q){
    return $p * $q;
  }

  function eular_z($p, $q){
    return ($p - 1) * ($q - 1);
  }

  function find_e($z){
    for($i = 2; $i < $z; $i++){
      if($this->coprime($i, $z)){
        return $i;
      }
    }
  }

  function gcd($e, $z){
      if ($e == 0 || $z == 0)
          return 0;

      if ($e == $z)
          return $e;

      if ($e > $z)
          return $this->gcd($e - $z, $z);

      return $this->gcd($e, $z - $e);
  }

  function coprime($e, $z){
      if ($this->gcd($e, $z) == 1)
        return true;
      return false;
  }

  function find_d($e, $z) {
    for($d=1;;$d++){
      if(($d * $e % $z) == 1){
        return $d; // Mod Inverse - Better
      }
    }
  }

  function encrypt($m, $e, $n){
    $c = "";
    $newChar = "";
    $everySeparate = "";
    for($i = 0; $i < strlen($m); $i++){
      $newChar = bcpowmod(ord($m[$i]), $e, $n);     // Log may works
      $everySeparate.=strlen($newChar);
      $c.=$newChar;
    }
    return array($c, $everySeparate);
  }

  function decrypt($c, $d, $n, $everySeparate){
    $m = "";
    // echo strlen($c)."  ".$everySeparate[0]."  ".$everySeparate[1]. "  ";
    for($i = 0, $ct = 0; $i < strlen($c); $i+=$everySeparate[$ct], $ct++){
      $cc = $this->getTheCurrentChar($c, $i, $everySeparate[$ct]);
      // echo $cc. "  ";
      $m.=chr(bcpowmod($cc, $d, $n));
    }
    return $m;
  }

  function getTheCurrentChar($c, $from, $to){
    $current = "";
    for($i = 0, $j = $from; $i < $to; $i++, $j++){
      $current.=$c[$j];
    }
    return intval($current);
  }
}
?>