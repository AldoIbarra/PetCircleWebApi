<?php
namespace App;

class Post {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Posts($id){
		$orm = new \App\Core\Model($this->db);
        $joins = array(["Condition"=>"Left", "Table"=>"Files", "RelationColumn"=>"PostId", "Prefix"=>" p"],
		["Condition"=>"Left", "Table"=>"Images", "RelationColumn"=>"PostId", "Prefix"=>" p"]);
		$filtro = array();
		if($id > 0){
			$filtro = array("PostId"=>$id);
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
                "Status"=>"Status",
				"Images"=> array(
					"ImgId"=>"ImgId",
					"PostId"=>"PostId",
					"Img"=>"Img"
				),
				"Files"=> array(
					"FileId"=>"FileId",
					"PostId"=>"PostId",
					"File"=>"File"
				)
			), "", "", "", $joins);
		return iterator_to_array ($list);
	}

	function Save($data){
		$imgAux = "\App\Image";
		$imgController = new $imgAux($this->db);
		// $fileAux = "\App\File";
		// $fileController = new $fileAux($this->db);
		$orm = new \App\Core\Model($this->db);
		//Edita
		if($data["PostId"] > 0){
			$instances = $this->Posts($data["PostId"]);
			$instance = $instances[0];
			$instance->UserId = $data["UserId"];
			$instance->CategoryId = $data["CategoryId"];
			$instance->Title = $data["Title"];
			$instance->Descripion = $data["Description"];
			$instance->CreationDate = $data["CreationDate"];
			$instance->UpdatedDate = $data["UpdatedDate"];
			$instance->Status = $data["Status"];

			// return $orm->save($instance, "Posts", "PostId", 
            // array(
			// 	"PostId"=>"PostId",
            //     "UserId"=>"UserId",
            //     "CategoryId"=>"CategoryId",
            //     "Title"=>"Title",
            //     "Description"=>"Description",
			// 	"CreationDate"=>"CreationDate",
			// 	"UpdatedDate"=>"UpdatedDate",
            //     "Status"=>"Status"
			// ));
			//Guarda
		} else {

			// if(isset($instance->imagen) ){
			// 	$datab = $instance->Img;
			// 	list($type, $datab) = explode(';', $datab);
			// 	list(, $datab)      = explode(',', $datab);
			// 	//DECODIFICA 
			// 	$instance->Img = base64_decode($datab);
			// }

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

			// foreach($data["Files"] as $item){
			// 	$item["PostId"] = $PostId;
			// 	$item->File = "data:image/png;base64," . base64_encode($item->File);
			// 	$fileController->{"Save"}($item);
			// }

			return $PostId;
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Posts WHERE PostId = ?");
		$sentencia->bindParam(1, $data["PostId"]);
		$sentencia->execute();
		return "OK";
	}
}
?>
