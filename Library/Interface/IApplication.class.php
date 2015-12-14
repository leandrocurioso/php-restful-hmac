<?php

namespace Library\_Interface;

/**
    Application interface
    This interface signs the Application class methods
*/
interface IApplication {

	/**
        Construct method signature
        @return void
	*/
	public function __construct();
	/**
        Set enconding method signature
        @static
        @return void
	*/
	public static function set_encoding();
	/**
        Set timezone method signature
        @static
        @return void
	*/
	public static function set_timezone();
	/**
        Config method signature
        @param String $node
        @return string
	*/
	public static function config($node);
	/**
        Initialize method signature
        @static
        @return Application object
	*/
	public static function initialize();
	/**
        Construct configuration constants method signature
        @static
        @return void
	*/
	public static function construct_configuration_constants();
	/**
        Application state method signature
        @static
        @return void
	*/
	public function application_state();
	/**
        Auto class loader method signature
        @param String $classPath
        @return void
	*/
	public static function auto_class_loader( $classPath );
	/**
        Router method signature
        @return void
	*/
	public function router();
	/**
        Session start method signature
        @return void
	*/
	public function session_start();
	/**
        Change application environment method signature
        @return void
	*/
	public function change_application_environment();

}
