<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AppModel', 'Model');

class Event extends AppModel {
    public $name = 'Event';  
//    public $validate = array(
//        'title' => array(
//            'required' => array(
//                'allowEmpty' => false,
//                'message' => 'Un titre d\'évenement est requis'
//            )
//        ),
//        'title' => array(
//            'required' => array(
//                'allowEmpty' => false,
//                'message' => 'Un titre d\'évenement est requis'
//            )
//        ),
//        'date_start' => array(
//            'required' => array(
//                'allowEmpty' => false,
//                'message' => 'Une date de début est requis'
//            )
//        ),
//        'date_end' => array(
//            'required' => array(
//                'allowEmpty' => false,
//                'message' => 'Une date de fin est requis'
//            )
//        )
//    ); 
    
    public function beforeSave($options = array()) {
        $this->data['Event']['date_start'] = date( "Y-m-d H:i:s", strtotime($this->data['Event']['date_start']." +1 month" ));
        $this->data['Event']['date_end'] = date( "Y-m-d H:i:s", strtotime($this->data['Event']['date_end']." +1 month" ) );;
        var_dump($this->data['Event']['date_end'] );
        return true;
    }
    
}
    
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
