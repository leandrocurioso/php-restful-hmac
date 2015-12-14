<?php

namespace Library\ValueObject;

/** 
    Api entity value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiEntityVO extends ValueObject {

	public $Id;
	public $Name;
	public $Active;

}