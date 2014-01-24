<?php
App::uses('Controller', 'Controller');

class UsersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
    
    public function beforeFilter() {  
        parent::beforeFilter();
        $this->Auth->allow('add', 'login', 'LoginGoogle', 'GoogleCallback');
    }
        
    function beforeFacebookLogin($user){
        //die(var_dump($user));
        //Logic to happen before a facebook login
    }
    
    function afterFacebookLogin(){
        //Logic to happen after successful facebook login.
        $this->redirect('/');
    }
    
    public function isAuthorized($user) {
        // Tous les users inscrits peuvent ajouter les posts
        if ($this->action === 'view') {
            $UserId = $this->request->params['pass'][0];
            if ($UserId == $user['id'])  
                return true;
            else
                return false;
        }
        return parent::isAuthorized($user);
    }
        
    public function GoogleCallback(){
        $this->redirect($this->Auth->redirect());        
    }
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {               
                $this->Session->setFlash(__('Nom d\'user ou mot de passe invalide, réessayer'));
            }
        }
    }

    function logout(){
        session_destroy();
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('User invalide'));
        }
        $this->set('user', $this->User->read(null, $this->Auth->User('id')));
    }

    public function add() {
        
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('L\'user a été sauvegardé'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('L\'user n\'a pas été sauvegardé. Merci de réessayer.'));
            }
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('User Invalide'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('L\'user a été sauvegardé'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('L\'user n\'a pas été sauvegardé. Merci de réessayer.'));
            }
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
            throw new NotFoundException(__('User invalide'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User supprimé'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('L\'user n\'a pas été supprimé'));
        $this->redirect(array('action' => 'index'));
    }
    
    public function update(){        
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->User->save($this->request->data); 
    }
    
    public function GetContactGoogle(){
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->User->save($this->request->data); 
        header('Content-Type: application/json');
        echo json_encode($this->Session->read('Google.friends'));
    }
   
}
?>
