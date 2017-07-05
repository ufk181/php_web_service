<?php
require_once 'class.sesion.php';

class Android_DB{
 //Veritabanı  Gereken Bilgiler.
    private $username ;
    private $password ;
    private $host ;
    private $database;

//Singleton File
 public static $Instance;
 //Database Wrapper
   private $connection;
   private $stmt ;

   //Hata tutucular
   private $error_handle ;
  //Session Tutucu
  private $session;

  public static function Instance(){
    if(!isset(self::$Instance)){
      self::$Instance  = new Android_DB();
    }
    return self::$Instance;
  }

  public function __construct(){
      if(extension_loaded('mysqli')){
        $this->username = 'root';
        $this->password = '';
        $this->host = 'localhost';
        $this->database = "test";
        self::Connecting2Mysql();
        $this->session  = SessionManager::Instance();
      }
else{
        die("Hata : Mysqli Kütüphanesi Yüklü değildir.");
      }
  } //  Construct

/**
*
*  Bağlantı Kurulumu
*/
 public function   Connecting2Mysql(){

     try{
           $this->connection = @mysqli_connect($this->host,$this->username,$this->password,$this->database);
                               @mysqli_query("SET CHARSET 'utf-8'");
            if(!$this->connection){
               throw new Exception("Hata : "  . mysqli_connect_error());
            }
      return $this->connection;
     }
     catch(Exception $e){
         print $e->getMessage();
     }
 }

 //Kullanıcıyı Alır ve Saklar.

public function storeUser($k_adi , $k_sifre ,$k_email)
{
      $uuid = uniqid('',true);
      $y_sifre = $this->__encryptpassword($k_sifre);
      $y_kripto_sifre  = $y_sifre['encrypt'];
      $salt = $y_sifre['saf'];
      $tarih = @date("d.m.y");

   try{

         $this->stmt = $this->connection->prepare("SELECT name,email FROM login WHERE name=? AND email=?");
         $this->stmt->bind_param('ss',$k_adi,$k_email);
         $this->stmt->execute();
         $sonuc = $this->stmt->get_result()->fetch_assoc();
        if (  isset($sonuc)   ) {
         return   json_encode(array('error' => 'user is have','return' => 'false'));
        } else {
         $Insert =  self::__userAdd($k_adi,$y_kripto_sifre,$k_email,$tarih);
        // $retVal = ($Insert) ?  json_encode(array('error' => 'Kullanıcı Eklendi', 'return' => 'true')) :  json_encode(array('error' => 'Kullanıcı Eklenemedş', 'return' => 'false')); ;

            if($Insert['insert_id'] > 0){
             return json_encode(array('error' => 'User is Adding', 'return' => 'true'));
        }
        else{
             return   json_encode(array('error' => 'User is  dosn\'t Add.', 'return' => 'false'));
        }


        }

   }catch(Exception $e) {
    return $e->getMessage();
   }
} // storeUser

 // Şifre Üretir.
 // return $last_sifre
private function __encryptpassword($hash){
   $salt = sha1(rand());
   $salt = (strlen($salt)  <= 8 )  ? substr($salt,0,strlen($salt)) : substr($salt,0,8);
   $sifre = base64_encode(sha1($hash.$salt,true). $salt);
   $last_sifre   = array('saf' => $hash, 'encrypt' => $sifre);
   return $last_sifre;
}
  private function __userAdd($k_adi,$k_sifre,$k_email,$tarih){
       $this->stmt = $this->connection->prepare("INSERT INTO login (id,name,password,email,tarih) VALUES(NULL,?,?,?,?)");
       $this->stmt->bind_param('ssss',$k_adi,$k_sifre,$k_email,$tarih);
       $this->stmt->execute();
       $insert_id = $this->connection = mysqli_insert_id($this->connection);
       return  array('status' => 'Ekleme Ok !','insert_id'  => $insert_id);
   }

public function testJsontoWebService($name){
       return  json_encode(array('name' => $name));

}
public function LoginUser($username,$password){
     $password = $this->__encryptpassword($password);
     $this->stmt  = $this->connection->prepare("SELECT name,password FROM login WHERE name=? AND password=?");
     $this->stmt->bind_param('ss',$username,$password);
     $this->stmt->execute();
     $sonuc_user = $this->stmt->get_result()->fetch_assoc();
     print_r($sonuc_user);
     if(isset($sonuc_user)){
        $this->session->create_session('Login_user',true);
        $this->session->setSession('Login_user', array('isim' => $sonuc_user['name']));
        $this->session->setSessionID();

     }




}
    
    public function GetInfoByUser($user){
         
         $isim  = $this->session->getSession('isim');
        $this->stmt->connection->prepare("SELECT * FROM login WHERE name=?");
        $this->stmt->bind_param('s',$isim);
        $this->stmt->execute();
        $sonuc = $this->stmt->get_result()->fetch_assoc();
        return $sonuc;
        
        
    }
    
    

} // End of Line Class

$a = Android_DB::Instance();
$a->LoginUser('ufuk','886412');
//TODO: Daha bir sürü özellik eklenecektir.

?>
