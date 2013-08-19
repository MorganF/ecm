<?php
class Ecm_DbInterface
{
	protected $connection;
	
	public function __construct ()
	{
		$this->connection = mysqli_connect("localhost","root","","morgancms") or die ('Database connection failed.');
		$this->connection->set_charset("utf8");
	}
	
	public function query ($query)
	{
		return $this->connection->query($query);
	}
	public function fetch ($result)
	{
		return mysqli_fetch_object($result);
	}
	
	public function countRows($result)
	{
		return mysqli_num_rows($result);
	}
	
	public function affectedRows ()
	{
		return mysqli_affected_rows($this->connection);
	}
	
	public function lastInsertId ()
	{
		return mysqli_insert_id($this->connection);
	}
	
	public function secureString ($string)
	{
		return mysqli_real_escape_string($this->connection, $string);
	}
	
	public function error ()
	{
		return mysqli_error($this->connection);
	}
	
	public function processConjonction ($cond)
	{
		$return = '';
		
		foreach ($cond as $field => $value)
		{
			if (!empty($return))
				$return .= ' And ';
			
			$value = $this->secureString($value);
			$return .= "$field='$value'";
		}
		
		return $return;
	}
}