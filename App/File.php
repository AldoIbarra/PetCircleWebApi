<?php
namespace App;

class File {

	private $db;

	function __construct($db){
		$this->db = $db;
	}

	function Files($id){
		$orm = new \App\Core\Model($this->db);
		$filtro = array();
		if($id > 0){
			$filtro = array("FileId"=>$id);
		}
		$list = $orm->select($filtro, "Files", "mFile", 
			array(
                "FileId"=>"FileId",
				"PostId"=>"PostId",
                "File"=>"File"
			), "", "", "");
        $items = iterator_to_array ($list);
        /*se convierte en base64 el contenido antes de retornar el objeto*/
        foreach($items as $item){
            $item->File = "data:image/png;base64," . base64_encode($item->File);
        }
        return  $items;
	}

	function PostFiles($id){
		$orm = new \App\Core\Model($this->db);
		$filtro = array();
		if($id > 0){
			$filtro = array("PostId"=>$id);
		}
		$list = $orm->select($filtro, "Files", "mFile", 
			array(
                "FileId"=>"FileId",
				"PostId"=>"PostId",
                "File"=>"File"
			), "", "", "");
        $items = iterator_to_array ($list);
        /*se convierte en base64 el contenido antes de retornar el objeto*/
        foreach($items as $item){
            $item->File = "data:image/png;base64," . base64_encode($item->File);
        }
        return $items;
	}

	function Save($data){
		$orm = new \App\Core\Model($this->db);
		//Edita
		if($data["FileId"] > 0){
			$instances = $this->Files($data["FileId"]);
			$instance = $instances[0];
			$instance->PostId = $data["PostId"];
			$instance->File = $data["File"];

			if(isset($instance->File) ){
				$datab = $instance->File;
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$instance->File = base64_decode($datab);
			}

            return $orm->save($instance, "Files", "FileId", 
				array(
                    "FileId"=>"FileId",
                    "PostId"=>"PostId",
                    "File"=>"File"));

		} else {

			if(isset($data["File"]) ){
				$datab = $data["File"];
				list($type, $datab) = explode(';', $datab);
				list(, $datab)      = explode(',', $datab);
				//DECODIFICA 
				$data["File"] = base64_decode($datab);
			}

			return $orm->save($data, "Files", "FileId", 
                array(
                    "FileId"=>"FileId",
                    "PostId"=>"PostId",
                    "File"=>"File"
            ));
		}
	}

	function Delete($data){
		$sentencia = $this->db->prepare("DELETE FROM Files WHERE FileId = ?");
		$sentencia->bindParam(1, $data["FileId"]);
		$sentencia->execute();
		return "OK";
	}
}
?>
