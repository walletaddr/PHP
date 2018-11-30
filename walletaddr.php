<?php
/**
 * Website: http://walletaddr.com
 * GitHub: http://github.com/walletaddr
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Robert Huang <https://github.com/sagwwaa>
 * @version 1.0
 */

function btc_shortcode($address) {
  // remove the first char.  BTC address always begins with 1 or 3.
  // It represents a destination for bitcoin payment.
  $address = substr($address, 1);

  $alphabets = preg_replace("/(?![A-Za-z])./", "", $address);
  preg_match_all('/[A-Za-z]+/', $address, $matchesAlphabets);
  preg_match_all('/[A-Z]+/', $address, $matchesUppercase);
  preg_match_all('/[a-z]+/', $address, $matchesLowercase);
  preg_match_all('/\d+/', $address, $matchesNumbers);

  $num1 = $num2 = $num3 = "";
  $numPos1 = $numPos2 = $numPos3 = 0;
  $numDash1 = $numDash2 = "";
  $letter1 = $letter2 = $letter3 = $letter4 = "";
  $letterPos1 = $letterPos2 = $letterPos3 = $letterPos4 = 0;
  $letterDash1 = $letterDash2 = $letterDash3 = "";
  $letterDashPos1 = $letterDashPos2 = $letterDashPos3 = 0;

  $numberFirst = false;
  if (is_numeric(substr($address, 0, 1))) {
    $numberFirst = true;
  }
  $uppercaseFirst = false;
  if (ctype_upper(substr($alphabets, 0, 1))) {
    $uppercaseFirst = true;
  }
  $uppercaseLast = false;
  if (ctype_upper(substr($alphabets, -1))) {
    $uppercaseLast = true;
  }

  // numeric set
  if (isset($matchesNumbers[0][0])) {
    $num1 = $matchesNumbers[0][0];
  }
  if (count($matchesNumbers[0]) > 2) {
    $numericsRange = array_slice($matchesNumbers[0], 1, count($matchesNumbers[0]) - 2);
    $duplicates = array_unique(array_diff_assoc($numericsRange, array_unique($numericsRange)));
    $highestUniqueNum = 0;
    foreach($numericsRange as $number) {
      if ($number > $highestUniqueNum && !in_array($number, $duplicates)) {
        $highestUniqueNum = $number;
      }
    }

    if ($highestUniqueNum > 0) {
      $num2 = $highestUniqueNum . "";
    } else {
      $num2 = "";
    }


  }
  if (count($matchesNumbers[0]) >= 2 && isset($matchesNumbers[0][count($matchesNumbers[0]) - 1])) {
    $num3 = $matchesNumbers[0][count($matchesNumbers[0]) - 1];
  }

  if ((strlen($num1) + strlen($num2)) > 4) {
    $numDash1 = "-";
  }
  if (strlen($numDash1) > 0) {
    if ((strlen($num2) + strlen($num3)) > 4) {
      $numDash2 = "-";
    }
  } else {
    if ((strlen($num1) + strlen($num2) + strlen($num3)) > 4) {
      $numDash2 = "-";
    }
  }

  // letter set
  if ($uppercaseFirst) {
    $letter1 = $matchesUppercase[0][0];
    $letterPos1 = strpos($address, $letter1);
    $letter2 = $matchesLowercase[0][0];
    $letterPos2 = strpos($address, $letter2);
  } else {
    $letter1 = $matchesLowercase[0][0];
    $letterPos1 = strpos($address, $letter1);
    $letter2 = $matchesUppercase[0][0];
    $letterPos2 = strpos($address, $letter2);
  }

  if ($uppercaseLast) {
    $letter3 = $matchesLowercase[0][count($matchesLowercase[0]) - 1];
    $letterPos3 = strrpos($address, $letter3);
    $letter4 = $matchesUppercase[0][count($matchesUppercase[0]) - 1];
    $letterPos4 = strrpos($address, $letter4);
  } else {
    $letter3 = $matchesUppercase[0][count($matchesUppercase[0]) - 1];
    $letterPos3 = strrpos($address, $letter3);
    $letter4 =  $matchesLowercase[0][count($matchesLowercase[0]) - 1];
    $letterPos4 = strrpos($address, $letter4);
  }

  if (($letterPos2 - ($letterPos1 + strlen($letter1) - 1)) > 1) {
    $letterDash1 = "-";
  }
  if (($uppercaseFirst && !$uppercaseLast) || (!$uppercaseFirst && $uppercaseLast)) {
    $letterDash2 = "-";
  }
  if ($letterPos3 > $letterPos4) {
    if (($letterPos3 - ($letterPos4 + strlen($letter4) - 1)) > 1) {
      $letterDash3 = "-";
    }
  } else {
    if (($letterPos4 - ($letterPos3 + strlen($letter3) - 1)) > 1) {
      $letterDash3 = "-";
    }
  }


  // combine
  $shortCode = array();
  $arrNumericCode = array();
  $arrAlphabetsCode = array();

  array_push($arrAlphabetsCode, $letter1);
  array_push($arrAlphabetsCode, $letterDash1);
  array_push($arrAlphabetsCode, $letter2);
  array_push($arrAlphabetsCode, $letterDash2);
  array_push($arrAlphabetsCode, $letter3);
  array_push($arrAlphabetsCode, $letterDash3);
  array_push($arrAlphabetsCode, $letter4);

  array_push($arrNumericCode, $num1);
  array_push($arrNumericCode, $numDash1);
  array_push($arrNumericCode, $num2);
  array_push($arrNumericCode, $numDash2);
  array_push($arrNumericCode, $num3);

  if ($numberFirst) {
    $shortCode = array_merge($shortCode, $arrNumericCode);
    array_push($shortCode, " ");
    $shortCode = array_merge($shortCode, $arrAlphabetsCode);
  } else {
    $shortCode = array_merge($shortCode, $arrAlphabetsCode);
    array_push($shortCode, " ");
    $shortCode = array_merge($shortCode, $arrNumericCode);
  }

  return implode("", $shortCode);

}


?>
