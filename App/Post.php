<?php
namespace App;

class Post {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Posts($id){
		$orm = new \App\Core\Model($this->db);
        $joins = array();
        if($id > 0){
            $joins = array(["Condition"=>"Inner", "Table"=>"Files", "PrimaryColum"=>"FileId", "ForeignColum"=>"PostId"],
            ["Condition"=>"Inner", "Table"=>"Images", "PrimaryColum"=>"ImgId", "ForeignColum"=>"PostId"]);
        }
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
		$orm = new \App\Core\Model($this->db);
		//Edita
		if($data["PostId"] > 0){
			$instances = $this->Posts($data["PostId"]);
			$instance = $instances[0];
			$instance->UserId = $data["UserId"];
			$instance->CategoryId = $data["CategoryId"];
			$instance->Title = $data["Title"];
			$instance->Descripion = $data["Description"];
			$instance->UpdatedDate = $data["UpdatedDate"];
			$instance->Status = $data["Status"];
			$instance->ImgId = $data["ImgId"];
			$instance->Img = $data["Img"];
			$instance->FileId = $data["FileId"];
			$instance->File = $data["File"];

			return $orm->save($instance, "Posts", "PostId", 
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
			//Guarda
		} else {

			// if(isset($instance->imagen) ){
			// 	$datab = $instance->Img;
			// 	list($type, $datab) = explode(';', $datab);
			// 	list(, $datab)      = explode(',', $datab);
			// 	//DECODIFICA 
			// 	$instance->Img = base64_decode($datab);
			// }

			return $orm->save($data, "Posts", "PostId", 
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
