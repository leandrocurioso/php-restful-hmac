<?php

/**   
  Service request class  
  This class is responsible for handeling calls to the webservice
*/
class ServiceRequest {

	/**
        @access private
        @var array $Options | Options array
	*/
	private $Options = array();
	
	 /**       
	    Constructor method
	    @access public
	    @throws Exception object
	    @static
	    @return void
	 */
	public function __construct($options = array()){
		$this->Options = $options;
	}

	 /**       
	    Check headers before start the request
	    @access public
	    @throws Exception object
	    @return void
	 */
  	 public function check_headers(){
		try{
			//Check authorization prefix
			if(!isset($this->Options["authorization_prefix"])){
				throw new Exception("There's no authorization prefix for the service request.");
			}

			//Check host
			if(!isset($this->Options["host"])){
				throw new Exception("There's no host for the service request.");
			}

			//Check api key
			if(!isset($this->Options["api_key"])){
				throw new Exception("There's no api key for the service request.");
			}

			//Check api secret key
			if(!isset($this->Options["api_secret_key"])){
				throw new Exception("There's no api secret key for the service request.");
			}
		 }catch(Exception $e){
            throw $e;
        }
	}

	/**       
	    Build the header for the webservice
	    @access public
	    @throws Exception object
        @param string $queryString | String with the query string    
        @param string $authorization | String with the authorization hash string   
	    @return array
	*/
	public function build_header($queryString , $authorization = null){
		try{
			$_time = time();
			$header = [
						"Api-Key: ".$this->Options["api_key"]."",
						"Hmac-Hash: ".hash_hmac('sha256', $queryString.$_time, $this->Options["api_secret_key"]),
						"X-Timestamp: ".$_time,
						"Client-Ip: ".Utility::get_client_ip(),
						"Client-User-Agent: ".$_SERVER["HTTP_USER_AGENT"]
					  ];
		  if($authorization != null){
		  	$header[] = "Authorization: ".$this->Options["authorization_prefix"]." ".$authorization."";
		  }
		  return $header;
		 }catch(Exception $e){
            throw $e;
        }
	}

	/**       
	    Mount the url for the webservice request
	    @access public
	    @throws Exception object
        @param string $serviceUri | String with the service uri
        @param array $parameters | Array with the parameters
	    @return string
	*/
	public function mount_url($serviceUri , $parameters = null){
		try{
			if(is_array($parameters)){
				return $this->Options["host"].$serviceUri."?".Utility::build_http_query($parameters);
			}else{
				return $this->Options["host"].$serviceUri;
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**       
	    Get request
	    @access public
	    @throws Exception object
        @param string $serviceUri | String with the service uri
        @param array $parameters | Array with the parameters
        @param string $authorization | String with the authorization hash string   
	    @return object
	*/
    public function get($serviceUri, $parameters = null, $authorization = null, $debug = false){
        try{
           	self::check_headers();
            $curl = curl_init(self::mount_url($serviceUri,$parameters));
            curl_setopt($curl,CURLOPT_HTTPHEADER, self::build_header(Utility::build_http_query($parameters) , $authorization));
            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
			$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
            if ($curl_response === false) {
                throw new Exception('Error occured during curl exec. Additioanl info: ' . var_export(curl_getinfo($curl)));
            }

			$json = json_decode($curl_response);
		
            if(isset($json) && is_object($json)){
          	  return (object) Utility::array_to_object(["payload"=>json_decode($curl_response) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
            }else{
                if($debug){
                    $data =  (object) Utility::array_to_object(["webservice_return"=>trim(strip_tags( $curl_response)) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
                    Utility::debug($data);
                }
            }
        }catch(Exception $e){
            throw $e;
        }
    }

	/**       
	    Post request
	    @access public
	    @throws Exception object
        @param string $serviceUri | String with the service uri
        @param array $parameters | Array with the parameters
        @param string $authorization | String with the authorization hash string   
	    @return object
	*/
    public function post($serviceUri, $parameters = null, $authorization = null, $debug = false){
        try{
            self::check_headers();
            
            $curl = curl_init($this->Options["host"].$serviceUri);
			
            curl_setopt($curl,CURLOPT_HTTPHEADER,self::build_header(Utility::build_http_query($parameters) , $authorization));                                                                                                              
            
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, Utility::build_http_query($parameters));
			$curl_response = curl_exec($curl);
			$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($curl_response === false) {
                throw new Exception('Error occured during curl exec. Additioanl info: ' . var_export(curl_getinfo($curl)));
            }

            $json = json_decode($curl_response);

            if(isset($json) && is_object($json)){
          	  return (object) Utility::array_to_object(["payload"=>json_decode($curl_response) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
            }else{
                if($debug){
                    $data =  (object) Utility::array_to_object(["webservice_return"=>trim(strip_tags( $curl_response)) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
                    Utility::debug($data);
                }         
            }
        }catch(Exception $e){
            throw $e;
        }
    }

	/**       
	    Put request
	    @access public
	    @throws Exception object
        @param string $serviceUri | String with the service uri
        @param array $parameters | Array with the parameters
        @param string $authorization | String with the authorization hash string   
	    @return object
	*/
    public function put($serviceUri, $parameters = null, $authorization = null, $debug = false){
        try{
            self::check_headers();
            $curl = curl_init($this->Options["host"].$serviceUri);
            curl_setopt($curl,CURLOPT_HTTPHEADER,self::build_header(Utility::build_http_query($parameters) , $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, Utility::build_http_query($parameters));
            $curl_response = curl_exec($curl);
			$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($curl_response === false) {
                throw new Exception('Error occured during curl exec. Additioanl info: ' . var_export(curl_getinfo($curl)));
            }

			$json = json_decode($curl_response);

            if(isset($json) && is_object($json)){
          	  return (object) Utility::array_to_object(["payload"=>json_decode($curl_response) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
            }else{
                if($debug){
                    $data =  (object) Utility::array_to_object(["webservice_return"=>trim(strip_tags( $curl_response)) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
                    Utility::debug($data);
                }
            }

        }catch(Exception $e){
            throw $e;
        }
    }

	/**       
	    Delete request
	    @access public
	    @throws Exception object
        @param string $serviceUri | String with the service uri
        @param array $parameters | Array with the parameters
        @param string $authorization | String with the authorization hash string   
	    @return object
	*/
    public function delete($serviceUri, $parameters = null, $authorization = null, $debug = false){
        try{
            self::check_headers();
            $curl = curl_init($this->Options["host"].$serviceUri);
            curl_setopt($curl,CURLOPT_HTTPHEADER,self::build_header(Utility::build_http_query($parameters) , $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_POSTFIELDS, Utility::build_http_query($parameters));
            $curl_response = curl_exec($curl);
			$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($curl_response === false) {
                throw new Exception('Error occured during curl exec. Additioanl info: ' . var_export(curl_getinfo($curl)));
            }

			$json = json_decode($curl_response);

            if(isset($json) && is_object($json)){
          	  return (object) Utility::array_to_object(["payload"=>json_decode($curl_response) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
            }else{
                if($debug){
                    $data =  (object) Utility::array_to_object(["webservice_return"=>trim(strip_tags( $curl_response)) , "http_status" => ["http_method"=>"POST","code"=> $http_status_code , "canonical_name" => HttpHandler::get_http_code_info($http_status_code )] ]);
                    Utility::debug($data);
                }
            }
        }catch(Exception $e){
            throw $e;
        }
    }

}