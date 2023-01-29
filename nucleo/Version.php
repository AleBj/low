<?php



class Version

{

	private $_server;

	private $_requerida;

	

	public function __construct($_version)

	{

		$this->_server = explode('.', phpversion());

		$this->_requerida = explode('.',$_version);

		

		if(count($this->_server)){

			

			if(count($this->_server) >= 2 && count($this->_requerida) >= 2){

			

				if($this->_server[0] < $this->_requerida[0]){

					die('Su version actual de PHP es: ' . phpversion() . '. El sistema requiere una version ' . $_version . ' o superior para su correcto funcionamiento.');

				}

				

				/*if($this->_server[1] < $this->_requerida[1]){

					die('Su version actual de PHP es: ' . phpversion() . '. El sistema requiere una version ' . $_version . ' o superior para su correcto funcionamiento.');

				}*/

	

			}else{

				if($this->_server[0] < $this->_requerida[0]){

					die('Su version actual de PHP es: ' . phpversion() . '. El sistema requiere una version ' . $_version . ' o superior para su correcto funcionamiento.');

				}

			}

			

		}else{

			if(phpversion() < $this->_requerida[0]){

				die('Su version actual de PHP es: ' . phpversion() . '. El sistema requiere una version ' . $_version . ' o superior para su correcto funcionamiento.');

			}

		}

	}

}