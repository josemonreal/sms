<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Requiere nombre usuario'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Requiere password'
            )
        ), 
          //^[8]{1}[1-9]{2}[0-9]{5}$/
        // 'rule'    => '/^[392]{1}[0-9]{7}$/i',
        //'message' => 'El numero'     
        'phone' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Numero requerido'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'user')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        )
    );

    public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
}
}

?>