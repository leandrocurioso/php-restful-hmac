<?php

/** 
    Http handler class
    This class is responsible for handle http properties
*/ 

class HttpHandler {
	
	/**
        Header method for heading response
        @throws Exception object
        @param int $code
		$@static
        @return void
	*/ 
	public static function header($code){  
		try{
			header("HTTP/1.1 ".$code." ".self::get_http_code_info($code)."");
			exit;
		 }catch(Exception $e){
			throw $e;
		}
	}

	/**
        Get http code info
        @throws Exception object
        @param int $code
		$@static
        @return string
	*/ 
	public static function get_http_code_info($code){
		try{
			$strheader = "";
			switch($code){
				case 100: $strheader .= "Continue"; break;
				case 101: $strheader .= "Switching Protocols"; break;
				case 102: $strheader .= "Processing (WebDAV)"; break;
				case 200: $strheader .= "OK"; break;
				case 201: $strheader .= "Created"; break;
				case 202: $strheader .= "Accepted";	break;
				case 203: $strheader .= "Non-Authoritative Information"; break;
				case 204: $strheader .= "No Content"; break;
				case 205: $strheader .= "Reset Content"; break;
				case 206: $strheader .= "Partial Content"; break;
				case 207: $strheader .= "Multi-Status (WebDAV)"; break;
				case 208: $strheader .= "Already Reported (WebDAV)"; break;
				case 226: $strheader .= "IM Used"; break;
				case 300: $strheader .= "Multiple Choices"; break;
				case 301: $strheader .= "Moved Permanently"; break;
				case 302: $strheader .= "Found"; break;
				case 303: $strheader .= "See Other"; break;
				case 304: $strheader .= "Not Modified";	break;
				case 305: $strheader .= "Use Proxy"; break;
				case 306: $strheader .= "(Unused)";	break;
				case 307: $strheader .= "Temporary Redirect"; break;
				case 308: $strheader .= "Permanent Redirect (experiemental)"; break;
				case 400: $strheader .= "Bad Request"; break;
				case 401: $strheader .= "Unauthorized";	break;
				case 402: $strheader .= "Payment Required";	break;
				case 403: $strheader .= "Forbidden"; break;
				case 404: $strheader .= "Not Found"; break;
				case 405: $strheader .= "Method Not Allowed"; break;
				case 406: $strheader .= "Not Acceptable"; break;
				case 407: $strheader .= "Proxy Authentication Required"; break;
				case 408: $strheader .= "Request Timeout"; break;
				case 409: $strheader .= "Conflict";	break;
				case 410: $strheader .= "Gone";	break;
				case 411: $strheader .= "Length Required"; break;
				case 412: $strheader .= "Precondition Failed";	break;
				case 413: $strheader .= "Request Entity Too Large";	break;
				case 414: $strheader .= "Request-URI Too Long";	break;
				case 415: $strheader .= "Unsupported Media Type"; break;
				case 416: $strheader .= "Requested Range Not Satisfiable";	break;
				case 417: $strheader .= "Expectation Failed"; break;
				case 418: $strheader .= "I'm a teapot (RFC 2324)";	break;
				case 420: $strheader .= "Enhance Your Calm (Twitter)"; break;
				case 410: $strheader .= "Gone";	break;
				case 422: $strheader .= "Unprocessable Entity (WebDAV)"; break;
				case 423: $strheader .= "Locked (WebDAV)";	break;
				case 424: $strheader .= "Failed Dependency (WebDAV)"; break;
				case 425: $strheader .= "Reserved for WebDAV";	break;
				case 426: $strheader .= "Upgrade Required";	break;
				case 428: $strheader .= "Precondition Required"; break;
				case 429: $strheader .= "Too Many Requests"; break;
				case 431: $strheader .= "Request Header Fields Too Large"; break;
				case 444: $strheader .= "No Response (Nginx)"; break;
				case 449: $strheader .= "Retry With (Microsoft)"; break;
				case 450: $strheader .= "Blocked by Windows Parental Controls (Microsoft)";	break;
				case 499: $strheader .= "Client Closed Request (Nginx)"; break;
				case 500: $strheader .= "Internal Server Error"; break;
				case 501: $strheader .= "Not Implemented";	break;
				case 502: $strheader .= "Bad Gateway";	break;
				case 503: $strheader .= "Service Unavailable";	break;
				case 504: $strheader .= "Gateway Timeout";	break;
				case 505: $strheader .= "HTTP Version Not Supported"; break;
				case 506: $strheader .= "Variant Also Negotiates (Experimental)"; break;
				case 507: $strheader .= "Insufficient Storage (WebDAV)"; break;
				case 508: $strheader .= "Loop Detected (WebDAV)"; break;
				case 509: $strheader .= "Bandwidth Limit Exceeded (Apache)"; break;
				case 510: $strheader .= "Not Extended";	break;
				case 511: $strheader .= "Network Authentication Required"; break;
				case 598: $strheader .= "Network read timeout error"; break;
				case 599: $strheader .= "Network connect timeout error"; break;
			}
			return $strheader;
		}catch(Exception $e){
			throw $e;
		}
	}
	
}