<?php

namespace Library\Toolkit;

use Exception;

/**
    Utility class
    This class is responsible for utilities methods
    @package Library\Toolkit
*/
class Utility  {

	/**
	 * This method print object for debug
	 * @access public
	 * @throws Exception object
	 * @static
	 * @return void
	 */
	public static function debug($obj){
			try {
					echo "<pre>";
					print_r($obj);
					echo "</pre>";
			} catch (Exception $e) {
					throw $e;
			}
	}

	/**
        Method responsible for checking if the string can be a boolean value
        @access public
        @throws Exception object
        @param string $string | String with the boolean value
        @static
        @return boolean
	*/
	public static function check_bool($string){
		try{
		    $string = strtolower($string);
		    return (in_array($string, array("true", "false", "1", "0", "yes", "no"), true));
	    }catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method checks if a string conatins a given string
        @access public
        @throws Exception object
        @param string $haystack | String to be checked
        @param string $needle | String with needle
        @static
        @return boolean
	*/
	public static function contains_string($haystack, $needle)	{
		try{
			if (strpos($haystack, $needle) !== false){
				return true;
			}else {
				return false;
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method serializes a given object in session
        @access public
        @throws Exception object
        @param object $obj | Some object
        @param string $sessionName | String with session key
        @static
        @return void
	*/
	public static function session_object_serializer($obj,$sessionName){
		try{
			if($obj){
				$_SESSION[$sessionName] = serialize($obj);
			}
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method unserializes a given session in object
        @access public
        @throws Exception object
        @param string $sessionName | String with session key
        @static
        @return object
	*/
	public static function session_object_unserializer($sessionName){
		try{
			return unserialize($_SESSION[$sessionName]);
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method gets an image and return its base64 encodding
        @access public
        @throws Exception object
        @param string $imgSrc | String with image path
        @static
        @return string
	*/
	public static function image_to_base64($imgSrc){
		try{
			$imgbinary = fread(fopen($imgSrc, "r"), filesize($imgSrc));
			$imgSrc = base64_encode($imgbinary);
			return $imgSrc;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method generates a unique token
        @access public
        @throws Exception object
        @static
        @return string
	*/
	 public static function generate_token(){
		try{
			return md5(uniqid(openssl_random_pseudo_bytes(32).mt_rand(), true));
		}catch(Exception $e){
			throw $e;
		}
	 }

	/**
        This method returns a uppercased string
        @access public
        @throws Exception object
        @param string $str | String to be uppercased
        @static
        @return string
	*/
	public static function upper ($str) {
		try{
			$LATIN_UC_CHARS = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ°°ª";
			$LATIN_LC_CHARS = "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý°ºª";
			$str = strtr ($str, $LATIN_LC_CHARS, $LATIN_UC_CHARS);
			$str = strtoupper($str);
			return $str;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method returns an escaped string
        @access public
        @throws Exception object
        @param string $string | String to be escaped
        @static
        @return string
	*/
	public static function escape_letter($string) {
		try{
			$table = array(
				'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z',
				'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
				'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
				'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
				'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
				'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
				'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
				'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
				'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
				'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
				'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
				'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
				'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
				'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
				'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
			);

			$string = strtr($string, $table);
			$string = strtolower($string);
			$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
			$string = preg_replace("/[\s-]+/", " ", $string);
			$string = preg_replace("/[\s_]/", "-", $string);
			return $string;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method returns a shortened string
        @access public
        @throws Exception object
        @param string $string | String to be shortened
        @param int $chars | Int string limit
        @static
        @return string
	*/
	public static function text_shortener($string,$chars) {
		try{
			if (strlen($string) > $chars) {
				while (substr($string,$chars,1) <> ' ' && ($chars < strlen($string))){
					$chars++;
				};
			};
			return substr($string,0,$chars);
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method returns brazilian address information
        @access public
        @throws Exception object
        @param string $zipCode | String with the zipcode
        @param string $format | String with the return format
        @static
        @return string
	*/
	public static function get_address_by_zipcode( $zipCode , $format = "json" ) {
		try{
			$result = mb_convert_encoding(@file_get_contents( 'http://republicavirtual.com.br/web_cep.php?cep='.urlencode( $zipCode ).'&formato='.$format.'' ),'ISO-8859-1','UTF-8');
			if( !$result ) {
				$result = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
			}
			parse_str( $result, $final_return );
			if( is_array($final_return) ) {
				foreach($final_return as $utf8_return => $name){
					$parsed_result[$utf8_return] = utf8_encode($name);
				}
				$r = $parsed_result;
			}else{
				$r = $final_return;
			}
			return $r;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method returns  the current datetime
        @access public
        @throws Exception object
        @static
        @return datetime string
	*/
	public static function get_datetime(){
		try{
			return date("Y-m-d H:i:s");
		}catch(Exception $ex){
			throw $ex;
		}
	}

    /**
        This method forces a browser download
        @access public
        @throws Exception object
        @param string $file | String with the file path
        @static
        @return void
	*/
    public static function download_file($file) {
    	try{
	    	if(file_exists($file)) {
	            header('Content-Description: File Transfer');
	            header('Content-Type: application/octet-stream');
	            header('Content-Disposition: attachment; filename='.basename($file));
	            header('Content-Transfer-Encoding: binary');
	            header('Expires: 0');
	            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	            header('Pragma: public');
	            header('Content-Length: ' . filesize($file));
	            ob_clean();
	            flush();
	            readfile($file);
	            exit;
	        }
        }catch(Exception $e){
        	throw $e;
        }
    }

	/**
        This method returns the current date
        @access public
        @throws Exception object
        @static
        @return date string
	*/
	public static function get_date(){
		try{
			return date("Y-m-d");
		}catch(Exception $e){
			throw $e;
		}
	}


    /**
     * Method responsible for set object with reference input
     * @access public
     * @throws Exception object
     * @param string $string | String with the boolean value
     * @static
     * @return boolean
     */
    public static function set_object_input($object, $request)
    {
        $arrObjVar = get_object_vars($request);

        try {
            foreach ($arrObjVar as $key => $value) {
                if (property_exists($object, self::get_property_input($key))) {
                    $property = self::get_property_input($key);
                    $object->$property = $value;
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    private static function get_property_input($str)
    {
        $arrStr = explode("-", $str);
        if (is_array($arrStr) && count($arrStr) > 0) {
            if (isset($arrStr[1]))
                return $arrStr[1];
            else
                return $arrStr[0];
        } else {
            return $str;
        }
    }

	 /**
        This method converts an array into a xml stirng
        @access public
        @throws Exception object
        @param array $data | Array to be converted
        @param string $rootNodeName | String with the xml root node
        @param string $xml | String with the xml
        @static
        @return stirng
	*/
	public static function array_to_xml($data, $rootNodeName = 'root', $xml = null) {
		try{
			//Desligamos essa opção para evitar bugs
			if (ini_get('zend.ze1_compatibility_mode') == 1) {
				ini_set('zend.ze1_compatibility_mode', 0);
			}

			if ($xml == null) {
				$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
			}

			// faz o loop no array
			foreach ($data as $key => $value) {
				// se for indice numerico ele renomeia o indice
				if (is_numeric($key)) {
					$key = "unknownNode_" . (string) $key;
				}

				// substituir qualquer coisa não alfa númerico
				$key = preg_replace('/[^a-z]/i', '', $key);

				if (is_array($value)) {
					$node = $xml->addChild($key);
					toXml($value, $rootNodeName, $node);
				} else {
					$value = htmlentities($value);
					$xml->addChild($key, $value);
				}
			}
			return $xml->asXML();
		}catch(Exception $e){
			throw $e;
		}
	}

	 /**
        This method returns a client ip
        @access public
        @throws Exception object
        @static
        @return stirng
	*/
    public static function get_client_ip(){
        try{
            if(array_key_exists("HTTP_X_FORWARDED_FOR" , $_SERVER)){
                $ipList = $_SERVER["HTTP_X_FORWARDED_FOR"];
                $arrIp = explode("," , $ipList);
                if(array_key_exists(0,$arrIp)){
                    $clientIp = $arrIp[0];
                }else{
                    $clientIp = $ipList;
                }
            }else{
                $clientIp = $_SERVER["REMOTE_ADDR"];
            }
            return $clientIp;
        }catch(Exception $e){
            throw $e;
        }
    }

	/**
        This method converts an array into a stdObject
        @access public
        @throws Exception object
        @param array $arr | Array to be converted into an object
        @static
        @return stirng
	*/
	public static function array_to_object($arr = array()) {
		try{
			return (object) json_decode (json_encode ($arr), FALSE);
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method uploads a file
        @access public
        @throws Exception object
        @param string $file | String with the file path
        @param string $extension | String with the allowed extentions
        @param string $dir | String with the destination folder
        @static
        @return stirng
	*/
	public static function file_upload($file, $extension, $dir) {
		try {
			/* CHECK FILE NAME */
			if ($file->name) {

				/* FILE NAME */
				$file_name = strtolower ( $file->name );

				/* CHECK ALLOWED EXTENSION */
				if (preg_match ( "/\.($extension){1}$/i", $file_name, $ext )) {
					/* CHECK IF FOLDER EXISTS */
					if (! is_dir ( $dir )) {
						/* CREATE FOLDER */
						$oldmask = umask ( 0 );
						mkdir ( $dir, 0777, true );
						umask ( $oldmask );
					}

					/* RENAME THE FILE */
					$file_name = md5 ( uniqid ( time () ) ) . "." . $ext [1];

					/* DEFINE DESTINY DIRETORY */
					$full_path = $dir . $file_name;

					/* MOVE THE NEW FILE AND RETURN THE NAME */
					if (@move_uploaded_file ( $file->tmp_name, $full_path )) {
						/* RETURN */
						$image_options = array ();
						$image_options['file_name'] = $file_name;
						$image_options['full_path'] = $full_path;

						return $image_options;
					}else{
						return false;
					}
				}
			}
		} catch ( Exception $e) {
			throw $e;
		}
	}

	/**
        This method checks for a valid datetime stirng
        @access public
        @throws Exception object
        @param datetime string $date | String with the datetime
        @param string $format | String with datetime format
        @static
        @return boolean
	*/
  	public static function validate_datetime($date, $format = 'Y-m-d H:i:s'){
   		try{
	   		$d = DateTime::createFromFormat($format, $date);
	    	return $d && $d->format($format) == $date;
    	}catch(Exception $e){
			throw $e;
		}
	}

	/**
        This method checks for a valid date stirng
        @access public
        @throws Exception object
        @param date string $date | String with the date
        @param string $format | String with datetime format
        @static
        @return boolean
	*/
  	public static function validate_date($date, $format = 'Y-m-d'){
   		try{
	   		$d = DateTime::createFromFormat($format, $date);
	    	return $d && $d->format($format) == $date;
    	}catch(Exception $e){
			throw $e;
		}
	}


	/**
        This method checks for a valid date stirng
        @access public
        @throws Exception object
        @param array $query | Array of any format
        @static
        @return string
	*/
	public static function build_http_query( $query ){
		try{
		    $query_array = array();
		    foreach( $query as $key => $key_value ){
		        $query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );
		    }
		    return implode( '&', $query_array );
	    }catch(Exception  $e){
				throw $ex;
		}
	}

}
