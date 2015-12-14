<?php

use Core\Application;

/**
    Restful HMAC

    @author Leandro Curioso <leandro.curioso@gmail.com>
    @copyright Leandro Curioso © 2015
*/

/**
    Boot the application
*/

try{

	//Require application interface
	require_once("Library/Interface/IApplication.class.php");

	//Require application class
	require_once("Library/Application.class.php");

	//Initialize the application
	Application::initialize();

}catch(Exception $e){
	throw $e;
}
