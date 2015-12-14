<?php

namespace Library\ValueObject;

/** 
    Api user token value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiUserTokenVO extends ValueObject {

	public $Id;
	public $User;
	public $UserToken;
	public $ClientIp;
	public $UserAgent;
	public $AddDatetime;

}