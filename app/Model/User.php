<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AppModel', 'Model');

class User extends AppModel {

    public $name = 'User';
    public $validate = array(
        'email' => array(
                'email' => 'email',
                'rule'    => 'isUnique',
                'message' => 'Votre adresse email est déja attribuée à un compte.'
        )
    );
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['mot_de_passe'])) {
            $this->data[$this->alias]['mot_de_passe'] = AuthComponent::password($this->data[$this->alias]['mot_de_passe']);
        }
        return true;
    }
    
    
}
?>
