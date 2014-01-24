<?php
App::uses('Controller', 'Controller');

class PostsController extends AppController {
    
    public $helpers = array('Html', 'Form');
    public $components = array('Session');

    public function beforeFilter() { 
        $this->Auth->authorize = 'Controller';
    }
    
    public function isAuthorized($user) {
        // Tous les users inscrits peuvent ajouter les posts
        if ($this->action === 'add') {
            return true;
        }
        // Le propriétaire du post peut l'éditer et le supprimer
        if (in_array($this->action, array('edit', 'delete'))) {
            $postId = $this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id']))  
                return true;
            else
                return false;
        }

        return parent::isAuthorized($user);
    }

    public function index() {
        $this->set('posts', $this->Post->find('all'));
    }

    public function view($id) {
        $this->Post->id = $id;
        $this->set('post', $this->Post->read());
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->request->data['Post']['user_id'] = $this->Auth->user('id'); //Ligne ajoutée
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash('Votre post a été sauvegardé.');
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    public function edit($id = null) {
        $this->Post->id = $id;
        if ($this->request->is('get')) {
            $this->request->data = $this->Post->read();
        } else {
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash('Votre post a été mis à jour.');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Impossible de mettre à jour votre post.');
            }
        }
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if ($this->Post->delete($id)) {
            $this->Session->setFlash('Le Post avec l\'id ' . $id . ' a été supprimé.');
            $this->redirect(array('action' => 'index'));
        }
    }
}

?>
