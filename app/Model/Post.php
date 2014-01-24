<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AppModel', 'Model');

class Post extends AppModel {
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty'
        ),
        'body' => array(
            'rule' => 'notEmpty'
        )
    );

    public function isOwnedBy($post, $user) {
        if ($this->field('id', array('id' => $post, 'user_id' => $user)) <> false)
            return true;
        else
           return false;
    }
    

}
?>
