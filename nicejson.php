<?php

// original code: http://www.daveperrett.com/articles/2008/03/11/format-json-with-php/
// adapted to allow native functionality in php version >= 5.4.0

/**
* Formate a flat JSON string to make it more human-readable
*
* @param string $json The original JSON string to process
*        When the input is not a string it is assumed the input is RAW
*        and should be converted to JSON first of all.
* @return string Indented version of the original JSON string
*/
function json_format($json) {
  if (phpversion() && phpversion() >= 5.4) {
    return json_encode($json, JSON_PRETTY_PRINT);
  }
  if (!is_string($json)) {
    $json = json_encode($json);
  }
  $result      = '';
  $pos         = 0;
  $strLen      = strlen($json);
  $indentStr   = '  ';
  $newLine     = "\n";
  $prevChar    = '';
  $outOfQuotes = true;

  for ($i = 0; $i <= $strLen; $i++) {
    // Grab the next character in the string
    $char = substr($json, $i, 1);

    // Are we inside a quoted string?
    if ($char == '"' && $prevChar != '\\') {
      $outOfQuotes = !$outOfQuotes;
    }
    // If this character is the end of an element,
    // output a new line and indent the next line
    else if (($char == '}' || $char == ']') && $outOfQuotes) {
      $result .= $newLine;
      $pos--;
      for ($j = 0; $j < $pos; $j++) {
        $result .= $indentStr;
      }
    }
    // Add the character to the result string
    $result .= $char;

    // If the last character was the beginning of an element,
    // output a new line and indent the next line
    if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
      $result .= $newLine;
      if ($char == '{' || $char == '[') {
        $pos++;
      }
      for ($j = 0; $j < $pos; $j++) {
        $result .= $indentStr;
      }
    }
    $prevChar = $char;
  }

  return $result;
}

