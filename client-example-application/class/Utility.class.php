<?php

/**
 * Utility class
 * This class is responsible for utilities methods
 */
 
class Utility {

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
     * This method converts an array into a stdObject
     * @access public
     * @throws Exception object
     * @param array $arr | Array to be converted into an object
     * @static
     * @return stirng
     */
    public static function array_to_object($arr = array()){
        try {
            return (object)json_decode(json_encode($arr), FALSE);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * This method checks for a valid date stirng
     * @access public
     * @throws Exception object
     * @param array $query | Array of any format
     * @static
     * @return string
     */
    public static function build_http_query($query = array()){
        try {
            $query_array = array();
			if(count($query) > 0){
				foreach ($query as $key => $key_value) {
					$query_array[] = urlencode($key) . '=' . urlencode($key_value);
				}
				return implode('&', $query_array);
			}else{
				return "";
			}
        } catch (Exception  $e) {
            throw $ex;
        }
    }

}	
