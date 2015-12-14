<?php

namespace Library\DataAccessObject;

use Library\_Interface\IDataAccessObject;
use Library\Toolkit\PDOHandler;
use Library\ValueObject\ValueObject;
use Library\Toolkit\HttpHandler;
use Exception;

/** 
    Data access object class
    The data access object class is the database methods
    @package Library\DataAccessObject
    @interface IDataAccessObject
*/ 
abstract class DataAccessObject implements IDataAccessObject {

	/** 
        @access protected
        @var object $PDOHandler | PDO handler object
	*/ 
	protected $PDOHandler;
	
	/** 
        Constructor method
        @access public
        @throws Exception object
        @param object $pdoHandler
        @return void
	*/ 
	public function __construct(PDOHandler $pdoHandler) {
		try {
			$this->PDOHandler = $pdoHandler;
		} catch ( Exception $e ) {
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
	public function read(ValueObject $objVO) {
		try {
			throw new Exception(__METHOD__." method not implemented yet.");
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}
	
	/** 
        This method creates an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return int
	*/ 
	public function create(ValueObject $objVO) {
		try {
			throw new Exception(__METHOD__." method not implemented yet.");
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}
	
	/** 
        This method updates an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return bool
	*/ 
	public function update(ValueObject $objVO) {
		try {
			throw new Exception(__METHOD__." method not implemented yet.");
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}
	
	/** 
        This method deletes an entity register
        @access public
        @throws Exception object
        @param object $objVO
        @return bool
	*/ 
	public function delete(ValueObject $objVO) {
		try {
			throw new Exception(__METHOD__." method not implemented yet.");
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}
	
	/** 
        This method lists all registers from given entity
        @access public
        @throws Exception object
        @param object $objVO
        @return object
	*/ 
	public function list_all(ValueObject $objVO = null , $options = []) {
		try {
			throw new Exception(__METHOD__." method not implemented yet.");
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}
}