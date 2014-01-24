<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AppModel', 'Model');

class Participate extends AppModel {
    public $name = 'Participate';  
   
    public $belongsTo = array(
        'Event' => array(
            'className'    => 'Event',
            'foreignKey'   => 'id_events'
        ),
        'User' => array(
            'className'    => 'User',
            'foreignKey'   => 'id_users'
        )
    );
    
                
    public function beforeSave($options = array()) {
//        if ($this->data[$this->alias]['title']) {
//        }
        //die(debug($this->data));
        return true;
    }
}
    
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
