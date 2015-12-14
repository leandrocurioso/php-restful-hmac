<?php

namespace Core;

use Library\_Interface\IApplication;
use Library\Toolkit\Url;
use Library\Toolkit\HttpHandler;
use Library\Toolkit\Utility;
use Exception;

/**
    Application class
    The Application class is the core of the app, every request sent through the server will execute this structure
    @interface IApplication
*/
class Application implements IApplication {

	/**
        @access public
        @var object $Url | Url object
	*/
	public $Url;
	/**
        @access private
        @var array $RequestStructure | Array of 3 positions that are used to interpreter the client request
	*/
	private $RequestStructure = array("entity" => null , "webMethod"=>null,"parameter"=>null);
	/**
	    @access private
	    @static
	    @var array $Configuration | Array of nodes from the config.xml file
	*/
	public static $Configuration;

	/**
        Method responsible for the application construction for each client request
        @access public
        @throws Exception object
        @return void
	*/
	public function __construct(){
		try{

			//Require utility class
			require_once("Library/Toolkit/Utility.class.php");

			//Construct configuration and constants
			self::construct_configuration_constants();

			//Set encoding
			self::set_encoding();

			//Set timezone
			self::set_timezone();

      //Change application environment
      self::change_application_environment();

			//Application state
			self::application_state();

			//Start auto class load
      spl_autoload_register('self::auto_class_loader');

			//Url
      if (!is_object($this->Url)) {
      	$this->Url = new Url();
      }

      //Session start
			self::session_start();

  		//Set request structure
  		self::set_request_structure();

      //Router
      self::router();

		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method sets the application timezone
        @access public
        @throws Exception object
        @static
        @return void
	*/
	public static function set_timezone(){
		try{
			date_default_timezone_set(Application::config("general->application_timezone"));
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method sets the encoding of the application according config.xml file
        @access public
        @throws Exception object
        @static
        @return void
	*/
	public static function set_encoding(){
		try{
			ini_set('default_charset',self::config("general->application_charset"));
			mb_internal_encoding(self::config("general->application_charset"));
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	    This method initializes the app object calling a new instance of application
        @access public
        @throws Exception object
        @static
        @return Application object
	*/
	public static function initialize(){
		try{
			return new Application();
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method get the request structure
        @access public
        @throws Exception object
        @param String $key
        @return string
	*/
	public function request_structure($key){
		try{
			return $this->RequestStructure[$key];
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        The method sets the request structure array values for this application class according to the router url position configuration
        @access public
        @throws Exception object
        @return void
	*/
	public function set_request_structure() {
		try{

		    //Entity
            if($this->Url->url_position(self::config('router->url_position->entity')) != null){
            	$this->RequestStructure["entity"]  = strtolower($this->Url->url_position(self::config('router->url_position->entity')));
            }else{
            	$this->RequestStructure["entity"]  = self::config('router->main_service');
            }

            //Web Method
            if($this->Url->url_position(self::config("router->url_position->webmethod")) != null) {
            	$this->RequestStructure["webMethod"]  = strtolower($this->Url->url_position(self::config('router->url_position->webmethod')));
            }else{
            	$this->RequestStructure["webMethod"]  = self::config('router->index_webmethod');
            }

            //Parameter
            if($this->Url->url_position(self::config("router->url_position->parameter")) != null){
            	$this->RequestStructure["parameter"] = strtolower($this->Url->url_position(self::config("router->url_position->parameter")));
            }
   		}catch(Exception $e){
			throw $e;
		}
  	}

	/**
        This method gets the requested node from the Configuration static array
        @access public
        @throws Exception object
        @param String $node
        @static
        @return string
	*/
	public static function config($node){
		try{
			$optionsArr = explode("->" , $node);
			$result = null;
			$strArrKey = "";
			$arrConfig = self::$Configuration;
			foreach($optionsArr as $key1 => $value1){
				$strArrKey.= "['" .strtolower($value1). "']";
			}
			$value =  eval('return (isset($arrConfig'.$strArrKey.') == true) ? $arrConfig'.$strArrKey.' : null;');

			//Check if value is a boolean
			if(is_string($value) && Utility::check_bool($value)){
				$value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
			}

			return $value;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method initializes the session if is enabled in config.xml and set the secure php session id if also secure http is set to true
        @access public
        @throws Exception object
        @return void
	*/
  public function session_start(){
       try {
			if(self::config("general->session_enabled")){
				if (session_status() == PHP_SESSION_NONE) {
					ini_set('session.cookie_httponly', 1);
					if( self::config("general->secure_http")){
						ini_set('session.cookie_secure', 1);
					}
					session_start();
				}
			}
	 }catch(Exception $e){
           throw  $e;
       }
    }

	/**
        This method sets the router url position according to the localhost configuration
        @access public
        @throws Exception object
        @return void
	*/
  public  function change_application_environment(){
       try {

           if (self::config('router->application_localhost')) {
               self::$Configuration['router']['url_position']['entity'] = 1;
               self::$Configuration['router']['url_position']['webmethod'] = 2;
               self::$Configuration['router']['url_position']['parameter'] = 3;
           } else {
           	   self::$Configuration['router']['url_position']['entity'] = 0;
               self::$Configuration['router']['url_position']['webmethod'] = 1;
               self::$Configuration['router']['url_position']['parameter'] = 2;
           }
       }catch(Exception $e){
           throw  $e;
       }
    }

	/**
        Construct the configuration and constants based on config.xml and other path diretories
        @access public
        @throws Exception object
        @static
        @return void
	*/
	public static function construct_configuration_constants(){
		try{
			//Define ROOT_PATH
			$documentRoot = $_SERVER['DOCUMENT_ROOT'];

			//Define CONFIG_FILENAME
			self::$Configuration["file"]["config"] = 'Config.xml';

			//Set file configuration
			self::$Configuration = json_decode(json_encode((array)get_object_vars(simplexml_load_file(self::config('file->config')))), true);

			//Define ROOT_PATH
			self::$Configuration["path"]["virtual"]["root"] = self::config('router->base_url');
			self::$Configuration["path"]["physical"]["root"]  = $documentRoot.self::config('path->virtual->root');


			//Define UNAUTHENTICATED_ROUTE_FILENAME
			self::$Configuration["file"]["unauthenticated_route"] = self::config('path->physical->root').'UnauthenticatedRoute.xml';

			//Define LIBRARY_PATH
			self::$Configuration["path"]["virtual"]["library"] = "Library/";
			self::$Configuration["path"]["physical"]["library"] = self::config('path->physical->root').self::config('path->virtual->library');

			//Define LIRRARY_TOOLKIT_PATH
			self::$Configuration["path"]["virtual"]["toolkit"] = "Toolkit/";
			self::$Configuration["path"]["physical"]["toolkit"] = self::config('path->physical->library').self::config('path->virtual->toolkit');

			//Define LIRRARY_DAO_PATH
			self::$Configuration["path"]["virtual"]["data_access_object"] = "DataAccessObject/";
			self::$Configuration["path"]["physical"]["data_access_object"] = self::config('path->physical->library').self::config('path->virtual->data_access_object');

			//Define LIRRARY_VO_PATH
			self::$Configuration["path"]["virtual"]["value_object"] = "ValueObject/";
			self::$Configuration["path"]["physical"]["value_object"] = self::config('path->physical->library').self::config('path->virtual->value_object');

			//Define LIRRARY_INTERFACE_PATH
			self::$Configuration["path"]["virtual"]["interface"] = "Interface/";
			self::$Configuration["path"]["physical"]["interface"] = self::config('path->physical->library').self::config('path->virtual->interface');

			//Define BUSINESS_PATH
			self::$Configuration["path"]["virtual"]["business_core"] = "BusinessCore/";
			self::$Configuration["path"]["physical"]["business_core"] = self::config('path->physical->library').self::config('path->virtual->business_core');

			//Define SERVICE_PATH
			self::$Configuration["path"]["virtual"]["service"] = "Service/";
			self::$Configuration["path"]["physical"]["service"] = self::config('path->physical->library').self::config('path->virtual->service');

		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Sets the application state according to the config.xml for debug error reporting purposes
        @access public
        @throws Exception object
        @return void
	*/
	public function application_state(){
		try{
			$appState = self::config('general->application_state');
			if($appState == "D"){
				error_reporting(E_ALL);
			}elseif($appState == "P"){
				error_reporting(0);
			}elseif($appState == "M"){
				error_reporting(0);
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Auto class loader for the whole framework, using the namespcae and folder structure
        @access public
        @throws Exception object
        @param String $classPath
        @static
        @return void
	*/
	public static function auto_class_loader( $classPath ) {
		try{
			$classPath = str_replace("_","",self::config('path->root').str_replace("\\", "/" , $classPath)). ".class.php";
      if( is_file( $classPath) ) {
				require_once( $classPath);
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        Router method for service instantiation purposes, it uses the attribute $RequestStructure values and if they do not exists get from de default in config.xml
        @access public
        @throws Exception object
        @return void
	*/
	public function router(){
		try{

				//Entity name
				$entityName = ucfirst(self::request_structure("entity"));

				//Explode the string
				$arrEntityName = explode("-",$entityName);

				//Set entity name
				$entityName = "";
				foreach($arrEntityName as $en){
					$entityName.= ucfirst($en);
				}
				$this->RequestStructure["entity"] = $entityName;

				//Check if service entity exists
				$serviceEntity = self::request_structure("entity");
				$serviceFilePath = self::config('path->physical->service').$entityName."SRV.class.php";

				if(!is_file($serviceFilePath)){
					HttpHandler::header(404);
				}else{
					//Execute the service constructor
					$serviceEntity = "\\Library\\Service\\".$serviceEntity.'SRV';
					$serviceEntityNewInstance = new $serviceEntity($this);
				}
			}catch(Exception $e){
				throw $e;
			}
		}

}
