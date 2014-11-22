<?php

// app/Controller/UsersController.php

class ContactsController extends AppController {

	public $helpers = array('Form', 'Html', 'Paginator','Js');

	public $components = array('RequestHandler');

	public function index(){

		$this->layout = 'back';
		//Get all contact
		$userId = AuthComponent::user('id');
        $this->loadModel("Contact");
        $dataContact = $this->Contact->find('all', array(
                                'fields' => array('Contact.phone','Contact.name','Contact.id'),
                                'conditions' => array('Contact.user_id' => $userId )
        ));

        $nameUser=AuthComponent::user('username');
        
        $this->set('name',$nameUser);
        $this->set("contacts",$dataContact);        
        $this->set("userID",$userId);        
	}

	public function crud(){

		//post example num=3921106550&nom=Triny&ide=22
		$userId = AuthComponent::user('id');
		$role = AuthComponent::user('role');
		$msg = '';

		if($role == "admin"){

			$numContact = 20;
		}else if($role == "user"){

			$numContact = 3;
		}

		if($_POST['act']=='u'){

			$this->Contact->id = $_POST['ide'];
			$data = array( 'name' => $_POST['nom'],'phone'=>$_POST['num']);
			$this->Contact->save($data);	
		} elseif($_POST['act']=='d'){

			$this->Contact->delete(array('Contact.id'=>$_POST['ide']));
		}elseif ($_POST['act'] == 'a') {

			$totalContact = $this->Contact->find('count', array('conditions'=>array('Contact.user_id' => $userId)));
			if($totalContact > $numContact) {

				$msg = "Ya no puedes agregar contactos";
			}else{

				$data = array( 'name' => $_POST['nom'],'phone'=>$_POST['num'],'user_id'=>$userId);
				$this->Contact->save($data);
				$msg = 'Se ha agregado el contacto :'.$_POST['nom'];
			# code...
			}


		}

		$this->loadModel("Contact");
        $dataContact = $this->Contact->find('all', array(
                                'fields' => array('Contact.phone','Contact.name','Contact.id'),
                                'conditions' => array('Contact.user_id' => $userId )
        ));
        $this->set("contacts",$dataContact);
        $this->set("msg",$msg);

	}

	public function savecontact() {

		$this->render(false);

		$this->loadModel("Contact");

		$usID = AuthComponent::user('id');
		$role = AuthComponent::user('role');

		if($role == "admin"){

			$numContact = 20;
		}else if($role == "user"){

			$numContact = 3;
		}


		if ($this->request->is('ajax')) {

			$totalContact = $this->Contact->find('count', array('conditions'=>array('Contact.user_id' => $usID)));

			if($totalContact > $numContact) {

				$this->Session->setFlash(__('Ya no puedes agregar contactos, compra un paquete ;) !!'));

				echo "<h2>Ya no puedes agregar contactos, compra un paquete ;) !!<h2>";

		        echo '<script> $(".newContact").css("display","none"); 

		        		$("#ContactName").val("");

		        		$("#ContactPhone").val("");



		        		function delete_msg(){

		        			$("#containerRequest").text("");

		        		}



		        		setInterval(delete_msg, 7000);

		        		window.location.href=window.location.href;

		        	</script>';

		        exit();

			}



			$dataSaved = $this->Contact->save($this->data);

			/*debug($this->data);

			*/

			if($dataSaved){



				$this->Session->setFlash(__('El contacto ha sido guardado'));

				echo "Guardado correctamente!!";

		        echo '<script> $(".newContact").css("display","none"); 

		        		$("#ContactName").val("");

		        		$("#ContactPhone").val("");



		        		function delete_msg(){

		        			$("#containerRequest").text("");

		        		}



		        		setInterval(delete_msg, 7000);

		        		window.location.href=window.location.href;

		        	</script>';

			}else{

				$this->Session->setFlash(__('Hubo un error intenta de nuevo'));

				echo "Hubo un error intenta de nuevo!!";

		        echo '<script> $(".newContact").css("display","none"); 

		        		$("#ContactName").val("");

		        		$("#ContactPhone").val("");



		        		function delete_msg(){

		        			$("#containerRequest").text("");

		        		}



		        		setInterval(delete_msg, 7000);

		        		window.location.href=window.location.href;

		        	</script>';



			}



		

		//$this->render('office', 'ajax');

		}

	}



}