<?php

namespace Library\ValueObject;

/** 
    Api log value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiLogVO extends ValueObject {

	public $Id;
	public $User;
	public $ApiApplication;
	public $Content;
	public $UserToken;
	public $ApiEntity;
	public $ApiEntityService;
	public $Parameter;
	public $HttpVerb;
	public $ClientIp;
	public $ServerIp;
	public $AddDatetime;

}