<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 *
 * CookieBackpack
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

namespace mk2\backpack_cookie;

use Mk2\Libraries\Backpack;
use mk2\backpack_encrypt\EncryptBackpack;

class CookieBackpack extends Backpack{

	public $name="mk2_cookie_fields";
	public $limit=30;
	public $encrypt=[
		"encAlgolizum"=>"aes-256-cbc",
		"encSalt"=>"mk2cookiesalt123456789********************************************",
		"encPassword"=>"mk2cookiepassword123456789*****************************************",
	];
	public $path="";
	public $domain="";
	public $secure="";

	/**
	 * __construct
	 */
	public function __construct(){
		parent::__construct();
		
		if(!empty($this->alternativeEncrypt)){
			$this->Encrypt=new $this->alternativeEncrypt();
		}
		else{
			$this->Encrypt=new EncryptBackpack();
		}

	}

	/**
	 * write
	 * @param string $name
	 * @param $value
	 */
	public function write($name,$value,$option=array()){

		if(!empty($this->name)){
			$cookie_name=$this->name.$name;
		}
		else
		{
			$cookie_name=$name;
		}

		$value=$this->Encrypt->encode($value,$this->encrypt);

		if(is_array($value)){
			$value=json_encode($value);
		}
	
		if(!empty($option["limit"])){

			if($option["limit"]=="no"){
				$limit=0;
			}
			else
			{
				$limit=time()+$option["limit"];
			}
		}
		else
		{
			$limit=time()+$this->limit;
		}

		if(!empty($option["path"])){
			$path=$option["path"];
		}
		else
		{
			if(!empty($this->path)){
				$path=$this->path;
			}
			else
			{
				$path="/";
			}
		}

		if(!empty($option["domain"])){
			$domain=$option["domain"];
		}
		else
		{
			if(!empty($this->domain)){
				$domain=$this->domain;
			}
		}

		if(!empty($option["secure"])){
			$secure=$option["secure"];
		}
		else
		{
			if(!empty($this->secure)){
				$secure=$this->secure;
			}
		}

		setcookie($cookie_name,$value,@$limit,$path,@$domain,@$secure);
		
	}

	/**
	 * read
	 * @param string $name
	 */
	public function read($name=null){

		if(!empty($this->name)){
			$cookie_name=$this->name.$name;
		}
		else
		{
			$cookie_name=$name;
		}

		if(!empty($_COOKIE[$cookie_name])){
			$source=@$_COOKIE[$cookie_name];
		}
		else
		{
			return null;
		}

		$source=$this->Encrypt->decode($source,$this->encrypt);

		return $source;
	}

	/**
	 * delete
	 * @param string $name
	 * @param array $option = []
	 */
	public function delete($name,$option=[]){

		if(!empty($this->name)){
			$cookie_name=$this->name.$name;
		}
		else
		{
			$cookie_name=$name;
		}

		if(!empty($option["path"])){
			$path=$option["path"];
		}
		else
		{
			if(!empty($this->path)){
				$path=$this->path;
			}
			else
			{
				$path="/";
			}
		}

		if(!empty($option["domain"])){
			$domain=$option["domain"];
		}
		else
		{
			if(!empty($this->domain)){
				$domain=$this->domain;
			}
		}

		if(!empty($option["secure"])){
			$secure=$option["secure"];
		}
		else
		{
			if(!empty($this->secure)){
				$secure=$this->secure;
			}
		}

		setcookie($cookie_name,"",time()-1000,@$path,@$domain,@$secure);

		return;
	}

}