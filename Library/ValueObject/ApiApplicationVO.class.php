<?php

namespace Library\ValueObject;

/** 
    Api application value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiApplicationVO extends ValueObject {

	public $Id;
	public $Name;
	public $ApiKey;
	public $ApiSecretKey;
	public $AddDatetime;

}