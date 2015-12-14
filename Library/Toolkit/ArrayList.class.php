<?php

namespace Library\Toolkit;

use Exception;

/** 
    Array list class
    This class is responsible for array listing
    @package Library\Toolkit
*/ 
class ArrayList {

     /** 
        @access private
        @var array $Items | Array of elements
    */ 
    private $Items = array();

    /** 
        Constructor class method
        @access public
        @throws Exception object
        @param array $arr | Array of variables
        @return void
    */ 
    public function __construct($arr = []) {
        try{
            $this->Items = $arr;
        }catch(Exception $e){
            throw $e;
        }
    }       

    /** 
        This method adds an object into the array list
        @access public
        @throws Exception object
        @param dynamic $obj | Dynamic object
        @param string $key | String with the key       
        @return void
    */ 
    public function add($obj, $key = NULL) {
       try{
            if ($key == NULL) {
                $this->Items[] = $obj;
            } else {
                $this->Items[$key] = $obj;
            }
        }catch(Exception $e){
            throw $e;
        }
    }		

    /** 
        This method returns the first element of the array list
        @access public
        @throws Exception object
        @return dynamic
    */ 
    public function first() { 	
    	try{
            if (!is_array($this->Items)) return $this->Items ; 		
            if (!count($this->Items)) return null; 	
            	reset($this->Items); 		
                return $this->Items[key($this->Items)]; 
        }catch(Exception $e){
            throw $e;
        }    	
     } 		
    
    /** 
        This method returns the last element of the array list
        @access public
        @throws Exception object
        @return dynamic
    */ 
    public function last() { 	
        try{
             if (!is_array($this->Items)) return $this->Items; 	
                if (!count($this->Items)) return null; 	
                 end($this->Items); 	
              return $this->Items[key($this->Items)]; 
         }catch(Exception $e){
            throw $e;
        }
    } 	

    /** 
        This method deletes the given key from array list
        @access public
        @throws Exception object
        @return null or false
    */ 
    public function delete($key) {
        try{
            if (isset($this->Items[$key])) {
                unset($this->Items[$key]);
            } else {
                 return false;
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method gets the given key from array list
        @access public
        @throws Exception object
        @return dynamic
    */ 
    public function get($key) {
        try{
            if (isset($this->Items[$key])) {
                return $this->Items[$key];
            } else {
                 return false;
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method returns the entire array list
        @access public
        @throws Exception object
        @return array
    */ 
    public function get_items() {
        try{
             return $this->Items;
         }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method returns the entire keys from array list
        @access public
        @throws Exception object
        @return array
    */ 
    public function get_keys() {
        try{
             return array_keys($this->Items);
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method returns length of the array
        @access public
        @throws Exception object
        @return int
    */ 
    public function length() {
        try{
            return count($this->Items);
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method checks if the given key exists in the array list
        @access public
        @throws Exception object
        @param string $key | String with the key       
        @return bool
    */ 
    public function is_key($key) {
        try{
            return isset($this->Items[$key]);
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method clears the array list
        @access public
        @throws Exception object
        @return void
    */ 
    public function clear() {
        try{
            unset($this->Items);
            $this->Items = array();
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method shows the array list
        @access public
        @param bool $position | Boolean to print specific item or entire list    
        @throws Exception object
        @return void
    */ 
    public function show($position = NULL) {
        try{
            if($position){
                echo "<pre style='height: 90%; overflow: visible;margin: 0 auto;width:90%;border-radius:20px;padding:20px;font-size:13px;color:#DDD;background-color:#333;'>";
                echo "<h2>Preview ArrayList Object #".spl_object_hash($this)."</h2><hr/>";
                var_dump(self::get($position));
                echo "</pre>";
            }else{
                echo "<pre style='height: 90%; overflow: visible;margin: 0 auto;width:90%;border-radius:20px;padding:20px;font-size:13px;color:#DDD;background-color:#333;'>";
                echo "<h2>Preview ArrayList Object #".spl_object_hash($this)."</h2><hr/>";
                var_dump($this->Items);
                echo "</pre>";
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
        This method returns the json from array list
        @access public
        @throws Exception object
        @return string
    */ 
    public function json_encode() {
        try{
            return json_encode((array)array_values(self::get_items()));
        }catch(Exception $e){
            throw $e;
        }
    }

}