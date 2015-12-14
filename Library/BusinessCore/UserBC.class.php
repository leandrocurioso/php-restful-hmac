<?php

namespace Library\BusinessCore;

use Library\BusinessCore\BusinessCore;
use Library\ValueObject\ValueObject;
use Library\Toolkit\Database;
use Library\DataAccessObject\UserDAO;
use Exception;

class UserBC extends BusinessCore {

    protected $IndexDatabase = "db1";
    protected $UserDAO;

    public function __construct(Database $database = null) {
        try {
            parent::__construct($database);
            $this->UserDAO = new UserDAO(parent::get_database());
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function create(ValueObject $objVO) {
        try {
            return $this->UserDAO->create($objVO);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update(ValueObject $objVO){
        try {
            return $this->UserDAO->update($objVO);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function delete(ValueObject $objVO){
        try {
            parent::begin_transaction();
            $status = $this->UserDAO->delete($objVO);
            parent::commit();
            return $status;
        } catch (Exception $ex) {
            parent::rollback();
            throw $ex;
        }
    }

    public function read(ValueObject $objVO){
        try {
            return $this->UserDAO->read($objVO);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function save(ValueObject $objVO){
        try {
            parent::begin_transaction();
            if ($objVO->Id == null) {
                $returnId = self::create($objVO);
            } else {
                self::update($objVO);
                $returnId = $objVO->Id;
            }
            parent::commit();
            return $returnId;
        } catch (Exception $ex) {
            parent::rollback();
            throw $ex;
        }
    }

    public function list_all(ValueObject $objVO = null, $options = null){
        try {
            return $this->UserDAO->list_all($objVO);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
