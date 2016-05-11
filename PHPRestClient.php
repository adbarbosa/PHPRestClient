<?php
class PHPRestClient
{
	private $method  = null;
	private $url     = null;
	private $arrData = array();

	private $user    = null;
	private $pass    = null;
	private $hasAuth = false;

	function __construct($method=null, $url=null, $arrData=null, $user=null, $pass=null)
	{
		$this->setBaseConfig($method, $url, $arrData);

	}

	public function setBaseConfig($method=null, $url=null, $arrData=null)
	{
		if(!empty($method))
		{
			switch (strtoupper(trim($method,' ')))
			{
				case 'GET':
					$this->method = 'GET';
					break;

				case 'POST':
					$this->method = 'POST';
					break;

				case 'PUT':
					$this->method = 'PUT';
					break;

				case 'DELETE':
					$this->method = 'DELETE';
					break;

				default:
					trigger_error("Invalid Method! Set POST as default", E_USER_NOTICE);
					$method = 'POST';
					break;
			}
			$this->method = $method;
		}

		if(!empty($url))
		{
			if (!filter_var($url, FILTER_VALIDATE_URL) === false)
			{
				$this->url = $url;
			}
			else
			{
			    trigger_error("Invalid URL: ". $url, E_USER_NOTICE);
			}
		}

		if(!empty($arrData))
		{
			if(is_array($arrData))
			{
				$this->arrData = $arrData;
			}
			else
			{
				trigger_error("Invalid Data! ", E_USER_NOTICE);
			}
		}
	}

	public function setAuthentication($user=null, $pass=null)
	{
		if(!empty($user))
		{
			$this->user = $user;
		}
		if(!empty($pass))
		{
			$this->pass = $pass;
			$this->hasAuth = true;
		}
		else
		{
			$this->hasAuth = false;
			trigger_error("Invalid Method! Set POST as default", E_USER_NOTICE);
		}
	}

	public function  hasAuth()
	{
		return $this->hasAuth;
	}

	public function callApi()
	{
		$curl = curl_init();

	    switch ($this->method)
	    {
			/*case "GET":
				// TODO: Add Code here
				break;**/
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);
	            if ($this->arrData)
				{
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->arrData);
				}
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
			/*case "DELETE":
				// TODO: Add Code here
				break;*/
	        default:
	            if ($this->arrData)
				{
					$url = sprintf("%s?%s", $this->url, http_build_query($this->arrData));
				}
	    }

		if($this->hasAuth)
		{
			// Optional Authentication:
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    curl_setopt($curl, CURLOPT_USERPWD, $this->user.":".$this->pass);
		}

	    curl_setopt($curl, CURLOPT_URL, $this->url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}

	function __destruct() {

	}
}
?>
