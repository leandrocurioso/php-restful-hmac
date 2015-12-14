<?php

namespace Library\Toolkit;

use Exception;

/** 
    Url class
    This class is responsible for handling url
    @package Library\Toolkit
*/ 
class Url {

	/** 
        @access public
        @var string $DomainName | String with domain name
	*/ 
    public $DomainName;
	/** 
        @access public
        @var string $HttpHost | String with http host
	*/ 
	public $HttpHost;
	/** 
        @access public
        @var string $Protocol | String with the current protocol
	*/ 
    public $Protocol;
	/** 
        @access public
        @var string $FullUrl | String with the full url
	*/ 
	public $FullUrl;
	/** 
        @access public
        @var string $Uri | String with the uniform resource identifier
	*/ 
    public $Uri;
	/** 
        @access public
        @var string $Port | String with the request port
	*/ 
	public $Port;
	/** 
        @access public
        @var string $QueryString | String with the current query string
	*/ 
    public $QueryString;
	/** 
        @access public
        @var array $Urls | Array with the urls
	*/ 
	public $Urls = array();
	/** 
        @access public
        @var string $RequestMethod | String with the current request method
	*/ 
	public $RequestMethod;
	/** 
        @access public
        @var string $HttpAccept | String with the current http accept
	*/ 
	public $HttpAccept;

	/** 
        Constructor method of url class
        @access public
        @throws Exception object
        @return void
	*/ 
	public function __construct(){
		try{
			$server = $_SERVER;
			$this->DomainName = $server['SERVER_NAME'];
			$this->HttpHost = $server['HTTP_HOST'];
			$this->Uri = $server['REQUEST_URI'];
			$this->QueryString = $server['QUERY_STRING'];
			$this->Port = $server['SERVER_PORT'];
			$this->Protocol = ((!empty($server['HTTPS']) && $server['HTTPS'] != 'off') || $server['SERVER_PORT'] == 443) ? "https://" : "http://";
			$this->RequestMethod = $server['REQUEST_METHOD'];
			$this->HttpAccept = $server['HTTP_ACCEPT'];
			self::set_url();
			self::set_full_url();
		}catch(Exception $e){
			throw $e;
		}	
    }
	
	/** 
        This method sets the Urls array property
        @access private
        @throws Exception object
        @return void
	*/ 
	private function set_url(){
       try{
            $url_uri  = $this->Uri . "?";
            $url_pos  = strpos( $url_uri, "?" );
            $url_site = substr( $url_uri, 0, $url_pos );
            $urls     = explode( "/", $url_site );
            array_shift( $urls );
			$newUrls = array();
            foreach($urls as $key => $value){
				if(!empty($value)){
					$newUrls[] = $value;
				}
			}
			$this->Urls = $newUrls;
       }catch(Exception $e){
			throw $e;
		}	
    }
   
	/** 
        This method gets the urls position from Urls array property
        @access public
        @throws Exception object
        @return string or null
	*/ 
	public function url_position($position){
		try{
			if(isset($this->Urls[$position])){
                return $this->Urls[$position];
            }else{
				return null;
			}
		}catch(Exception $e){
			throw $e;
		}
    }

	/** 
        This method sets the full url proprty
        @access public
        @throws Exception object
        @return void
	*/ 
	public function set_full_url() {
		try{
			$this->FullUrl = $this->Protocol.$this->HttpHost.$this->Uri;
		}catch(Exception $e){
			throw $e;
		}
  	}

}
