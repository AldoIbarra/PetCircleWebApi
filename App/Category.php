<?php
namespace App;

class Category {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Categories($id){
		$orm = new \App\Core\Model($this->db);
		$filtro = array();
		if($id > 0){
			$filtro = array("CategoryId"=>$id);
		}
		$list = $orm->select($filtro, "Categories", "mCategory", 
			array(
				"CategoryId"=>"CategoryId",
				"Name"=>"Name",
				"Description"=>"Description",
				"Status"=>"Status"
			), "", "", "");
		return iterator_to_array($list);
	}

	function Save($data){
		$orm = new \App\Core\Model($this->db);
		if($data["CategoryId"] > 0){
			$instances = $this->Categories($data["CategoryId"]);
			$instance = $instances[0];
			$instance->Name = $data["Name"];
			$instance->Description = $data["Description"];
			$instance->Status = $data["Status"];
			return $orm->save($instance, "Categories", "CategoryId", 
				array("CategoryId"=>"CategoryId", "Name"=>"Name", "Description"=>"Description","Status"=>"Status"));
		} else {
			return $orm->save($data, "Categories", "CategoryId", 
				array("CategoryId"=>"CategoryId", "Name"=>"Name", "Description"=>"Description","Status"=>"Status"));
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Categories WHERE CategoryId = ?");
		$sentencia->bindParam(1, $data["CategoryId"]);
		$sentencia->execute();
		return "OK";
	}

	function CategoryNameById($id){
		$orm = new \App\Core\Model($this->db);
		$filtro = array("CategoryId"=>$id);

		$list = $orm->select($filtro, "Categories", "mCategory", 
			array(
				"CategoryId"=>"CategoryId",
				"Name"=>"Name",
			), "", "", "");
		$result =  iterator_to_array($list);
		return $result[0]->Name;
	}
}
?>
