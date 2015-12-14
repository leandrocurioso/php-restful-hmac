<?php

namespace Library\BusinessCore;

use Core\Application;
use Library\BusinessCore\BusinessCore;
use Library\ValueObject\ValueObject;
use Library\Toolkit\Database;
use Library\DataAccessObject\ApiDAO;
use Library\Toolkit\HttpHandler;
use Library\Toolkit\Utility;
use Exception;

/**
    Api business core
    This class handles the business core of the api
    @package Library\BusinessCore
*/
class ApiBC extends BusinessCore {

	/**
        @access protected
        @var string $IndexDatabase | String with the default database
	*/
	protected $IndexDatabase = "db1";
	/**
        @access protected
        @var object $ApiDAO | Api data access object
	*/
	protected $ApiDAO;

	/**
        Constructor method
        @access public
        @throws Exception object
        @param object $database
        @return void
	*/
	public function __construct(Database $database = null){
		try{
			parent::__construct($database);
			$this->ApiDAO = new ApiDAO(parent::get_database());
		}catch(Exception $ex){
			throw $ex;
		}
	}

	/**
        This method authenticate the user
        @access public
        @throws Exception object
        @param array $data
        @return string
	*/
	public function auth_user($objVO,$params){
		try{
			$login = ucfirst(strtolower(Application::config("service->auth_name_label")));
			$password = ucfirst(strtolower(Application::config("service->auth_password_label")));
			$objVO->Token = Utility::generate_token();
			$params["user_token"] = $objVO->Token;
			$objVO->User->$password = sha1($objVO->User->$password);
			$result = $this->ApiDAO->auth_user($objVO->User);
			if($result == null or count($result) == 0){
				HttpHandler::header(401);
			}else{
				$idLabel = Application::config("service->auth_user_id_label");
				$objVO->User->Id = $result[0]->$idLabel;
				$params["user_id"] = $objVO->User->Id;
				$rc = $this->ApiDAO->insert_token($objVO);
				if($rc == 0){
					throw new Exception("Couldn't insert a new token.");
				}else{
					$this->ApiDAO->insert_system_log($params);
				}
			}
			return $objVO->Token;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Check api key
        @access public
        @throws Exception object
        @param object $objVO
        @return void
	*/
	public function check_api_key(ValueObject $objVO){
		try{
			return $this->ApiDAO->check_api_key($objVO);
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Check authorization token
        @access public
        @throws Exception object
        @param array $param
        @return void
	*/
	public function check_authorization_token($param = []){
		try{
			$apiToken = explode(" " , $param["user_token"]);
			if( $apiToken[0] != Application::config("service->authorization_prefix")){
				HttpHandler::header(401);
			}
			$apiToken[1] = $apiToken[1];
			$param["user_token"] = $apiToken[1];
			$result =  $this->ApiDAO->check_authorization_token($param);
			if($result->UserId == 0){
				HttpHandler::header(401);
			}else{
				$param["user_id"] = $result->UserId;
				$param["add_datetime"] = Utility::get_datetime();
				$this->ApiDAO->insert_system_log($param);
				$result->ApiToken = $apiToken[1];
			}
			return $result;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Refresh token
        @access public
        @throws Exception object
        @param string $oldToken
        @return string
	*/
	public function refresh_token($oldToken){
		try{
			$newToken = Utility::generate_token();
			$oldToken = explode(" " , $oldToken);
			$rc = $this->ApiDAO->refresh_token($oldToken[1] , $newToken);
			if($rc == 0){
				throw new Exception("Couldn't refresh token");
			}else{
				return $newToken;
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Delete token
        @access public
        @throws Exception object
        @param string $token
        @return string
	*/
	public function delete_token($token){
		try{
			$arrToken = explode(" " , $token);
			$rc = $this->ApiDAO->delete_token($arrToken[1]);
			if($rc == 0){
				throw new Exception("Couldn't delete token");
			}else{
				return true;
			}
		}catch(Exception $e){
			throw $e;
		}
	}

}
