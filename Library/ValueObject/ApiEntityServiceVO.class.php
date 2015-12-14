<?php

namespace Library\ValueObject;

/** 
    Api entity service value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiEntityServiceVO extends ValueObject {

	public $Id;
	public $ApiEntity;
	public $Name;
	public $Description;
	public $HttpVerb;
	public $Active;

}