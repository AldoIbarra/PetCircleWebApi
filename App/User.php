<?php
namespace App;

class User {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Users($id){
		$orm = new \App\Core\Model($this->db);
		$filtro = array();
		if($id > 0){
			$filtro = array("UserId"=>$id);
		}
		$list = $orm->select($filtro, "Users", "mUser", 
			array(
				"UserId"=>"UserId",
				"FullName"=>"FullName",
				"Password"=>"Password",
				"PhoneNumber"=>"PhoneNumber",
				"NickName"=>"NickName",
				"Img"=>"Img",
				"Email"=>"Email",
				"Status"=>"Status",
				"CreationDate"=>"CreationDate",
				"UpdatedDate"=>"UpdatedDate"
			), "", "", "");
		$items = iterator_to_array ($list);
		/*se convierte en base64 el contenido antes de retornar el objeto*/
		foreach($items as $item){
			$item->Img = "data:image/png;base64," . base64_encode($item->Img);
		}
		return  $items;
	}

	function Save($data){
		$orm = new \App\Core\Model($this->db);
		if($data["UserId"] > 0){
			$instances = $this->Users($data["UserId"]);
			$instance = $instances[0];
			$instance->FullName = $data["FullName"];
			$instance->Password = $data["Password"];
			$instance->PhoneNumber = $data["PhoneNumber"];
			$instance->NickName = $data["NickName"];
			$instance->Img = $data["Img"];
			$instance->Email = $data["Email"];
			$instance->Status = $data["Status"];
			$instance->CreationDate = $data["CreationDate"];
			$instance->UpdatedDate = $data["UpdatedDate"];

			if(isset($instance->Img) ){
				$datab = $instance->Img;
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$instance->Img = base64_decode($datab);
			}

			return $orm->save($instance, "Users", "UserId", 
				array("UserId"=>"UserId", "FullName"=>"FullName", "Password"=>"Password", "PhoneNumber"=>"PhoneNumber", "NickName"=>"NickName", 
				"Img"=>"Img", "Email"=>"Email", "Status"=>"Status", "UpdatedDate"=>"UpdatedDate"));
		}
		else {

			if(isset($data["Img"])){
				$datab = $data["Img"];
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$data["Img"] = base64_decode($datab);
			}

			return $orm->save($data, "Users", "UserId", 
				array("UserId"=>"UserId", "FullName"=>"FullName", "Password"=>"Password", "PhoneNumber"=>"PhoneNumber", "NickName"=>"NickName",
				"Img"=>"Img", "Email"=>"Email", "Status"=>"Status", "CreationDate"=>"CreationDate", "UpdatedDate"=>"UpdatedDate"));
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Users WHERE UserId = ?");
		$sentencia->bindParam(1, $data["UserId"]);
		$sentencia->execute();
		return "OK";
	}
}
?>
