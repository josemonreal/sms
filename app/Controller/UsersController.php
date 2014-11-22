<?php
// app/Controller/UsersController.php
class UsersController extends AppController {

    public $helpers = array('Form', 'Html', 'Paginator','Js');
    /*public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add','index','login');
    }*/

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        //error_reporting(2);
        if ($this->request->is('post')) {
            
                App::import('Vendor', 'recaptchalib', array('file' => 'recaptchalib.php'));        
                $privatekey = "6Leik-cSAAAAADXvEhn7W7RQ9YeofI9W4JRS8-Lw";
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                    //if ($resp->is_valid) { 
                    if (true) { 
                       
                        $existsUser = $this->User->find('first',array('conditions'=>array('User.username'=>$this->request->data['User']['username']))); 
                        $existsNum = $this->User->find('first',array('conditions'=>array('User.phone'=>$this->request->data['User']['phone']))); 
                       if ($existsUser || $existsNum ){
                       // if (false){
                            $this->Session->setFlash(__('Usuario o numero ya esta registrado'));
                            return $this->redirect('/users/add');  
                        } else {
                            $this->User->create();
                            $phoneNum=$this->request->data['User']['phone'];
                            $firsNum=substr($phoneNum, 0, 3);
                            if(strlen($phoneNum)==10 && is_numeric($phoneNum)){
                                if ($this->User->save($this->request->data)) {
                                    $this->loadModel('Code_validate');
                
                                    $codeValid=$this->RandomString();
                                    $lastID=$this->User->getLastInsertID();
                                    $data = array('code' => $codeValid,'id_user'=>$lastID);
                                    // This will update Recipe with id 10
                                    if($this->Code_validate->save($data)) {
                                        $userN=$this->request->data['User']['username']; $passN=$this->request->data['User']['password'];
                                        $message="Tu codigo es: $codeValid ve a sms.syspaweb.com y valida,tu usuario:".$userN.",tu contraseña:".$passN;
                                        
                                        
                                        if($this->send_sms($this->request->data['User']['phone'],$message)) {
                                            $this->Session->setFlash(__('Te registraste correctamente,logueate y valida tu codigo para poder mandar msj gratis'));
                                            return $this->redirect(array('action' => 'office'));
                                        }else {
                                            $this->Session->setFlash(__('No se pudo enviar el sms, envianos un mail a contacto@syspaweb.com'));
                                            return $this->redirect(array('action' => 'office'));
                                        }
                                    }   

                                    $this->Session->setFlash(__('Te has registrado correctamente, Ahora puedes loguearte'));
                                    return $this->redirect(array('action' => 'office'));
                                }else {
                                    $this->Session->setFlash(__('Usuario o numero ya esta registrado'));
                                    debug($this->User->validationErrors);
//show last sql query
debug($this->User->getDataSource()->getLog(false, false));
                                    //return $this->redirect('/users/add');  
                                }
                            }else {
                                $this->Session->setFlash(__('El numero es incorrecto'));
                                 return $this->redirect('/users/add'); 
                            }
                        }
                    } else { 
                        $this->Session->setFlash(__('Existe error en el captcha'));
                        return $this->redirect(array('action' => 'add'));
                    }

            
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

public function login() {
    configure::write("debug",0);

    App::uses('recaptchalib', 'Vendor');
    require("Component/face/src/facebook.php");
    //require('vendors/amazon/s3_config.php');
    $this->loadModel("Log");
    $idUser=AuthComponent::user('id');

    //for login with face
	$facebook = new Facebook(array(
	  'appId'  => '828828913798539',
	  'secret' => 'ffa358af007f008bb948ce834a934b0c',
	));

	// Get User ID
	$user = $facebook->getUser();

	// We may or may not have this data based on whether the user is logged in.
	//
	// If we have a $user id here, it means we know the user is logged into
	// Facebook, but we don't know if the access token is valid. An access
	// token is invalid if the user logged out of Facebook.

	if ($user) {
	  try {
	    // Proceed knowing you have a logged in user who's authenticated.
	    $userProfile = $facebook->api('/me');
        //debug($user_profile);
	  } catch (FacebookApiException $e) {
	    error_log($e);
	    $user = null;
	  }
	}

    if(isset($userProfile['id']) && !empty($userProfile['id'])){

        $data = array('username' => $userProfile['name'],'idFace' => $userProfile['id'],'status' => 1 );
        if($dataUser = $this->User->findByIdFace($userProfile['id'])){

            if($this->Auth->login($dataUser['id'])){

                $statusUser=AuthComponent::user('status');
                $idUser=AuthComponent::user('id');
                if($statusUser >= 0){

                    // debug($this->request);exit();

                    $data=array("user_id"=>"$idUser","description"=>"login with facebook");

                    $this->Log->create();
                    $this->Log->save($data);
                    $this->Session->write('User.id',$userId);
                     return $this->redirect('/users/office');
                }
            }

        }else{

            if($this->User->save($data)){
                $userId = $this->User->getLastInsertID();
                
                if($this->Auth->login($userId)){

                    $statusUser=AuthComponent::user('status');
                    $idUser=AuthComponent::user('id');
                    if($statusUser >= 0){

                        // debug($this->request);exit();

                        $data=array("user_id"=>"$idUser","description"=>"login with facebook");

                        $this->Log->create();
                        $this->Log->save($data);
                        $this->Session->write('User.id',$userId);
                         return $this->redirect('/users/office');
                    }
                }

            }
        }

    }

	// Login or logout url will be needed depending on current user state.
	if ($user) {
	  $logoutUrl = $facebook->getLogoutUrl();
	} else {
	  $statusUrl = $facebook->getLoginStatusUrl();
	  $loginUrl = $facebook->getLoginUrl();
	}

	// This call will always work since we are fetching public data.
	$naitik = $facebook->api('/naitik');
	//login with face ***+

	$this->set('user',$user);
	$this->set('userProfile',$userProfile);
	$this->set('logoutUrl',$logoutUrl);
	$this->set('statusUrl',$statusUrl);
	$this->set('loginUrl',$loginUrl);
	$this->set('naitik',$naitik);

    if($idUser) {
         return $this->redirect($this->Auth->redirect());
    }
    if ($this->request->is('post')) {
        if ($this->Auth->login()) {
            $statusUser=AuthComponent::user('status');
            $idUser=AuthComponent::user('id');
            if($statusUser >= 0){

               // debug($this->request);exit();

                $data=array("user_id"=>"$idUser","description"=>"login");

                $this->Log->create();
                $this->Log->save($data);
                $this->Session->write('User.id',$idUser);
                return $this->redirect($this->Auth->redirect());
            }else {
                 $this->Session->setFlash(__('El numero telefonico no a sido validado'));
            }
        } else {    
            $this->Session->setFlash(__('Usuario o password incorrecto'));
        }
    }
}

/*
Array
(
    [id] => 100004290155569
    [name] => Joma Monreal
    [first_name] => Joma
    [last_name] => Monreal
    [link] => https://www.facebook.com/joma.monreal
    [gender] => male
    [timezone] => -5
    [locale] => en_US
    [verified] => 1
    [updated_time] => 2014-03-23T02:39:54+0000
    [username] => joma.monreal
)
*/
public function addByFace(){

}

public function loginSocial(){

    configure::write("debug",2);
    $this->loadModel("User");
    $this->loadModel("Log");
    //debug($_SESSION);

    $this->autoRender=false;
    require("Component/face/src/facebook.php");
    //require('vendors/amazon/s3_config.php');

    //for login with face
    $facebook = new Facebook(array(
      'appId'  => '828828913798539',
      'secret' => 'ffa358af007f008bb948ce834a934b0c',
    ));

    // Get User ID
    $user = $facebook->getUser();

    // We may or may not have this data based on whether the user is logged in.
    //
    // If we have a $user id here, it means we know the user is logged into
    // Facebook, but we don't know if the access token is valid. An access
    // token is invalid if the user logged out of Facebook.
    //debug($user);
    if ($user) {
      try {
        // Proceed knowing you have a logged in user who's authenticated.
        $userProfile = $facebook->api('/me');
        //debug($userProfile);
      } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
      }
    }

    if(isset($userProfile['id']) && !empty($userProfile['id'])){



        $data = array('username' => $userProfile['first_name'],'idFace' => $userProfile['id'],'status' => 1,'name'=>$userProfile['first_name'],'email'=>$userProfile['email'] );

        
        $existsUser = $this->User->find('first',array(
            'fields' => array('User.idFace','User.id','User.status'),
            'conditions'=>array('User.idFace'=>$userProfile['id'])   
        ));
        
        if(count($existsUser)>=1){

             if($this->Auth->login($existsUser['User']['id'])){
                    
                $UserID = $existsUser['User']['id'];
                //echo $statusUser=AuthComponent::user();
                $statusUser = $existsUser['User']['status'];

                $idUser=AuthComponent::user();
                if($statusUser >= 0){

                    // debug($this->request);exit();

                    $data=array("user_id"=>"$UserID","description"=>"login with facebook");

                    $this->Log->create();
                    $this->Log->save($data);
                    $this->Session->write('User.id',$UserID);
                    exit();
                    return $this->redirect("/users/office");
                }
            }

        }

        if($this->User->save($data)){
            $userId = $this->User->getLastInsertID();
            
            if($this->Auth->login($userId)){

                $statusUser=AuthComponent::user('status');
                $idUser=AuthComponent::user('id');
                if($statusUser >= 0){

                    // debug($this->request);exit();

                    $data=array("user_id"=>"$idUser","description"=>"login with facebook");

                    $this->Log->create();
                    $this->Log->save($data);
                    $this->Session->write('User.id',$idUser);
                    return $this->redirect($this->Auth->redirect());
                }
            }

        }

    }
}

public function logout() {
    session_destroy();
    return $this->redirect($this->Auth->logout());
}

    public function office() {
        configure::write("debug",0);
        //debug(AuthComponent::user());
        //debug($_SESSION);
        $this->layout = 'back';
        $usID = AuthComponent::user('id');
        $_SESSION['id']=$usID;
        $this->set("userID",$usID);
        $numStatus = $this->Session->read('statusN');
        if(isset($numStatus) && !empty($numStatus)){

            $statusUser = $numStatus;

        }else{

            $statusUser=AuthComponent::user('status');
        }
        $nameUser=AuthComponent::user('username');
        $this->set('status',$statusUser);
        $this->set('name',$nameUser);
        $this->Session->write('User.try', 0);
        $contacs = $this->get_contacts();
        //debug($contacs);
        $this->set(compact('contacs'));

        
            
    }

    public function send()
    {

        $this->autoRender = false;
        if(AuthComponent::user('id')=="")
        {
            echo "<script>alert('No logueado ');</script>";
            echo "<script>document.location.href='/users/';</script>";
           
            //exit();
        }

        //**App::uses('RoutoTelecomSMS', 'Vendor');

        $conn=mysql_connect("www.syspaweb.com","syspaweb_jose","joma1987");
        $seldb=mysql_select_db("syspaweb_sms",$conn);
        $userId=AuthComponent::user('id');
        //debug($_POST);
        $nume=$_POST['num'];
        $message=$_POST['msj'];

        $num=mysql_query("SELECT total_sms FROM messages WHERE user_id=$userId");
        $num_array=mysql_fetch_array($num);
        $num_sms=$num_array[0];
        //echo $num_sms."*******";

        if($num_sms > 0) {

            $new_num=$num_sms-1;

            $qry=mysql_query("UPDATE  messages SET total_sms=$new_num WHERE user_id=$userId");
            //=mysql_fetch_array($qry);

            if($this->send_sms($nume,$message)) {
                $this->Session->setFlash(__('Mensaje enviado te quedan, '.$new_num.' msj, por el dia de hoy '));
                return $this->redirect(array('action' => 'office'));
            }else {
                $this->Session->setFlash(__('No se envio el msj'));
                return $this->redirect(array('action' => 'office'));
            }

        }else {
            $this->Session->setFlash(__('Ya no puedes enviar mensajes por el dia de hoy!!!'));
            return $this->redirect(array('action' => 'office'));
        }
        return $this->redirect(array('action' => 'index'));
         

        //echo "<script>document.location.href='/users/office/no_msj:".$new_num."';</script>";


                /*$conn=mysql_connect("www.syspaweb.com","syspaweb_jose","joma1987");
        $seldb=mysql_select_db("syspaweb_sms",$conn);
        $user_Id=1;


        $num=mysql_query("SELECT total_sms FROM messages WHERE user_id=$userId");
        $num_array=mysql_fetch_array($num);
        $num_sms=$num_array[0];
        echo $num_sms."*******";

        if($num_sms > 0) {

            $new_num=$num_sms-1;

            $qry=mysql_query("UPDATE  messages SET total_sms=$new_num WHERE user_id=$userId");
            //=mysql_fetch_array($qry);
        }
        */

    }

    public function RandomString($length=5,$uc=FALSE,$n=TRUE,$sc=FALSE)
    {
        $source = 'abcdefghijklmnpqrstuvwxyz';
        if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($n==1) $source .= '123456789';
        if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
        if($length>0){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }
     
        }
        return $rstr;
    }
    public function val_code() {
        $numTry = $this->Session->read('numTry');
        if($numTry==0 || $numTry=="") {
            $this->Session->write('numTry',1);
            $numTry=1;
        }else {
            $numTry=$numTry+1;
            $this->Session->write('numTry',$numTry);
            if($numTry >= 4){ 
                $this->Session->setFlash(__('varios intentos fallidos, problemas?? manda email a: webmaster@syspaweb.com'));
                $this->Session->delete('numTry');
                $this->logout();
            }
        }

        $this->autoRender=false;
        $this->loadModel("CodeValidate");
        $this->loadModel("User");
        $this->loadModel("Message");
        $idUS=AuthComponent::user('id');

        $valCode=$this->CodeValidate->findById_userAndCode($idUS, $this->request->data['code']);
        if($valCode) {
            $this->Session->setFlash(__('Validado Correctamente!!'));
            $data = array('user_id' => $idUS,'total_sms'=> 3 );
            $this->Message->save($data);
            $this->User->updateAll(
            array('User.status' => 1),
            array('User.id ' => $idUS)
            );
            //$this->logout();
            $this->Session->write('statusN','1');
            return $this->redirect('/users/office');   
            //return $this->redirect(array('action' => 'login '));    

        }else {
            //echo "false";
            $this->Session->setFlash(__('El codigo es erroneo, intento num -> '.$numTry));
            return $this->redirect('/users/office'); 
            //return $this->redirect(array('action' => 'office'));
        } 
        
/*
        debug($this->request->data['code']);
        $this->loadModel
        $existsNum = $this->User->find('first',array('conditions'=>array('User.phone'=>$this->request->data['User']['phone']))); 
       // 'code' => 'qweerr'
        /*
        $this->updateAll(
            array('Baker.approved' => true),
            array('Baker.created <=' => $value)
        );*/
    }

    public function send_sms($nume,$message) {
       
        App::uses('RoutoTelecomSMS', 'Vendor');

        $nume=(string)$nume;
        $nume="52".$nume;
        

        $message=substr($message, 0,140);
        $sms = new RoutoTelecomSMS;
        // setting login parameters
        $sms->SetUser("1183531");
        $sms->SetPass("z5hy5449n");
        $sms->SetOwnNum(5511945330); // optional
        $sms->SetType("SMS"); // optional
        // get values entered from FORM
        $sms->SetNumber($nume);
        $sms->SetMessage($message);
        // send SMS and print result
        if($smsresult=$sms->Send()) {  return true; }
       //if(true) {  return true; }
        else { 
            return false;
        }
        //echo "se envio";
        //echo debug($smsresult);
        

    }

    public function savecontact() {
        
 $this->render(false);
    /*
        $this->render(false);
        $this->loadModel("Contact");

        if ($this->request->is('ajax')) {
            
            $this->Contact->create();
            $dataSaved = $this->Contact->save($this->data);

            if($dataSaved){

                //$this->Session->setFlash(__('The data has been saved'));
            }

        
        //$this->render('office', 'ajax');
        }*/
    }

    public function get_contacts(){
        $userId = AuthComponent::user('id');
        $this->loadModel("Contact");
        $dataContact = $this->Contact->find('list', array(
                                'fields' => array('Contact.phone','Contact.name'),
                                'conditions' => array('Contact.user_id' => $userId )
         ));
        return $dataContact;

    }

    public function onlysend(){
        session_start();
        if(!isset($_SESSION['ip'])){
            $myip = $this->verIP();
            $_SESSION['ip']=$myip;
        }else{
            echo  "<script>alert('Tienes que registrarte para enviar mas mensajes o solo podras enviar 1 por dia');</script>";
            echo "<script>document.location.href='/users/add'</script>";
        }
        if($this->request->is('post')){
            $nume=$_POST['num'];
            $message=$_POST['msj']; 
            if($this->send_sms($nume,$message)) {
             echo  "<script>alert('Mensaje Enviado ');</script>";
            }else {

                echo  "<script>alert('Hubo un error intente de nuevo');</script>";
            }
        }


    }

    public function verIP(){ 

        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function currentMsj(){

        $this->autoRender = false;
        $usID = AuthComponent::user('id');
        $this->loadModel('Message');
        $totalSms = $this->Message->find('first',array(
            'conditions'=>array('Message.user_id'=>$usID),
            'fields'=>array('Message.total_sms')
        ));

        echo $totalSms['Message']['total_sms']; 
           
    }

    public function currentInvitate(){

        $this->autoRender = false;
        $usID = AuthComponent::user('id');
        $this->loadModel('User');
        $totalInv = $this->User->find('all',array(
            'conditions'=>array('User.status'  =>array(1,2),
                                'User.sponsor' => $usID
            ),
            'fields'=>'User.sponsor'
        ));

        echo count($totalInv); 
           
    }


}
?>