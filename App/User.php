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
		$list = $orm->select($filtro, "users", "mUser", 
			array(
				"UserId"=>"UserId",
				"Name"=>"Name",
				"Email"=>"Email",
				"Password"=>"Password",
				"CreationDate"=>"CreationDate",
				"UpdatedDate"=>"UpdatedDate",
				"Status"=>"Status"
			), "", "", "");
		return iterator_to_array($list);
	}

	function Save($data){
		$orm = new \App\Core\Model($this->db);
		if($data["UserId"] > 0){
			$instances = $this->Users($data["UserId"]);
			$instance = $instances[0];
			$instance->Name = $data["Name"];
			$instance->Email = $data["Email"];
			$instance->Password = $data["Password"];
			$instance->UpdatedDate = $data["UpdatedDate"];
			$instance->Status = $data["Status"];
			return $orm->save($instance, "users", "UserId", 
				array("UserId"=>"UserId", "Name"=>"Name", "Email"=>"Email", "Password"=>"Password", 
					"UpdatedDate"=>"UpdatedDate", "Status"=>"Status"));
		} else {
			return $orm->save($data, "users", "UserId", 
				array("UserId"=>"UserId", "Name"=>"Name", "Email"=>"Email", "Password"=>"Password", 
					"CreationDate"=>"CreationDate", "UpdatedDate"=>"UpdatedDate", "Status"=>"Status"));
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM users WHERE UserId = ?");
		$sentencia->bindParam(1, $data["UserId"]);
		$sentencia->execute();
		return "OK";
	}
}
?>
