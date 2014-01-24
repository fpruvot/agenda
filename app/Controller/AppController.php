<?php

App::uses('Controller', 'Controller');
App::import('Vendor', 'Google_Client', array('file' => 'google-api-php-client'.DS.'src'.DS.'Google_Client.php'));
App::import('Vendor', 'Google_PlusService', array('file' => 'google-api-php-client'.DS.'src'.DS.'contrib'.DS.'Google_PlusService.php'));
App::import('Vendor', 'Google_Oauth2Service', array('file' => 'google-api-php-client'.DS.'src'.DS.'contrib'.DS.'Google_Oauth2Service.php'));
App::import('Utility', 'Xml');

App::import('Vendor','tcpdf/tcpdf'); 

 class AppController extends Controller {
    public $helpers = array('Facebook.Facebook', 'Html', 'PhpExcel');   
    public $uses = array('User', 'Participate');
    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'Users',
                'action' => 'login'
            ),
            'authError' => '',
            'loginRedirect' => array(
                'controller' => 'Events',
                'action' => 'index'
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'User',
                    'fields' => array(
                        'username' => 'email',
                        'password' => 'password'
                    )
                )
            )
        ),
        'Facebook.Connect' => array('model' => 'User'),
        'OAuthConsumer'
    );
    
    public function beforeFilter() {
        if ($this->Session->read('Auth.redirect') == $this->webroot && $this->Session->read('Message.auth.message') == $this->Auth->authError) {
            $this->Session->delete('Message.auth');
        }

        $client = new Google_Client();
         if (!$this->Auth->loggedIn()){
            $plus = new Google_PlusService($client);
            $oauth2 = new Google_Oauth2Service($client);

            if (isset($_GET['code'])) {
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
            }

            if ($client->getAccessToken()) {
                $user = $oauth2->userinfo->get();
                $this->Session->write('Google.picture', $user['picture']);
                $theUser = $this->User->find('first', array('conditions' => array('User.email' => $user['email'])));
                $CountUser = $this->User->find('count', array('conditions' => array('User.email' => $user['email'])));
                $data = array('email' => $user['email'], 'name' => $user['name'], 'google_id' => $user['id']);
                if ($CountUser == 0){
                    $this->User->create();
                    $this->User->save($data);
                }else{
                    if (empty($theUser['User']['google_id'])){
                        $data = array('id'=> $theUser['User']['id'], 'email' => $user['email'], 'name' => $user['name'], 'google_id' => $user['id']);
                        $this->User->save($data);
                    }
                        
                }       
                $this->Auth->login($theUser['User']);
                $this->Session->write('TypeAuth', 'Google');
                $this->Session->write('Google.friends', $oauth2->userinfo->get_friend($client));
            }
        }   
        //var_dump($this->Session->read('Google.friends'));
        $authUrl = $client->createAuthUrl();
        $this->set('HrefGoogle', $authUrl);

        $button= '';
        $img = '';
        switch ($this->Session->read("TypeAuth")) {
            case 'Google':
                $img = '<b><img src="'.$this->Session->read('Google.picture').'" style="margin-top: -20px; margin-bottom: -20px;" height="35" width="35"></b>';
                $button = '<li class="divider"></li><li style="cursor:pointer;"><a href="/Agenda/Users/logout" id=""><i class="icon-off"></i>&nbsp&nbspDéconnexion</a></li>';
                break;
            case 'Facebook':
                $img = '<b><img src="'.$this->Session->read('Facebook.picture').'" style="margin-top: -20px; margin-bottom: -20px;" height="35" width="35"></b>';
                $button = '<li class="dropdown-submenu pull-left"><a tabindex="-1"><i class="icon-facebook"></i>&nbsp&nbspFacebook</a><ul class="dropdown-menu"><li><a name="show" id="button_birthday" tabindex="-1" href="#" onclick="javascript:Facebook_Friends();return false;">Afficher les anniversaires</a></li></ul></li><li class="divider"></li><li style="cursor:pointer;"><a href="#" onclick="logout(&quot;/Agenda/users/logout&quot;);" id=""><i class="icon-off"></i>&nbsp&nbspDéconnexion</a></li>';
                break;
            case 'AgendaWeb':
                $button = '<li class="divider"></li><li style="cursor:pointer;"><a href="/Agenda/Users/logout" id=""><i class="icon-off"></i>&nbsp&nbspDéconnexion</a></li>';
                break;
        }

        $chaineMenu = '';
        $notifications = $this->Participate->find('all',  array('conditions' => array('Participate.id_users' => $this->Auth->user('id'), 'Participate.notification' => false)));
        
        $divider = '';

        foreach ($notifications as $notification){
            
            $Author = $this->User->find('first', array('conditions' => array('id' => $notification['Event']['author'])));
            $chaineMenu = $chaineMenu.$divider."<li id='".$notification['Event']['id']."' style='cursor:pointer;' name='li_notification'>".
                            "<a onclick='notification_click(".$notification['Event']['id'].", ".$notification['Participate']['id'].");'><span style='font-weight : bold;'>".$Author['User']['name']."</span>&nbsp".
                            "vous a invité à participer à un évènement.<br>
                            </a>".
                        "</li>";
            
            $divider = "<li class='divider'></li>";
        }
        
        $this->set('TheUser', $this->User->read(false, $this->Auth->user('id')));
        $this->set('TheButton', $button);
        $this->set('nb_notifications', count($notifications));
        $this->set('notifications', $chaineMenu);
        $this->set('img', $img);
        $this->Auth->authorize = 'Controller';
        
        //$friends = $this->Facebook->api('me/friends');

        
    }
    
    public function isAuthorized($user) {
        // Admin peut accéder à toute action
//        if (isset($user['role']) && $user['role'] === 'admin') {
//            return true;
//        }
//
//        // Refus par défaut
//        return false;
        return true;
    }
    

    
    //    public function isAuthorized($user) {
//           var_dump($user);
//        // Tous les users inscrits peuvent ajouter les posts
//        if ($this->action === 'add') {
//            return true;
//        }
//
//        // Le propriétaire du post peut l'éditer et le supprimer
//        if (in_array($this->action, array('edit', 'delete'))) {
//            $postId = $this->request->params['pass'][0];
//            if ($this->Post->isOwnedBy($postId, $user['id'])) {
//                return true;
//            }
//        }
//        
//        return parent::isAuthorized($user);
//    }

}
