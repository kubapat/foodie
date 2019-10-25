<?php
/*
  FILE: src/funcs/utils.php
  DESCRIPTION: This file contains common utility functions

  Methods:
   - strContains($arg1,$arg2) - returns true if $arg2 is contiguous subsequence of $arg1
   - updateActivity() - update last_active value in users table indicating date of last user activity
   - getUserIP() - returns IP Address in most reliable way without external plugin
   - getUserPhoto() - returns address of logged in user
   - getName($arg1) - returns name of user with login=$arg1
   - getId($arg1) - returns ID of user with login=$arg1
   - getLastActivity($arg1) - return Last Active dat of user with login=$arg1
   - verifyDate($arg1) - returns bool value depending if $arg1 is correct date in format YYYY-MM-DD HH:MM
   - verifyImage($arg1) - return bool value depending if $arg1 is correct URL of image
   - getLatLng($coords,$type) - separates Latitude or Longitude (marked at $type var) form $coords var
   - genKey() - generates random 7-sign key (used as privkey in `users` table in DB)
  Variables:
   - NONE
*/


  class Utilities {

    public function strContains($sentence, $word) {
       if(strpos($sentence,$word) !== false) return (bool)true;
       else return (bool)false;
    }

    public function updateActivity() {
       global $conn;
       if(!isset($_SESSION['logged'])) return;
       mysqli_query($conn,"UPDATE users SET `last_active`='".date('Y-m-d H:i:s')."' WHERE login='".addslashes($_SESSION['login'])."';");
    }

    public function getUserIP() {
       if(isset($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
       else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_X_FORWARDED'])) return $_SERVER['HTTP_X_FORWARDED'];
       else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
       else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) return $_SERVER['HTTP_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_FORWARDED'])) return $_SERVER['HTTP_FORWARDED'];
       else if(isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
       else return '127.0.0.1';
    }


    public function getUserPhoto() {
       global $conn;
       if(!$_SESSION['logged']) return;
       return mysqli_fetch_row(mysqli_query($conn,"SELECT `image` FROM users WHERE login='".addslashes($_SESSION['login'])."';"))[0];
    }

    public function getName($login) {
       global $conn;
       $query = mysqli_query($conn,"SELECT name FROM users WHERE login='".addslashes($login)."';");
       if(!mysqli_num_rows($query)) return "Inactive user";
       else return mysqli_fetch_row($query)[0];
    }

    public function getId($login) {
       global $conn;
       $query = mysqli_query($conn,"SELECT id FROM users WHERE login='".addslashes($login)."';");
       if(!mysqli_num_rows($query)) return "0";
       else return mysqli_fetch_row($query)[0];
    }

    public function getLastActivity($login) {
       global $conn;
       $query = mysqli_fetch_row(mysqli_query($conn,"SELECT last_active FROM users WHERE login='".addslashes($login)."';"))[0];
       if(!strlen($query) || $query == '2002-09-13 20:50:00') return Langs::translations[$_SESSION['lang']]["forum"]["never"];
       else return $query;
    }

    public function verifyDate($date) {
       //2002-09-13 20:50:00
       if(strlen($date) != 19) return false;
       if($date[4] != '-' || $date[7] != '-' || $date[13] != ':' || $date[16] != ':') return false;

       $year  = (int)substr($date,0,4);
       $month = (int)substr($date,5,2);
       $day   = (int)substr($date,8,2);
       $hour  = (int)substr($date,11,2);
       $mins  = (int)substr($date,14,2);
       $secs  = (int)substr($date,17,2);

       if(!checkdate($month,$day,$year)) return false;

       if($hour<0 || $hour>23) return false;
       if($mins<0 || $mins>59) return false;
       if($secs<0 || $secs>59) return false;

       return true;
    }

    public function verifyImage($file) {
       $size = getimagesize($file);
       return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
    }

    public function getLatLng($coords,$type) {
       $retVal = "";
       $comma  = false;

       for($i=0; $i<strlen($coords); $i++) {
          if($coords[$i] == ",") {
             $comma = true;
             continue;
          } else if($type == "lat" && !$comma) $retVal.=$coords[$i];
            else if($type == "lat" && $comma)  break;
            else if($type == "lng" && $comma)  $retVal.=$coords[$i];
       }

       return $retVal;
    }

    public function genKey() {
       $config['mode'] = array(true, true, true, true);
       $config['length'] = 7;
       $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $values  = '0123456789';
       $values .='';
       $values .= $letters;
       $values .= strtoupper($letters);

       for ($h = 0, $length = (strlen($values) - 1); $h < $config['length']; ++$h) $random_symbols .= substr($values, mt_rand(0, $length), 1);

       return $random_symbols;
    }


  }

?>
