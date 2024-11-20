<?php

namespace App;
class Api{
	private $db;
	function __construct(){
			$this->db = new \PDO("mysql:host=localhost;port=3306;dbname=petcircle;charset=utf8mb4", "root" , "" );	
	}

	//ES LA FUNCION QUE SE EJECUTA CUANDO USAMOS EL METODO GET EN NUESTA LLAMADA AL API
	function read($name,$endpoint,$id){
		 $aux = "\\App\\" . $name;
		 $controller = new $aux($this->db);
		 return $controller->{$endpoint}($id);
	}

	//ES LA FUNCION QUE SE EJECUTA CUANDO USAMOS EL METODO POST EN NUESTRA LLAMADA API
	function write($name, $endpoint){
		 $aux = "\\App\\" . $name;
		 $controller = new $aux($this->db);
		 $aux_method = "" . $endpoint;

		 //$aux_method = "save_" . $endpoint;
		 $data = $this->getdataparamaters_json();
		
		 return $controller->{$aux_method}($data);
	}

 	/*
	* Content-Type application/json
	* Body= raw
	* JSON(application/json)
	*/

	//IMPORTANTE PARA QUE ESTO FUNCION ES USAR EL HEADER (application/json)
	function getdataparamaters_json()
	{
			// Lee el contenido del body de la solicitud
			$json_params = file_get_contents("php://input");
	
			// Asegúrate de que no esté vacío o nulo antes de intentar convertirlo
			if ($json_params !== false && !empty($json_params)) {
					// Aquí usamos mb_convert_encoding() solo si el contenido no está vacío
					$json_params = mb_convert_encoding($json_params, 'UTF-8', 'auto');
			}
	
			// Decodifica el JSON recibido
			$dd = json_decode($json_params, true);
	
			// Devuelve los parámetros decodificados
			return $dd;
	}
}
?>