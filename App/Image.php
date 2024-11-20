<?php
namespace App;

class Image {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Images($id){
		$orm = new \App\Core\Model($this->db);
        $joins = array();
		$filtro = array();
		if($id > 0){
			$filtro = array("ImgId"=>$id);
		}
		$list = $orm->select($filtro, "Images", "mImage", 
			array(
                "ImgId"=>"ImgId",
				"PostId"=>"PostId",
                "Img"=>"Img"
			), "", "", "", $joins);
        $items = iterator_to_array ($list);
        /*se convierte en base64 el contenido antes de retornar el objeto*/
        foreach($items as $item){
            $item->Img = "data:image/png;base64," . base64_encode($item->Img);
        }
        return  $items;
	}

	function Save($data){
		$orm = new \App\Core\Model($this->db);
		//Edita
		if($data["ImgId"] > 0){
			$instances = $this->Images($data["ImgId"]);
			$instance = $instances[0];
			$instance->PostId = $data["PostId"];
			$instance->Img = $data["Img"];

			if(isset($instance->Img) ){
				$datab = $instance->Img;
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$instance->Img = base64_decode($datab);
			}

            return $orm->save($instance, "Images", "ImgId", 
				array(
                    "ImgId"=>"ImgId",
                    "PostId"=>"PostId",
                    "Img"=>"Img"));

		} else {

			if(isset($data["Img"]) ){
				$datab = $data["Img"];
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$data["Img"] = base64_decode($datab);
			}

			return $orm->save($data, "Images", "ImgId", 
                array(
                    "ImgId"=>"ImgId",
                    "PostId"=>"PostId",
                    "Img"=>"Img"
            ));
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Images WHERE ImgId = ?");
		$sentencia->bindParam(1, $data["ImgId"]);
		$sentencia->execute();
		return "OK";
	}
}
?>
