<?php

namespace Library\BusinessCore;

use Library\_Interface\IBusinessCore;
use Library\Toolkit\Database;
use Library\Toolkit\Utility;
use Library\ValueObject\ValueObject;
use Library\Toolkit\HttpHandler;
use Exception;

/** 
    Business core class
    The business core class is the core of the entity business
    @package Library\BusinessCore
    @interface IBusinessCore
*/ 
abstract class BusinessCore implements IBusinessCore {

	/** 
        @access public
        @var object $Database | Database object
	*/ 
	public $Database;
    public $noCommitAndRollback;
	/** 
        Constructor method
        @access public
        @throws Exception object
        @param object $database
        @return void
	*/ 
	public function __construct(Database $database = null){
		try{

			if($database == null && $this->Database == null){
				$this->Database = new Database();
                $this->noCommitAndRollback = false;
			}else{
				$this->Database =  $database;
                $this->noCommitAndRollback = true;
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method gets the desired database or the default from child class
        @access public
        @throws Exception object
        @param string $db
        @return object
	*/ 
	public function get_database($db = null){
		try{
			if($db == null){
				if(isset($this->Database->Databases[$this->IndexDatabase])){
					return $this->Database->Databases[$this->IndexDatabase];
				}else{
					throw new Exception("There's no database index for given key: ".$this->IndexDatabase);
				}
			}else{
				if(isset($this->Database->Databases[$db])){
					return $this->Database->Databases[$db];
				}else{
					throw new Exception("There's no database index for given key: ".$db);
				}
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method creates an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return int
	*/ 
	public function create(ValueObject $objVO){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method reads an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return object
	*/ 
	public function read(ValueObject $objVO){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method saves an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return int
	*/ 
	public function save(ValueObject $objVO){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method updates an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return bool
	*/ 
	public function update(ValueObject $objVO){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method deletes an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return bool
	*/ 
	public function delete(ValueObject $objVO){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

	/** 
        This method lists all registers from given entity
        @access public
        @throws Exception object
        @param object $objVO
        @return object
	*/ 
	public function list_all(ValueObject $objVO = null , $options = []){
		try{
			HttpHandler::header(501);
		}catch(Exception $e){
			throw $e;
		}
	}

    public function begin_transaction(){
        $this->get_database()->begin_transaction();
    }

    public function commit(){
       if(!$this->noCommitAndRollback){
           $this->get_database()->commit();
       }
    }

    public function rollback(){
        if(!$this->noCommitAndRollback){
            $this->get_database()->rollback();
        }
    }
	
}
