<?php
// app/Controller/UsersController.php
class UsersController extends AppController {


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add','index');
    }

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

        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                //App::uses('recaptchalib', 'Vendor');
                App::import('recaptchalib', 'Vendor');
                //$privatekey = "6Leik-cSAAAAADXvEhn7W7RQ9YeofI9W4JRS8-Lw";
                $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
                    if (!$resp->is_valid) { 
                        $this->Session->setFlash(__('Te has registrado correctamente'));
                        return $this->redirect(array('action' => '/users/office'));
                    } else { 
                        $this->Session->setFlash(__('Existe error en el captcha'));
                        return $this->redirect(array('action' => '/users/login'));
                    }

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
    App::uses('recaptchalib', 'Vendor');
    if ($this->request->is('post')) {

        $privatekey = "6Leik-cSAAAAADXvEhn7W7RQ9YeofI9W4JRS8-Lw";
        $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) { 

            if ($this->Auth->login()) {
                $statusUser=AuthComponent::user('status');
                if($statusUser > 0){
                    $idUser=AuthComponent::user('id');
                    $this->Session->write('User.id',$idUser);
                    return $this->redirect($this->Auth->redirect());
                }else {
                     $this->Session->setFlash(__('El numero telefonico no a sido validado'));
                }
            } else {    
                $this->Session->setFlash(__('Usuario o password incorrecto'));
            }
        } else { 
            $this->Session->setFlash(__('Captcha es incorrecto'));
        }
  
    }
}

public function logout() {
    return $this->redirect($this->Auth->logout());
}

    public function office() {

        $usID = $this->Session->read('User.id');
        $_SESSION['id']=$usID;
        $this->set("userID",$usID);
            
    }

    public function send()
    {

        $this->autoRender = false;
        if($this->Session->read('User.id')=="")
        {
            echo "<script>alert('No logueado ');</script>";
            echo "<script>document.location.href='/users/';</script>";

            //exit();
        }

        App::uses('RoutoTelecomSMS', 'Vendor');

        $conn=mysql_connect("www.syspaweb.com","syspaweb_jose","joma1987");
$seldb=mysql_select_db("syspaweb_sms",$conn);
$userId=$this->Session->read('User.id');
debug($_POST);
$nume="52".$_POST['num'];

$num=mysql_query("SELECT total_sms FROM messages WHERE user_id=$userId");
$num_array=mysql_fetch_array($num);
$num_sms=$num_array[0];
//echo $num_sms."*******";

if($num_sms > 0) {

    $new_num=$num_sms-1;

    $qry=mysql_query("UPDATE  messages SET total_sms=$new_num WHERE user_id=$userId");
    //=mysql_fetch_array($qry);
$message=substr($_POST['msj'], 0,140);

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
$smsresult = $sms->Send();
//echo debug($smsresult);

 echo "<script>alert('mensajes restantes:'".$new_num.");</script>";
}else {
    echo "<script>alert('ya no puedes enviar msj');</script>";
}
echo "<script>document.location.href='/users/office/no_msj:".$new_num."';</script>";


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


}
?>