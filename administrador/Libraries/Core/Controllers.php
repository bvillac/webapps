<?php 
	
	class Controllers{
		public function __construct(){
			$this->views = new Views();
			$this->loadModel();
		}

		public function loadModel(){
			//HomeModel.php
			$model = get_class($this)."Model";
			$routClass = "Models/".$model.".php";
			//putMessageLogFile($routClass);
			if(file_exists($routClass)){
				require_once($routClass);
				$this->model = new $model();
			}
		}
	}

 ?>