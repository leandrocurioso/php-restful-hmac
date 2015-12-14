<?php

namespace Library\_Interface;

/**
    Value Object interface
    This interface signs the value object class methods
*/
interface IValueObject {

	/**
        Self print method signature
        @return void
	*/
	public function self_print();
	/**
        Serialize method signature
        @return void
	*/
	public function serialize();
	/**
        Unserialize method signature
        @return void
	*/
	public function unserialize();

}
