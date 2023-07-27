<?php
namespace LTIPlatform;
class Database
{

	// specify your own database credentials
	private $host = "localhost";
	private $db_name = "learning_center_api_lti_platform";
	private $username = "root";
	private $password = "admin";
	public $conn;

    public function enableFKChecks()
    {
        if ($this->conn) {
            $this->conn->exec('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    public function disableFKChecks()
    {
        if ($this->conn) {
            $this->conn->exec('SET FOREIGN_KEY_CHECKS = 0');
        }
    }

    // get the database connection
    public function getConnection()
    {

        $this->conn = null;

        try{
            $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            //listen for php and mysql errors
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
