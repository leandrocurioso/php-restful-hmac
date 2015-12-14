<?php

namespace Library\Toolkit;

use PDO;
use PDOException;
use Exception;

/**
    PDO handler class
    The PDO handler class is the pdo class itself, polymorphed for a various drivers usage
    @package Library\Toolkit
*/
class PDOHandler {

	/**
        @access private
        @var string $Host | Database host address
	*/
	private $Host;
	/**
        @access private
        @var string $User | Database user
	*/
	private $User;
	/**
        @access private
        @var string $Password | Database user password
	*/
	private $Password;
	/**
        @access private
        @var string $DatabaseName | Database name
	*/
	private $DatabaseName;
	/**
        @access private
        @var object $DBHandler | PDO class itself
	*/
	private $DBHandler;
	/**
        @access private
        @var string $Dsn | Dsn connection string
	*/
	private $Dsn;
	/**
        @access private
        @var object $Stmt | Statement pdo object
	*/
	private $Stmt;
	/**
        @access private
        @var array $Options | Array of pdo database options
	*/
	private $Options;

	/**
        Constructor method
        @access public
        @throws PDOException object
        @return void
	*/
	public function __construct($dsn, $host = "localhost", $user = "root", $pass = "" , $databaseName = "") {
		try {
			// Set atributes
			$this->Host = $host;
			$this->User = $user;
			$this->Password = $pass;
			$this->DatabaseName = $databaseName;
 			// Create a new PDO instanace
			if ($this->DBHandler == null) {
				$this->DBHandler = new PDO ( self::dsn_switcher( $dsn ), $this->User, $this->Password );
				self::set_attribute();
			}
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method sets the attribute of db handler object
        @access private
        @throws PDOException object
        @return void
	*/
	private function set_attribute() {
		try {
			if (is_array ( $this->Options )) {
				foreach ( $this->Options as $key => $value ) {
					$this->DBHandler->setAttribute( $key, $value );
				}
			}
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method switches the dsn for given database key
        @access private
        @throws Exception object
        @param string $dsn
        @return void
	*/
	private function dsn_switcher($dsn) {
		try {
			$connectionString = "";
			switch ($dsn) {
				case "mysql" :
					$connectionString = 'mysql:host=' . $this->Host . ";charset=utf8";
					$this->Options = array (
							PDO::ATTR_PERSISTENT => true,
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
					);
					break;
				case "sqlite" :
					$connectionString = 'sqlite:' . $this->Host;
					$this->Options = array (
							PDO::ATTR_PERSISTENT => true,
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					);
					break;
				case "pgsql" :
					$connectionString = 'pgsql:host=' . $this->Host . ';port=5432;user=' . $this->user . ';password=' . $this->Password . '';
					break;
				case "mssql" :
					$connectionString = 'mssql:host=' . $this->Host . '';
					break;
				case "sybase" :
					$connectionString = 'sybase:host=' . $this->Host . '';
					break;
				case "dblib" :
					$connectionString = 'dblib:host=' . $this->Host . '';
					break;
				case "sqlsrv" :
					$connectionString = 'sqlsrv:Server=' . $this->Host . '';
					break;
			}
		}catch ( Exception $e ) {
			throw $e;
		}
		return $connectionString;
	}

	/**
        This method prepares the query
        @access public
        @throws PDOException object
        @param string $query
        @return void
	*/
    public function query($query) {
		try {
			 $this->DBHandler->query("USE " . $this->DatabaseName);
			 $this->Stmt = $this->DBHandler->prepare( $query );
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method closes the cursor
        @access public
        @throws PDOException object
        @return void
	*/
     public function close_cursor() {
		try {
			$this->Stmt->closeCursor();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method binds the query parameters
        @access public
        @throws PDOException object
 		@param string $param
 		@param string $value
 		@param string $type
        @return void
	*/
	public function bind($param, $value, $type = NULL) {
		try {
			if (is_null ( $type )) {
				switch ($param) {
					case is_int ( $value ) :
						$type = PDO::PARAM_INT;
						break;
					case is_bool ( $value ) :
						$type = PDO::PARAM_BOOL;
						break;
					case is_null ( $value ) :
						$type = PDO::PARAM_NULL;
						break;
					default :
						$type = PDO::PARAM_STR;
				}
			}
			$this->Stmt->bindValue( $param, $value, $type );
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method executes the prepared query
        @access public
        @throws PDOException object
        @return void
	*/
	public function execute() {
		try {
			return $this->Stmt->execute();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method executes the prepared query
        @access public
 		@throws PDOException object
        @return array or object
	*/
	public function result_set() {
		try {
			self::execute();
			$result = $this->Stmt->fetchAll( PDO::FETCH_OBJ );
			self::close_cursor();
			return $result;
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method executes a single query
        @access public
 		@throws PDOException object
        @return array
	*/
	public function single() {
		try {
			self::execute();
			return $this->Stmt->fetch( PDO::FETCH_ASSOC );
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method counts the query result
        @access public
 		@throws PDOException object
        @return int
	*/
	public function row_count() {
		try {
			return $this->Stmt->rowCount();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method gets the last inserted id
        @access public
 		@throws PDOException object
        @return int
	*/
	public function last_inserted_id() {
		try {
			return $this->DBHandler->lastInsertId();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method begins the transaction
        @access public
 		@throws PDOException object
        @return void
	*/
	public function begin_transaction() {
		try {
			if (!$this->DBHandler->inTransaction()){
				$this->DBHandler->beginTransaction();
			}
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method commits the transaction
        @access public
 		@throws PDOException object
        @return void
	*/
	public function commit() {
		try {
			$this->DBHandler->commit();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method rollbacks the transaction
        @access public
 		@throws PDOException object
        @return void
	*/
	public function rollback() {
		try {
			$this->DBHandler->rollBack();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

	/**
        This method dumps the parameters for debug
        @access public
 		@throws PDOException object
        @return array
	*/
	public function debug_dump_params() {
		try {
			return $this->Stmt->debugDumpParams();
		}catch ( PDOException $e ) {
			throw $e;
		}
	}

}
