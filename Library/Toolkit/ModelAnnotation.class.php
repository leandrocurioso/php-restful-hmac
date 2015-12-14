<?php

namespace Library\Toolkit;

use Library\Toolkit\AnnotationCore;
use Library\ValueObject\HandyCore\ValueObject;
use Exception;

/**   
  Model annotation class  
  Class responsible to validate the value object annotation  
  @package Library\Toolkit
*/
class ModelAnnotation {
	/** 
        @access = public
        @var object $ValueObject | Value object
    */
    public $ValueObject;
    /** 
        @access = public
        @var array $PropertyAnnotation | Array with property annotation
    */
	public $PropertyAnnotation = [];
    /** 
        @access = public
        @var array $Error | Array with error
    */
    public $Error = [];
	
	/**
        Constructor method
        @throws Exception object
        @param object $vo | Value Object
        @return void
	*/ 
	public function __construct(ValueObject $vo){
		try{
			$this->ValueObject = $vo;
			$this->PropertyAnnotation = self::build_property($this->ValueObject);
		}catch(Exception $e){
			throw $e;
		}
	}
	
	/**
        Builds the property from annotation
        @throws Exception object
        @param object $vo | Value Object
        @static
        @return array
	*/ 
	public static function build_property(ValueObject $vo){
		try{
			$objVars = get_object_vars($vo);
			$propertyAnnotation = array();
			if(count($objVars) > 0){
				foreach($objVars as $key => $value){
					$propertyAnnotation[$key] = AnnotationCore::get_property($vo , $key);
				}
			}
		}catch(Exception $e){
			throw $e;
		}
		return $propertyAnnotation;
	}
	
	/**
        Checks if the value object is valid
        @throws Exception object
        @param string $state | String with the state of validation for insert and update
        @param bool $cascadeObjectError | Boolean for cascade the object error
        @return bool
	*/ 
	public function is_valid($state = "insert" , $cascadeObjectError = true){
		try{
			$arrState = array();
			$result = false;
	
			if(is_array($this->PropertyAnnotation) && count($this->PropertyAnnotation) > 0){
				foreach($this->PropertyAnnotation as $key => $value){
					
					if(is_array($value) && count($value) > 0){
						foreach($value as $key2 => $value2){
							
							
							if(!isset($value['Model.property.key'])){
								 $value['Model.property.key'] = null;
							}
							
							//@Model.validate.Id
							if($state != "insert"){		
							
								if($key2 == "Model.validate.id"){
							
									$this->Error[$key]["id"] = self::id($this->ValueObject , $key , $value2 , $value['Model.property.key']);
								}
							}
							
							//@Model.validate.required
							if($key2 == "Model.validate.required"){
								$this->Error[$key]["required"] = self::required($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.maxLength
							if($key2 == "Model.validate.maxLength"){
								$this->Error[$key]["maxLength"] = self::max_length($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.minLength
							if($key2 == "Model.validate.minLength"){
								$this->Error[$key]["minLength"] = self::min_length($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
						
							//@Model.validate.rangeLength
							if($key2 == "Model.validate.rangeLength"){
								$this->Error[$key]["rangeLength"] = self::range_length($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.email
							if($key2 == "Model.validate.email"){
								$this->Error[$key]["email"] = self::email($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.url
							if($key2 == "Model.validate.url"){
								$this->Error[$key]["url"] = self::url($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.regExp
							if($key2 == "Model.validate.regExp"){
								$this->Error[$key]["regExp"] = self::reg_exp($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.ip
							if($key2 == "Model.validate.ip"){
								$this->Error[$key]["ip"] = self::ip($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.int
							if($key2 == "Model.validate.int"){
								$this->Error[$key]["int"] = self::int_number($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.float
							if($key2 == "Model.validate.float"){
								$this->Error[$key]["float"] = self::float_number($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.maxValue
							if($key2 == "Model.validate.maxValue"){
								$this->Error[$key]["maxValue"] = self::max_value($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.minValue
							if($key2 == "Model.validate.minValue"){
								$this->Error[$key]["minValue"] = self::min_value($this->ValueObject , $key , $value2, $value['Model.property.key']);
							}
							
							//@Model.validate.boolean
							if($key2 == "Model.validate.boolean"){
								$this->Error[$key]["boolean"] = self::_is_boolean($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.date
							if($key2 == "Model.validate.date"){
								$this->Error[$key]["date"] = self::is_date($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.datetime
							if($key2 == "Model.validate.datetime"){
								$this->Error[$key]["datetime"] = self::is_datetime($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
							//@Model.validate.valueObject
							if($cascadeObjectError){
								if($key2 == "Model.validate.valueObject"){
									$this->Error[$key]["valueObject"] = self::is_an_object($this->ValueObject , $state,  $key , $value2 , $value['Model.property.key']);
								}
							}
							//@Model.validate.equalTo
							if($key2 == "Model.validate.equalTo"){
								$this->Error[$key]["equalTo"] = self::equal_to($this->ValueObject , $key , $value2 , $value['Model.property.key']);
							}
							
						}
						
						
					}
					
				}
			}
			$objState = array();
			if(count($this->Error) > 0){
				foreach($this->Error as $key => $value){
					
					if(is_array($value)){
						foreach($value as $key2 => $value2){
							$objState[] = $value2["state"];
						}
					}
				}
			}
			$result = false;
			if(in_array(false , $objState)){
				$result = false;
			}else{
				$result = true;
			}
		}catch(Exception $e){
			throw $e;
		}
		return $result;
	}
	
	/**
        Checks if the value object is valid
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return bool
	*/ 
     public static function is_an_object($vo , $state, $key , $value = "" , $propertykey){
		try{
					
			if (is_a($vo->$key, $value) == false) {
				$arrResult = array(
									"state" => false , 
									"value" => $value , 
									"message" => "Not a valid object of (".$value.")",
									"propertyKey" => $propertykey,
									"cascade_error_list" => null
								   );
			} else {
				if($vo->$key->is_valid($state , true) == false){
					$arrResult = array(
									"state" => false , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => "Object of (".$value.") but not valid",
									"cascade_error_list" => array($value => $vo->$key->get_model_error())
								   );
				}else{
				
					$arrResult = array(
									"state" => true , 
									"message" => null,
									"value" => $value , 
									"propertyKey" => $propertykey,
									"cascade_error_list" => array($value => null)
								   );
				}
			}			

		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if is a valid date
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function is_date($vo , $key , $value , $propertykey){
		try{
			$arrDate  = explode('-', $vo->$key);
			if(count($arrDate) == 0){
				$arrDate  = explode('/', $vo->$key);
			}
		
			if (count($arrDate) == 3) {

			$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
			if (!preg_match($date_regex, $arrDate[0]."-".$arrDate[1]."-".$arrDate[2])) {

					$arrResult = array(
									"state" => false , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => "Not a valid date"
								   );
				} else {
					$arrResult = array(
									"state" => true , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => null
								   );
				}
			} else {
				$arrResult = array(
									"state" => false , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => "Not a valid date"
								   );
			}
			
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}

	/**
        Checks if is a valid datetime
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function is_datetime($vo , $key , $value , $format =  'Y-m-d H:i:s' , $propertykey){
		try{
			$d = DateTime::createFromFormat($format, $vo->$key);
			$state = $d && $d->format($format) == $vo->$key;

			if (!$state) {
					$arrResult = array(
									"state" => false , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => "Not a valid datetime"
								   );
				} else {
					$arrResult = array(
									"state" => true , 
									"value" => $value , 
									"propertyKey" => $propertykey,
									"message" => null
								   );
				}			
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if is a valid boolean
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function _is_boolean($vo , $key, $value , $propertykey){
		try{
			if(!filter_var($vo->$key, FILTER_VALIDATE_BOOLEAN)){ 
				$arrResult = array(
								"state" => false , 
								"value" => $value , 
								"propertyKey" => $propertykey,
								"message" => "Not a valid boolean"
							   );
			}else{	
				$arrResult = array(
								"state" => true , 
								"value" => $value , 
								"propertyKey" => $propertykey,
								"message" => null
							   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks contains a minimum value
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function min_value($vo  , $key , $value , $propertykey){
		try{
			if($vo->$key < $value){ 
				$arrResult = array(
								"state" => false , 
								"value" => $value , 
								"propertyKey" => $propertykey,
								"message" => "Min value (".$value.")"
							   );
			}else{	
				$arrResult = array(
								"state" => true , 
								"value" => $value, 
								"propertyKey" => $propertykey,
								"message" => null
							   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks contains a maximum value
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function max_value($vo  , $key , $value , $propertykey){
		try{
			if($vo->$key > $value){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Max value (".$value.")"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks is a valid float tnumber
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function float_number($vo , $key , $value , $propertykey){
		try{
			if(!is_float($vo->$key)){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Not a valid float number"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks is a valid int tnumber
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function int_number($vo , $key , $value , $propertykey){
		try{
			if(!is_int($vo->$key)){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Not a valid int number"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value ,
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks is a valid internet protocol
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function ip($vo , $key , $value , $propertykey){
		try{
			if(!filter_var($vo->$key, FILTER_VALIDATE_IP)){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Not a valid ip"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks is a valid regular expression
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function reg_exp($vo , $key , $value , $propertykey){
		try{
			if(!preg_match($value, $vo->$key)){ 
				$arrResult = array(
							"state" => false ,
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Not a valid reg exp apply"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks is equal to the reference
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function equal_to($vo , $key , $value , $propertykey){
		try{
			if($vo->$key != $vo->$value){
				$arrResult = array(
							"state" => false ,
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Not equal to (".$value.")"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}	
	
	/**
        Checks if attribute is required
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function required($vo , $key , $value, $propertykey){
		try{
			if($vo->$key == null or $vo->$key == ""){
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => "Required field"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" => null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}	
		
	/**
        Checks if attribute contains the maximum allowed size
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function max_length($vo , $key ,$value ,$propertykey){
		try{
			if(strlen($vo->$key) > $value){
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  "Nax length (".$value.")"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if a valid email
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function email($vo , $key , $value , $propertykey){
		try{
			if(!filter_var($vo->$key, FILTER_VALIDATE_EMAIL)){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  "Not a valid email"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if a valid url
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function url($vo , $key , $value , $propertykey){
		try{
			if(!filter_var($vo->$key, FILTER_VALIDATE_URL)){ 
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  "Not a valid url"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if attribute contains the minumum allowed size
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function min_length($vo , $key ,$value , $propertykey){
		try{
			if(strlen($vo->$key) < $value){
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  "Min length (".$value.")"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}

	/**
        Checks if attribute contains the minumum and maximum allowed size
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function range_length($vo , $key ,$value , $propertykey){
		try{
			$arr = explode("," , $value); ; 
			$result = false;
			if(is_array($arr)){
				if(strlen($vo->$key) < $arr[0] or strlen($vo->$key) > $arr[1]){
					$arrResult = array(
								"state" => false , 
								"value" => $value , 
								"propertyKey" => $propertykey,
								"message" =>  "Range length (".$value.")"
							   );
				}else{	
					$arrResult = array(
								"state" => true , 
								"value" => $value , 
								"propertyKey" => $propertykey,
								"message" => null
							   );
				}
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
	/**
        Checks if attribute is a valid id
        @throws Exception object
        @param object $vo | Value object
        @param bool $state | Boolean with the state
        @param stirng $key | String with the key
        @param stirng $value | String with the value
        @param stirng $propertykey | String with the property key
        @static
        @return array
	*/ 
	public static function id($vo , $key , $value , $propertykey){
		try{
			if(self::required($vo , $key , $value , $propertykey) && $vo->$key <= 0){
				$arrResult = array(
							"state" => false , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  "Not a valid id"
						   );
			}else{	
				$arrResult = array(
							"state" => true , 
							"value" => $value , 
							"propertyKey" => $propertykey,
							"message" =>  null
						   );
			}
		}catch(Exception $e){
			throw $e;
		}
		return $arrResult;
	}
	
}

