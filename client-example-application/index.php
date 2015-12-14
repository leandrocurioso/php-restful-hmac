<?php

/** 
    Client example
    @author Leandro Curioso <leandro.curioso@gmail.com>
    @copyright Leandro Curioso © 2015
*/ 

try{
	
    //Report all errors
	error_reporting(E_ALL);
	
	//Require the http handler class
	require_once("class/HttpHandler.class.php");
	
	//Require the utility class
	require_once("class/Utility.class.php");
	
	//Require the service request class
	require_once("class/ServiceRequest.class.php");

	//Options for service request
	$options = array(
		"authorization_prefix" => "Authorization",
		"host"                 => "http://localhost:8080/php-restful-hmac",
		"api_key"              => "b0882ec326528cbdb8617813b5c0c5eccc7537b2",
		"api_secret_key"       => "afccb252d578f7c7529f253ebea35a3a82d8054e"
	);
	
	//New instance of service request
	$serviceRequest = new ServiceRequest($options);

	echo "<h1>Client example</h1>";
	echo "<p>The following calls are some example of how can you use the restful webservice!</p>";

	//Example of auth method - This method returns the token
	echo "<h2 style='color:green;'>[POST] Service route: /user/auth</h2>";
	$auth = $serviceRequest->post("/user/auth",
	[
		"email"=> "john.doe@email.com",
		"password"=> "123456"
	],null,false);
	Utility::debug($auth);

	//Set the token for authenticated methods
	$userToken = $auth->payload->data->token;
	
	//Example of get request to an unauthenticated route to list user
	echo "<h2 style='color:green;'>[GET] Service route: /user/list_user [TOKEN: $userToken]</h2>";
	$listUser = $serviceRequest->get("/user/list_user",null,null,false);
	Utility::debug($listUser);
	
	//Example of get request to an unauthenticated route to read single user
	echo "<h2 style='color:green;'>[GET] Service route: /user/read_user [TOKEN: $userToken]</h2>";
	$readUser = $serviceRequest->get("/user/read_user",["id"=>1],null,false);
	Utility::debug($readUser);
	
	//Example of post request to an authenticated route to create an user
	echo "<h2 style='color:green;'>[POST] Service: /user/create [TOKEN: $userToken]</h2>";
	$create = $serviceRequest->post("/user/create",
	[
		"name"=>"Darth Vader",
		"email"=>"darth.vader@email.com",
		"password"=>"123456"
	],$userToken,false);
	Utility::debug($create);
	
	//Example of put request to an authenticated route to update an user
	echo "<h2 style='color:green;'>[PUT] Service route: /user/update [TOKEN: $userToken]</h2>";
	$update = $serviceRequest->put("/user/update",
	[
		"id"=>1,
		"name"=>"Luke Skywalker"
	],$userToken,false);
	Utility::debug($update);

	//Example of delete request to an authenticated route to delete an user
	echo "<h2 style='color:green;'>[DELETE] Service route: /user/delete [TOKEN: $userToken]</h2>";
	$delete = $serviceRequest->delete("/user/delete",
	[
		"id"=>2
	],$userToken,false);
	Utility::debug($delete);
	
}catch(Exception $e){
	throw $e;
}