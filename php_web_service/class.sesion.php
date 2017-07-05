<?php

/**
* Session  Handle Class
*
*/

class  SessionManager{

       //Session Path Config
       private  $session_save_path = NULL ;
       private  $session_expire_times =  '3600';

       //Session Data key
       private $session_name;
       private $session_id;
       // Static Variables
       public static $Instance = NULL ;


       public static function Instance(){
       if(!isset(self::$Instance)){
         self::$Instance  = new SessionManager();
       }

        if (!isset($_SESSION)) {
        self::initsession();
        } // Sess Oluştur.
        return self::$Instance;
     }


    /**
     *  Session Oturumunu başlatır.
     */

 private function initsession(){
       session_start();
 }


 /**
 * Session Değişkenini hazırlar. Session'a Array'mı yoksa string mi olduğunu belirler.
 */
 public function createSession($session_name, $is_array = false){
       if(!isset($_SESSION[$session_name])){
         if(isset($is_array)){
           $_SESSION[$session_name] = array();
         }
         else{
           $_SESSION[$session_name] = '';
         }
       }
 }

   //Session id'yi çeker.
 public function getSessionID(){
     return $this->session_id;
 }


 //session id'yi set eder.
 public function setSessionID(){
    $this->session_id = session_id();
 }

/**
*  sessionları yok eder.
**/
 public function sessionDestroy($session_name, $all = false){
 if(isset($all)){
   if(isset($_SESSION[$session_name])){
      unset($_SESSION[$session_name]);
   }
   else
   {
     self::SessionAllDestroy();
   }
 }
}

    /**
     * Session All Destroy
     */
 private function SessionAllDestroy(){
     session_destroy();
 }

/**
* Session da tutulan verileri getirir.
* return  mixed @$sessionname 
*/
public function getSessionData($sessionname){
    return  $_SESSION[$sessionname];
}

public function setSessionData($session_name,$data){
    return $_SESSION[$session_name] = $data;
}






}// End of Line







?>
