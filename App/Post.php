<?php
namespace App;

class Post {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Posts($id){
		$imgAux = "\App\Image";
		$imgController = new $imgAux($this->db);
		$orm = new \App\Core\Model($this->db);
		$filtro = array("Status" => 1);
		if($id > 0){
			$filtro["PostId"] = $id;
		}
		$list = $orm->select($filtro, "Posts", "mPost", 
			array(
				"PostId"=>"PostId",
                "UserId"=>"UserId",
                "CategoryId"=>"CategoryId",
                "Title"=>"Title",
                "Description"=>"Description",
				"CreationDate"=>"CreationDate",
				"UpdatedDate"=>"UpdatedDate",
                "Status"=>"Status"
			), "", "", "");
		$items = iterator_to_array ($list);
		foreach($items as $item){
			$item->Images = $imgController->{"PostImages"}($item->PostId);
		}
		return $items;
	}

	function Save($data){
		$imgAux = "\App\Image";
		$imgController = new $imgAux($this->db);
		$orm = new \App\Core\Model($this->db);
		//Edita
		if($data["PostId"] > 0){
			$instances = $this->Posts($data["PostId"]);
			$instance = $instances[0];
			$instance->UserId = $data["UserId"];
			$instance->CategoryId = $data["CategoryId"];
			$instance->Title = $data["Title"];
			$instance->Description = $data["Description"];
			$instance->CreationDate = $data["CreationDate"];
			$instance->UpdatedDate = $data["UpdatedDate"];
			$instance->Status = $data["Status"];

			$PostId =  $orm->save($instance, "Posts", "PostId", 
                array(
                    "PostId"=>"PostId",
                    "UserId"=>"UserId",
                    "CategoryId"=>"CategoryId",
                    "Title"=>"Title",
                    "Description"=>"Description",
                    "CreationDate"=>"CreationDate",
                    "UpdatedDate"=>"UpdatedDate",
                    "Status"=>"Status"
            ));

			foreach($data["Images"] as $item){
				$item["PostId"] = $instance->PostId;
				$imgController->{"Save"}($item);
			}

			return $PostId;

		} else {

			$PostId =  (int)$orm->save($data, "Posts", "PostId", 
                array(
                    "PostId"=>"PostId",
                    "UserId"=>"UserId",
                    "CategoryId"=>"CategoryId",
                    "Title"=>"Title",
                    "Description"=>"Description",
                    "CreationDate"=>"CreationDate",
                    "UpdatedDate"=>"UpdatedDate",
                    "Status"=>"Status"
            ));

			foreach($data["Images"] as $item){
				$item["PostId"] = $PostId;
				$imgController->{"Save"}($item);
			}

			return $PostId;
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Posts WHERE PostId = ?");
		$sentencia->bindParam(1, $data["PostId"]);
		$sentencia->execute();
		return "OK";
	}

	function UpdateStatus($data){
		$orm = new \App\Core\Model($this->db);
		if($data["PostId"] > 0){
			$instances = $this->Posts($data["PostId"]);
			$instance = $instances[0];
			$instance->Status = $data["Status"];

			$PostId =  $orm->save($instance, "Posts", "PostId", 
                array(
                    "PostId"=>"PostId",
                    "Status"=>"Status"
            ));

			return $PostId;

		}
	}
}
?>
