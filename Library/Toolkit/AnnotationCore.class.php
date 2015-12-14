<?php

namespace Library\Toolkit;

use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use Library\Toolkit\HttpHandler;
use Exception;

/** 
    Annotation Core class
    The Annotation class is user to read annotations from classes, methods and properties
    @package Library\Toolkit
*/ 
class AnnotationCore {
	
  	/**
          Validate the method request
          @throws Exception object
          @param object $service
          @param string $method
          @param array $explodedParameters
          @param string $value
          @return void
  	*/ 
    public function validate_method_request($service , $method , $explodedParameters , $value){
        try{
            //Check for annotation request validate
            $result = [];
            if($service->Application->Url->RequestMethod != $value){
               $result[] = false;
                HttpHandler::header(405);
            }else{
              $result[] = true;
            }

            return (in_array(false, $result)) ? false : true;
        }catch(Exception $e){
            throw $e;
        }
    }
	
	 /**
       This method sets the content type header
       @access public
       @throws Exception object
	     @param object $service
       @param string $method
       @param array $explodedParameters
       @param string $value
       @return void
    */
    public function set_content_type_header($service , $method , $explodedParameters , $value){
  		try{
          //Check for annotation content type
         $responseContentType = $service::$ContentType["json"];
  	
  			 if(isset($service->ContentType[$value])){
  				 $responseContentType = $service::$ContentType[$value];
  			 }
        
         //Set content type
  			 header ("Content-Type: ".$responseContentType."; charset=".$service->Application->config("general->application_charset").";");
    	}catch(Exception $e){
  			throw $e;
  		}
	}
	
	 /**
       This method processes the service validation
       @access public
       @throws Exception object
	     @param object $service
       @param string $method
       @param array $explodedParameters
       @param string $value
       @return void
    */
    public function process_service_validate($service , $method , $explodedParameters , $value){
        try{
          $result = [];
    			switch($explodedParameters[2]){
    				case "RequestMethod":
    					$result[] = self::validate_method_request($service , $method , $explodedParameters , $value);
    				break;
    			}
           return (in_array(false, $result)) ? false : true;
    		}catch(Exception $e){
            throw $e;
        }
	  }
	
	 /**
       This method processes the service annotation
       @access public
       @throws Exception object
	     @param object $service
       @param string $method
       @param array $explodedParameters
       @param string $value
       @return void
    */
    public function process_service($service , $method , $explodedParameters , $value){
        try{
          $result = [2];

    			switch($explodedParameters[1]){
    				case "ContentType":
    					self::set_content_type_header($service , $method , $explodedParameters , $value);
    				break;
    				case "Validate":
    					$result[] = self::process_service_validate($service , $method , $explodedParameters , $value);
    				break;
    			}

           return (in_array(false, $result)) ? false : true;
		    }catch(Exception $e){
            throw $e;
        }
	}
	
    /**
       This method processes the annotation
       @access public
       @throws Exception object
	     @param object $service
       @param string $method
       @return boolean
    */
    public function process_method_annotation($service , $method){
        try{
      			//Set the result to false
            $result = [];

      			//Method annotation
      			$methodAnnotation = self::get_method($service , $method);
      			
      			//Process annotation
      			if(count($methodAnnotation) > 0){
      				$explodedParameters = [];
      				foreach($methodAnnotation as $key => $value){
      					$explodedParameters = explode(".", $key);
      					switch($explodedParameters[0]){
      						case "Service":
      							 $result[] = self::process_service($service , $method , $explodedParameters , $value);
      						break;
      					}
      				}
      			}else{
              $result[] = false;
            } 
            return (in_array(false, $result)) ? false : true;
        }catch(Exception $e){
            throw $e;
        }
    }
	
	/**
        Parse the reflection doc comment
        @throws Exception object
        @param Reflection $reflection
		    @static
        @return array
	*/ 
	public static function parser($reflection){  
		try{
            $rawAnnotation = $reflection->getDocComment();
            unset($reflection);
            $newArrAnnotation = [];
            $rawAnnotation = str_replace("/**","",$rawAnnotation);
            $rawAnnotation = str_replace("*/","",$rawAnnotation);
            $parsedAnnotation = explode("\r",$rawAnnotation);
            if(count($parsedAnnotation) > 0){
                foreach($parsedAnnotation as $anno){
                    $trimAnno = trim($anno);
                    if(!empty($trimAnno) && strpos($trimAnno , "!@") !== false){
                        $expodedNewArr = explode("=" , str_replace("!@" , "",$trimAnno));
						if(isset($expodedNewArr[0]) && isset($expodedNewArr[1])){
							$newArrAnnotation[trim($expodedNewArr[0])] = trim($expodedNewArr[1]);
						}
                    }
                }
            }
            return $newArrAnnotation;
		 }catch(Exception $e){
			throw $e;
		}
	}
	
	/**
        This method gets the class annotation stack
        @access public
        @throws Exception object
        @param string $class
        @return array
	*/ 
	public static function get_class($class){  
		try{
          return self::parser(new ReflectionClass($class));
		 }catch(Exception $e){
			throw $e;
		}
	}

	/** 
	    This method gets the property annotation stack
        @access public
        @throws Exception object
        @param string $class
        @param string $key
        @return array
	*/ 
	public static function get_property($class , $key){       
		 try{
			 return self::parser(new ReflectionProperty($class, $key));
		 }catch(Exception $e){
			throw $e;
		}
	}
	
	/** 
        This method gets the method annotation stack
        @access public
        @throws Exception object
        @param string $class
        @param string $key
        @return array
	*/ 
	public static function get_method($class , $key){       
		 try{
        if(method_exists($class, $key)){
          return self::parser(new ReflectionMethod($class, $key));
        }else{
          HttpHandler::header(501);
        }
	   	}catch(Exception $e){
    		throw $e;
    	}
	}

}