<?php

namespace Library\Service;

use Library\BusinessCore\UserBC;
use Library\BusinessCore\ApiBC;
use Library\Service\ServiceHMAC;
use Core\Application;
use Library\Toolkit\HttpHandler;
use Library\Toolkit\Utility;
use Library\ValueObject\UserVO;
use Library\ValueObject\ApiProfileVO;
use Library\ValueObject\ApiUserTokenVO;
use Exception;

/**
    User class
    This class is responsible for login service
    @package Library\Service
*/
class UserSRV extends ServiceHMAC {

	/**
        Constructor method
        @access public
        @throws Exception object
        @return void
	*/
	public function __construct(Application $app = null){
		try {
			parent::__construct ($app);
		} catch ( Exception $ex ) {
			throw $ex;
		}
	}

	/**
      List user method
      @access public
      @throws Exception object
	    !@Service.ContentType = json
	    !@Service.Validate.RequestMethod = GET
   */
	public function list_user(){
		try{
			$userBC = new UserBC();
			parent::response($userBC->list_all()->get_items());
		} catch ( Exception $ex ) {
			parent::response($ex);
		}
	}

	/**
			Read user method
			@access public
			@throws Exception object
			!@Service.ContentType = json
			!@Service.Validate.RequestMethod = GET
	 */
	public function read_user(){
		try{
			$userBC = new UserBC();
			$userVO = new UserVO();
			$userVO ->Id = 1;
			parent::response($userBC->read($userVO));
		} catch ( Exception $ex ) {
			parent::response($ex);
		}
	}

	/**
			Create method
			@access public
			@throws Exception object
			!@Service.ContentType = json
			!@Service.Validate.RequestMethod = POST
	 */
	public function create(){
		try{
			$userBC = new UserBC();
			$userVO = new UserVO();
			$userVO->ApiProfile = new ApiProfileVO();
			$userVO->Name = $this->Post->name;
			$userVO->Email = $this->Post->email;
			$userVO->ApiProfile->Id = 1;
			$userVO->Password = sha1($this->Post->password);
			$userVO->Active = 1;
			parent::response(["last_inserted_id" =>$userBC->create($userVO)]);
		} catch ( Exception $ex ) {
			parent::response($ex);
		}
	}

	/**
			Update method
			@access public
			@throws Exception object
			!@Service.ContentType = json
			!@Service.Validate.RequestMethod = PUT
	 */
	public function update(){
		try{
			$userBC = new UserBC();
			$userVO = new UserVO();
			$userVO->Id = $this->Put->id;
			$userVO->Name = $this->Put->name;
			parent::response(["update_status" => $userBC->save($userVO)]);
		} catch ( Exception $ex) {
			parent::response($ex);
		}
	}

	/**
			Delete method
			@access public
			@throws Exception object
			!@Service.ContentType = json
			!@Service.Validate.RequestMethod = DELETE
	 */
	public function delete(){
		try{
			$userBC = new UserBC();
			$userVO = new UserVO();
			$userVO->Id = $this->Delete->id;
			parent::response(["delete_status" => $userBC->delete($userVO)]);
		} catch ( Exception $ex) {
			parent::response($ex);
		}
	}

	/**
			Auth method
			@access public
			@throws Exception object
			!@Service.ContentType = json
			!@Service.Validate.RequestMethod = POST
	 */
	public function auth(){
		try{
			$login = Application::config("service->auth_name_label");
			$password = Application::config("service->auth_password_label");
			$addDatetime = Utility::get_datetime();
			$apiBC = new ApiBC();
			$apiUserTokenVO = new ApiUserTokenVO();
			$apiUserTokenVO->User  = new UserVO();
			$apiUserTokenVO->User->Email= $this->Post->$login;
			$apiUserTokenVO->User->Password = $this->Post->$password;
			$apiUserTokenVO->UserAgent = $this->Headers->{'Client-User-Agent'};
			$apiUserTokenVO->ClientIp = $this->Headers->{'Client-Ip'};
			$apiUserTokenVO->AddDatetime = $addDatetime;
			//Params for logging
			$params = [
							"user_table_label"   => $this->Application->config("service->user_table"),
							"auth_user_id_label" => $this->Application->config("service->auth_user_id_label"),
							"user_active_label"  => $this->Application->config("service->user_active_label"),
							"entity"             => $this->Application->request_structure("entity"),
							"service"            => $this->Application->request_structure("webMethod"),
							"parameter"          => $this->Application->request_structure("parameter"),
							"http_verb"          => $this->Application->Url->RequestMethod,
							"client_ip"          => $this->Headers->{"Client-Ip"},
							"server_ip"          => Utility::get_client_ip(),
							"api_key"           =>  $this->Headers->{"Api-Key"},
							"content"           =>  json_encode($this->Post),
							"add_datetime"      => $addDatetime
				 ];
			parent::response(["token" => $apiBC->auth_user($apiUserTokenVO,$params)]);
		} catch ( Exception $ex) {
			parent::response($ex);
		}
	}

}
