<?php
App::uses('Controller', 'Controller');
class ParticipatesController extends AppController {
    public $uses = array('Event', 'Participate', 'User');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function update() {
            $this->autoRender = false;
            $this->layout = 'ajax';
            $this->Participate->save($this->request->data);
    }
    


}
