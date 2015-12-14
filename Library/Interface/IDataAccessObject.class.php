<?phpnamespace Library\_Interface;use Library\Toolkit\PDOHandler;use Library\ValueObject\ValueObject;/**    Data access object interface class   This interface signs the data access class methods*/interface IDataAccessObject {	/**        Constructor method signature        @param object $pdo        @return void	*/	public function __construct(PDOHandler $pdoHandler);	/**        Create method signature        @param object $objVO        @return int	*/	public function create(ValueObject $objVO);	/**        Read method signature        @param object $objVO        @return object	*/	public function read(ValueObject $objVO);	/**        Update method signature        @param object $objVO        @return void	*/	public function update(ValueObject $objVO);	/**        Delete method signature        @param object $objVO        @return void	*/	public function delete(ValueObject $objVO);	/**        list all method signature        @param object $objVO		@param object $options        @return object	*/	public function list_all(ValueObject $objVO = null , $options = []);}