<?php
// This class has a constructor to connect to a database. The given
// code assumes you have created a database named 'quotes' inside MariaDB.
//
// Call function startByScratch() to drop quotes if it exists and then create
// a new database named quotes and add the two tables (design done for you).
// The function startByScratch() is only used for testing code at the bottom.
// 
// Authors: Rick Mercer and Ryan Rizzo
//
class DatabaseAdaptor {
  private $DB; // The instance variable used in every method below
  // Connect to an existing data based named 'first'
  public function __construct() {
    $dataBase ='mysql:dbname=quotes;charset=utf8;host=127.0.0.1';
    $user ='root';
    $password =''; // Empty string with XAMPP install
    try {
        $this->DB = new PDO ( $dataBase, $user, $password );
        $this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch ( PDOException $e ) {
        echo ('Error establishing Connection');
        exit ();
    }
  }
    
// This function exists only for testing purposes. Do not call it any other time.
public function startFromScratch() {
  $stmt = $this->DB->prepare("DROP DATABASE IF EXISTS quotes;");
  $stmt->execute();
       
  // This will fail unless you created database quotes inside MariaDB.
  $stmt = $this->DB->prepare("create database quotes;");
  $stmt->execute();

  $stmt = $this->DB->prepare("use quotes;");
  $stmt->execute();
        
  $update = " CREATE TABLE quotations ( " .
            " id int(20) NOT NULL AUTO_INCREMENT, added datetime, quote varchar(2000), " .
            " author varchar(100), rating int(11), flagged tinyint(1), PRIMARY KEY (id));";       
  $stmt = $this->DB->prepare($update);
  $stmt->execute();
                
  $update = "CREATE TABLE users ( ". 
            "id int(6) unsigned AUTO_INCREMENT, username varchar(64),
            password varchar(255), PRIMARY KEY (id) );";    
  $stmt = $this->DB->prepare($update);
  $stmt->execute(); 
}
    

// ^^^^^^^ Keep all code above for testing  ^^^^^^^^^


/////////////////////////////////////////////////////////////
// Complete these five straightfoward functions and run as a CLI application


    public function getAllQuotations() {
        $sql = "SELECT * FROM quotations ORDER BY rating DESC";
        $result = [];
        $i = 0;
        foreach ($this->DB->query($sql) as $row){
            $result[$i] = $row;
            $i++;
        }
        return $result;
    }
    
    public function getAllUsers(){
        $sql = "SELECT * from users";
        $result = [];
        $i = 0;
        foreach ($this->DB->query($sql) as $row){
            $result[$i] = $row;
            $i++;
        }
        return $result;
    }
    
    public function addQuote($quote, $author) {
        $sql = "INSERT INTO quotations (added, quote, author, flagged, rating)
        VALUES (NOW(),'" . $quote . "', '" . $author . "', '0', '0')";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute ();
    }
    
    public function addUser($accountname, $psw){
        $sql = "INSERT INTO users (username, password)
        VALUES ('" . $accountname . "', '" . $psw . "')";
        $this->DB->query($sql);
    }   


    public function verifyCredentials($accountName, $psw){
        $sql = "SELECT password FROM users WHERE username= '" . $accountName . "'";
        foreach ($this->DB->query($sql) as $row){
            if ($row["password"] == $psw){
                return true;
            }
        } return false;
    }
    // Raise the rating of the quote with the given $ID by 1
    public function raiseRating($ID) {

        $query =  "UPDATE quotations SET rating=rating+1 WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare ($query);
        $stmt->execute ();
    }
    public function lowerRating($ID) {
        $query =  "UPDATE quotations SET rating=rating-1 WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare ($query);
        $stmt->execute ();
    }
    public function deleteQuote($ID) {
        $query =  "DELETE FROM quotations WHERE id='" . $ID . "';" ;
        $stmt = $this->DB->prepare ($query);
        $stmt->execute ();
    }
    
    

}  // End class DatabaseAdaptor


?>
