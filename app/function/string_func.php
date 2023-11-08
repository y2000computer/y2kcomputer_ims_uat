<?php
// Portal share common string function
function _utf8_substr($str,$from,$len){
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}

function _utf8_strlen($str) {
  $count = 0;
  for ($i = 0; $i < strlen($str); ++$i) {
    if ((ord($str[$i]) & 0xC0) != 0x80) {
      ++$count;
    }
  }
  return $count;
}
function print_ar($array, $count=0) {
    $i=0;
    $tab ='';
    while($i != $count) {
        $i++;
        $tab .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
    }
    foreach($array as $key=>$value){
        if(is_array($value)){
            echo $tab."[<strong><u>$key</u></strong>]<br />";
            $count++;
            print_ar($value, $count);
            $count--;
        }
        else{
            $tab2 = substr($tab, 0, -12);
            echo "$tab2~ $key: <strong>$value</strong><br />";
        }
        $k++;
    }
    $count--;
}
function print_r_html($data,$return_data=false)
{
    $data = print_r($data,true);
    $data = f_str_replace( " ","&nbsp;", $data);
    $data = f_str_replace( "\r\n","<br>\r\n", $data);
    $data = f_str_replace( "\r","<br>\r", $data);
    $data = f_str_replace( "\n","<br>\n", $data);

    if (!$return_data)
        echo $data;    
    else 
        return $data;
}

function print_r_html_v2($data,$return_data=false)
{
    $data = print_r($data,true);
    $data = f_str_replace( " ","&nbsp;", $data);
    $data = f_str_replace( "\r\n","<br>\r\n", $data);
    //$data = f_str_replace( "\r","<br>\r", $data);
    //$data = f_str_replace( "\n","<br>\n", $data);

    if (!$return_data)
        echo $data;    
    else 
        return $data;
}

function print_r_html_v3($data,$return_data=false)
{
    $data = print_r($data,true);
    $data = f_str_replace( " ","&nbsp;", $data);
    //$data = f_str_replace( "\r\n","<br>\r\n", $data);
    $data = f_str_replace( "\r","<br>\r", $data);
    $data = f_str_replace( "\n","<br>\n", $data);

    if (!$return_data)
        echo $data;    
    else 
        return $data;
}

function mb_f_str_replace($needle, $replacement, $haystack) {
    return implode($replacement, mb_split($needle, $haystack));
 }
 

function mb_trim($str, $charlist = NULL, $encoding = NULL) {
    if ($encoding === NULL) {
        $encoding = mb_internal_encoding(); // Get internal encoding when not specified.
    }
    if ($charlist === NULL) {
        $charlist = "\\x{20}\\x{9}\\x{A}\\x{D}\\x{0}\\x{B}"; // Standard charlist, same as trim.
    } else {
        $chars = preg_split('//u', $charlist, -1, PREG_SPLIT_NO_EMPTY); // Splits the string into an array, character by character.
        foreach ($chars as $c => &$char) {
            if (preg_match('/^\x{2E}$/u', $char) && preg_match('/^\x{2E}$/u', $chars[$c+1])) { // Check for character ranges.
                $ch1 = hexdec(substr($chars[$c-1], 3, -1));
                $ch2 = (int)substr(mb_encode_numericentity($chars[$c+2], [0x0, 0x10ffff, 0, 0x10ffff], $encoding), 2, -1);
                $chs = '';
                for ($i = $ch1; $i <= $ch2; $i++) { // Loop through characters in Unicode order.
                    $chs .= "\\x{" . dechex($i) . "}";
                }
                unset($chars[$c], $chars[$c+1], $chars[$c+2]); // Unset the now pointless values.
                $chars[$c-1] = $chs; // Set the range.
            } else {
                $char = "\\x{" . dechex(substr(mb_encode_numericentity($char, [0x0, 0x10ffff, 0, 0x10ffff], $encoding), 2, -1)) . "}"; // Convert the character to it's unicode codepoint in \x{##} format.
            }
        }
        $charlist = implode('', $chars); // Return the array to string type.
    }
    $pattern = '/(^[' . $charlist . ']+)|([' . $charlist . ']+$)/u'; // Define the pattern.
    return preg_replace($pattern, '', $str); // Return the trimmed value.
}


function print_paragraph_html_v1($data,$return_data=false)
{
    $data = print_r($data,true);
    $data = f_str_replace( "\r\n","<br>\r\n", $data);
    //$data = f_str_replace( "\n","<br>\n", $data);

    if (!$return_data)
        echo $data;    
    else 
        return $data;
}


// FORMATTING FUNCTIONS
function f_html_escape($text)
{
    $text = $text ?? '';  // If the value passed into function is null set $text to a blank string
    //return f_html_escape($text, ENT_QUOTES, 'UTF-8', false); // Return escaped string
    return htmlspecialchars($text); // Return escaped string
}


function f_str_replace($text)
{
    $text = $text ?? '';  // If the value passed into function is null set $text to a blank string
    return str_replace("/", "", $text); // Return escaped string
}



?>