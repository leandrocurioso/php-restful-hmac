<?phpnamespace Library\ValueObject;use Library\_Interface\IValueObject;use Library\Toolkit\AnnotationCore;use Library\Toolkit\ModelAnnotation;use Exception;/**    Value object class    The value object class is a base for others value objects    @package = Library\ValueObject    @interface IValueObject*/abstract class ValueObject implements IValueObject {	/**		This method prints the current value object		@access public		@throws Exception object		@param boolean $dump | Boolean value to change print schema		@return void	*/	public function self_print($dump = false){		try{			if($dump == false){                echo "<pre style='display:table;height: 100%; overflow: visible;margin: 0 auto;width:90%;border-radius:20px;padding:20px;font-size:13px;color:#DDD;background-color:#333;'>";                echo "<h2>Preview Value Object #".spl_object_hash($this)."</h2><hr/>";				print_r($this);				echo "</pre>";			}else{				echo "<pre style='display:table;height: 100%; overflow: visible;margin: 0 auto;width:90%;border-radius:20px;padding:20px;font-size:13px;color:#DDD;background-color:#333;'>";                echo "<h2>Preview Value Object #".spl_object_hash($this)."</h2><hr/>";				var_dump($this);				echo "</pre>";			}		}catch(Exception $ex){			throw $ex;		}	}	/**		This method seriliazes the value object		@access public		@throws Exception object		@return string	*/	public function serialize(){		try{			return serialize($this);		}catch(Exception $ex){			throw $ex;		}	}	/**		This method unseriliazes the value object		@access public		@throws Exception object		@return object	*/	public function unserialize(){		try{			return unserialize($this);		}catch(Exception $ex){			throw $ex;		}	}}