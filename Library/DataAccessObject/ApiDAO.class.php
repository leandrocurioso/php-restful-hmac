<?php

namespace Library\DataAccessObject;

use Library\DataAccessObject\DataAccessObject;
use Library\Toolkit\PDOHandler;
use Library\Toolkit\ArrayList;
use PDO;
use Library\ValueObject\ValueObject;
use Library\ValueObject\ApiApplicationVO;
use Library\ValueObject\ApiUserTokenVO;
use PDOException;
use Exception;

/**
    Handy api data access object
    This class access the api
    @package Library\DataAccessObject
    @interface DataAccessObject
*/
class ApiDAO extends DataAccessObject {

  /**
      Constructor method
      @access public
      @throws Exception object
      @param object $pdoHandler
      @return void
  */
	public function __construct(PDOHandler $pdoHandler){
		try{
			parent::__construct($pdoHandler);
		}catch(Exception $ex){
			throw $ex;
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
  			$query = "SELECT api_secret_key FROM api_application WHERE 1 = 1 ";

        if ($objVO->ApiKey != null) {
            $query.= " AND api_application.api_key = :apiKey";
        }

      	$this->PDOHandler->query($query . " LIMIT 1");

        if ($objVO->ApiKey != null) {
            $this->PDOHandler->bind(':apiKey', $objVO->ApiKey, PDO::PARAM_STR);
        }

  			$resultSet = $this->PDOHandler->result_set();

  			if(count($resultSet) > 0){
  				$apiApplicationVO = new ApiApplicationVO();
  				$apiApplicationVO->ApiSecretKey = $resultSet[0]->api_secret_key;
  			}else{
  				$apiApplicationVO = $resultSet;
  			}

  			return $apiApplicationVO;
  		}catch(PDOException $ex){
  			throw $ex;
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
        $apiUserTokenVO = new ApiUserTokenVO();
        $query = "call sp_auth_user(:user_table_label, :auth_user_id_label , :user_active_label , :user_token, :entity, :service,:http_verb)";
      	$this->PDOHandler->query($query);
        if (count($param) > 0) {
						$this->PDOHandler->bind(':user_table_label', $param["user_table_label"], PDO::PARAM_STR);
						$this->PDOHandler->bind(':auth_user_id_label', $param["auth_user_id_label"], PDO::PARAM_STR);
						$this->PDOHandler->bind(':user_active_label', $param["user_active_label"], PDO::PARAM_STR);
            $this->PDOHandler->bind(':user_token', $param["user_token"], PDO::PARAM_STR);
            $this->PDOHandler->bind(':entity', $param["entity"], PDO::PARAM_STR);
            $this->PDOHandler->bind(':service', $param["service"], PDO::PARAM_STR);
            $this->PDOHandler->bind(':http_verb', $param["http_verb"], PDO::PARAM_STR);
        }
        $resultSet = $this->PDOHandler->result_set();
				if(count($resultSet) > 0){
					$apiUserTokenVO->UserId = $resultSet[0]->id;
				}else{
					$apiUserTokenVO->UserId = 0;
				}
        return $apiUserTokenVO;
      }catch(PDOException $ex){
        throw $ex;
      }
  }

  /**
      Auth user
      @access public
      @throws Exception object
      @param string $login
      @param string $password
      @return void
  */
  public function auth_user($objVO){
    try{
        $query = "SELECT id FROM user WHERE email = :email AND password = :password";
        $this->PDOHandler->query($query . " LIMIT 1");
        $this->PDOHandler->bind(':email', $objVO->Email, PDO::PARAM_STR);
        $this->PDOHandler->bind(':password', $objVO->Password, PDO::PARAM_STR);
        return $this->PDOHandler->result_set();
      }catch(PDOException $ex){
        throw $ex;
      }
  }

 /**
      Insert token
      @access public
      @throws Exception object
      @param string $newToken
      @param int $userId
      @param string $clientIp
      @param datetime $addDatetime
      @param string $userAgent
      @return void
  */
  public function insert_token($objVO){
    try{
        $query = "INSERT INTO api_user_token
				(
					user_token,
					user_id,
					add_datetime,
					client_ip,
					user_agent
				)
				VALUES
				(
					:user_token,
					:user_id,
					:add_datetime,
					:client_ip,
					:user_agent
				)";
        $this->PDOHandler->query($query);
        $this->PDOHandler->bind(':user_token', $objVO->Token, PDO::PARAM_STR);
        $this->PDOHandler->bind(':user_id', $objVO->User->Id, PDO::PARAM_INT);
        $this->PDOHandler->bind(':client_ip', $objVO->ClientIp, PDO::PARAM_STR);
        $this->PDOHandler->bind(':add_datetime', $objVO->AddDatetime, PDO::PARAM_STR);
        $this->PDOHandler->bind(':user_agent', $objVO->UserAgent, PDO::PARAM_STR);
        $this->PDOHandler->execute();
        return $this->PDOHandler->row_count();
      }catch(Exception $ex){
        throw $ex;
      }
  }

  /**
      Refresh token
      @access public
      @throws Exception object
      @param string $oldToken
      @param string $newToken
      @return int
  */
  public function refresh_token( $oldToken,$newToken){
    try{
        $query = "UPDATE api_user_token SET user_token = :api_new_token WHERE user_token = :api_old_token";
        $this->PDOHandler->query($query);
        $this->PDOHandler->bind(':api_new_token', $newToken, PDO::PARAM_STR);
        $this->PDOHandler->bind(':api_old_token', $oldToken, PDO::PARAM_INT);
        $this->PDOHandler->execute();
        return $this->PDOHandler->row_count();
      }catch(Exception $ex){
        throw $ex;
      }
  }

  /**
      Delete token
      @access public
      @throws Exception object
      @param string $token
      @return int
  */
  public function delete_token($token){
    try{
        $query = "DELETE FROM api_user_token WHERE user_token = :user_token";
        $this->PDOHandler->query($query);
        $this->PDOHandler->bind(':user_token', $token, PDO::PARAM_STR);
        $this->PDOHandler->execute();
        return $this->PDOHandler->row_count();
      }catch(Exception $ex){
        throw $ex;
      }
  }

  /**
      Insert system log
      @access public
      @throws Exception object
      @param array $params
      @return int
  */
  public function insert_system_log($params = []){
    try{
        $query = "INSERT INTO api_log
        (
					user_id,
					api_application_key,
					user_token,
					entity,
					method,
					parameter,
					http_verb,
					client_ip,
					server_ip,
					add_datetime,
					content
				)
        VALUES
        (
					:user_id,
					:api_application_key,
					:user_token,
					:entity,
					:method,
					:parameter,
					:http_verb,
					:client_ip,
					:server_ip,
					:add_datetime,
					:content
				)";

				$this->PDOHandler->query($query);
        $this->PDOHandler->bind(':user_id', $params["user_id"], PDO::PARAM_INT);
        $this->PDOHandler->bind(':api_application_key', $params["api_key"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':user_token', $params["user_token"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':entity', $params["entity"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':method', $params["service"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':parameter', $params["parameter"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':http_verb', $params["http_verb"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':client_ip', $params["client_ip"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':server_ip', $params["server_ip"], PDO::PARAM_STR);
        $this->PDOHandler->bind(':add_datetime', $params["add_datetime"], PDO::PARAM_STR);
				$this->PDOHandler->bind(':content', $params["content"], PDO::PARAM_STR);
        $this->PDOHandler->execute();
        return $this->PDOHandler->row_count();
      }catch(Exception $ex){
        throw $ex;
      }
  }

}
