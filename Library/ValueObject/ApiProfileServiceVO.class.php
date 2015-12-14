<?php

namespace Library\ValueObject;

/** 
    Api profile service value object class
    The value object class is a base for others value objects
    @package Library\ValueObject
*/ 
class ApiProfileServiceVO extends ValueObject {

	public $Id;
	public $ApiEntityService;
	public $ApiProfile;
	public $Active;

}