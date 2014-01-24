<?php
App::uses('Controller', 'Controller');
App::uses('File', 'Utility');
class EventsController extends AppController {
    public $uses = array('Event', 'Participate', 'User');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function sendmail($to, $subject = null, $message = null){
        $headers  = 'From: sender@gmail.com' . "\r\n" .
                    'Reply-To: sender@gmail.com' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        if(mail($to, $subject, $message, $headers))
            var_dump ("Email sent");
        else
            var_dump ("Email sending failed");
    }
    
    function index($id = null){
        //$this->sendmail('bi-bou33@hotmail.fr', '', '');
        $chaine = "";
        $this->set('user', $this->User->read(false, $this->Auth->user('id')));
        $listEvent = $this->Participate->find('all',  array('conditions' => array('Participate.id_users' => $this->Auth->user('id'))));
        foreach ($listEvent as $value){
            $listParticipate = $this->Participate->find('all',  array('conditions' => array('Event.id' => $value['Event']['id'], 'NOT' => array('User.id' => $this->Auth->user('id')))));
            $chaineParticipate = '';
            $virgule = '';
            foreach ($listParticipate as $TheUser){
                $chaineParticipate = $chaineParticipate.$virgule."'".$TheUser['User']['email']."'";
                $virgule = ',';
            }
            $value['Event']['date_start'] = date( "Y-m-d H:i:s", strtotime($value['Event']['date_start']." -1 month" ));
            $value['Event']['date_end'] = date( "Y-m-d H:i:s", strtotime($value['Event']['date_end']." -1 month" ));
            
            $date_start = new DateTime($value['Event']['date_start']);
            $date_end = new DateTime($value['Event']['date_end']);
            $chaine = $chaine."{
                        id:'".$value['Event']['id']."',
                        title: '".str_replace("'", "\'", $value['Event']['title'])."',
                        start: new Date(".$date_start->format('Y, m, d, H, i, s')."),
                        end: new Date(".$date_end->format('Y, m, d, H, i, s')."),
                        description: '".str_replace("'", "\'", $value['Event']['description'])."',   
                        address: '".str_replace("'", "\'", $value['Event']['address'])."',
                        allDay: '".$value['Event']['allday']."',
                        author: '".$value['Event']['author']."',
                        className: 'tache',
                        participates: new Array(".$chaineParticipate.")
                        },";
        }
        $this->set('ListEvents', substr($chaine, 0, -1));
    }
    
    public function add() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        try {
            //$this->sendmail('bi-bou33@hotmail.fr', 'test', 'test');
            $this->Event->create();
            $this->Participate->create();
            if ($this->Event->save($this->request->data))  {
                if ($this->Participate->save(array('Participate' => array('id_users'=> $this->request->data['Event']['author'], 'id_events'=> $this->Event->id, 'notification' => true))))  {
                    foreach ($this->request->data['Participate'] as $TheParticipate) {
                        if (!empty($TheParticipate)){
                            $user = $this->User->find('first',  array('conditions' => array('email' => $TheParticipate))); 
                            $this->User->id = $user['User']['id'];
                            if (empty($user)){
                                $data = array('email' => $TheParticipate, 'password' => $this->User->generateRandomString(8));
                                $this->User->create();
                                $this->User->save($data);
                            }
                            $this->Participate->create();
                            $this->Participate->save(array('Participate' => array('id_users'=> $this->User->id, 'id_events'=> $this->Event->id, 'notification' => false)));
                            var_dump($user['User']['email']);
                            if (($user['User']['email_notification']) && ($this->User->id != $this->Auth->user("id"))){
                                $this->sendmail($user['User']['email'], 'Invitation Agenda Web', 'Bonjour '.$this->User->email.", \r\n vous &ecirc;tes invit&eacut; &agrave; particiter &agrave; un &eacut;evenement par l'utilisateur : ".$this->Auth->user("email").". \r\n Cliquer sur ce <a href='".Router::url(array('controller' => 'Event', 'action' => 'index'))."'>lien</a> pour consulter l'&eacute;venement. \r\n Cordialement. \r\n \r\n Agenda Web \r\n www.agenda.com  ");                                
                            }
                        }
                    }
                    echo $this->Event->id;
                }else{
                    echo "Echec";
                } 
            } else {
                 echo "Echec";
            }
        } catch (Exception $e) {
            echo 'Exception reÃ§ue : ',  $e->getMessage(), "\n";
        }
    }
    
    public function resize() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Event->save($this->request->data);
    }
    
    public function update() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $participates_exist = array();
        $this->Event->save($this->request->data);
        $temp = $this->Participate->find('all', array('conditions' => array('id_events' => $this->Event->id), 'fields' => array('User.email')));
        foreach ($temp as $p){
            array_push($participates_exist, $p['User']['email']);
        } 

        $participates_update = $this->request->data['Participate'];
        $array_add_participate = array_diff($participates_update, $participates_exist);
        foreach ($array_add_participate as $TheParticipate) {
            $user = $this->User->find('first',  array('conditions' => array('email' => $TheParticipate))); 
            $this->User->id = $user['User']['id'];
            if (empty($user)){
                $data = array('email' => $TheParticipate, 'password' => $this->User->generateRandomString(8));
                $this->User->create();
                $this->User->save($data);
            }
            $this->Participate->create();
            $this->Participate->save(array('Participate' => array('id_users'=> $this->User->id, 'id_events'=> $this->Event->id, 'notification' => false)));
        }
        $array_add_participate = array_diff($participates_exist, $participates_update);
        foreach ($array_add_participate as $TheParticipate) {
            $user = $this->User->find('first',  array('conditions' => array('email' => $TheParticipate))); 
            $this->Participate->deleteAll(array('id_users' => $user['User']['id'], 'id_events' => $this->Event->id));
        }
        echo $this->Event->id;
    }
        
    public function viewPdf() 
    { 
        $this->layout = 'pdf'; //this will use the pdf.ctp layout 
        $this->render(); 
    } 
    
    public function extraction_ical(){
        
        $chaine =   "BEGIN:VCALENDAR\n".
                    "PRODID:-//Agenda Web//FR\n".
                    "VERSION:1.0\n".
                    "X-WR-CALNAME:".$this->Auth->user('email')."\n".
                    "X-WR-TIMEZONE:Europe/Paris\n";
        
        $listEvent = $this->Participate->find('all',  array('conditions' => array('Participate.id_users' => $this->Auth->user('id'))));
        foreach ($listEvent as $value){
            $listParticipate = $this->Participate->find('all',  array('conditions' => array('Event.id' => $value['Event']['id'], 'NOT' => array('User.id' => $this->Auth->user('id')))));
            $chaineParticipate = '';
            foreach ($listParticipate as $TheUser){
                $chaineParticipate .= $chaineParticipate."ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;CN=".$TheUser['User']['email'].";X-NUM-GUESTS=0:mailto:".$TheUser['User']['email']."\n";
            }
            
            if ($value['Event']['allday']){
                $date_start = date('Ymd', strtotime($value['Event']['date_start']." +1 month"));
                $date_end = date('Ymd', strtotime($value['Event']['date_end']." +1 month +1 days"));
            }else{
                $date_start = date('Ymd\THis\Z', strtotime($value['Event']['date_start']." +1 month"));
                $date_end = date('Ymd\THis\Z', strtotime($value['Event']['date_end']." +1 month"));
            }
                      
            $date_stamp = date('Ymd\THis\Z', strtotime("now"));
            $date_created = date('Ymd\THis\Z', strtotime("now"));
            $date_updated = date('Ymd\THis\Z', strtotime("now"));

            $chaine .=  "BEGIN:VEVENT\n".
                        "DTSTART:".$date_start."\n".
                        "DTEND:".$date_end."\n".
                        "DTSTAMP:".$date_stamp."\n".
                        "UID:".$value['Event']['id']."\n".
                        $chaineParticipate.
                        "CREATED:".$date_created."\n".
                        "DESCRIPTION:".$value['Event']['description']."\n".
                        "LAST-MODIFIED:".$date_updated."\n".
                        "LOCATION:".$value['Event']['address']."\n".
                        "SEQUENCE:0\n".
                        "STATUS:CONFIRMED\n".
                        "SUMMARY:".$value['Event']['title']."\n".
                        "TRANSP:OPAQUE\n".
                        "END:VEVENT\n";             
        }
        $chaine .= "END:VCALENDAR";
        
        $fp = fopen($this->Auth->user('email').".ics","w+"); 
        fwrite($fp, $chaine);
        fclose($fp);
        
        $this->response->file($this->Auth->user('email').".ics", array('download' => true, 'name' => 'Export_Agenda'));

    }
    
    public function search(){
        $listEvent = null;
        $word = null;
        $date_debut = null;
        $date_fin = null;
        if ($this->request->is('post')) {
            $word = isset($this->request->data['search']) ? $this->request->data['search'] : null;
            $date_debut = isset($this->request->data['date_debut']) ? $this->request->data['date_debut'] : null;
            $date_fin = isset($this->request->data['date_fin']) ? $this->request->data['date_fin'] : null;
            
            $and = " AND ( ";
            $requete = "SELECT *  FROM users User INNER JOIN participates Participate ON User.id = Participate.id_users INNER JOIN events Event ON Participate.id_events = Event.id WHERE (Event.author = ".$this->Auth->user('id')." OR Participate.id_users = ".$this->Auth->user('id').") ";  
                             
            if ($word != null){
                $requete .= $and."Event.title LIKE '%".$word."%'  OR Event.description LIKE '%".$word."%' OR User.email LIKE '%".$word."%' ";
                $and = "";
            }
            if(($date_debut != null) && ($date_fin != null)){
                $requete .= $and."Event.date_start >= '".date('Y-m-d', strtotime($date_debut))."' AND Event.date_end <= '".date('Y-m-d', strtotime($date_fin))."' ";
                $and = "";
            }
            if ($and == "")
                $requete .= ")";
         
            $requete .= " GROUP BY Event.id";
            $listEvent = $this->Participate->query($requete);
        }

        $lst_participants = array();
        foreach ($listEvent as $value){
            $temp = $this->Participate->find('all',  array('conditions' => array('Event.id' => $value['Event']['id'], 'Participate.id_users <>' => $this->Auth->user('id')), 'fields' => array('User.email', 'Event.id')));
            $str = array();
            foreach ($temp as $e){
                array_push($str, $e['User']['email']);
            }
            $lst_participants[$value['Event']['id']] = $str;
        }
        
        //var_dump($listEvent);
        $this->set('lst_participants', $lst_participants);
        $this->set('search', $word);
        $this->set('date_debut', $date_debut);
        $this->set('date_fin', $date_fin);
        $this->set('ListEvents', $listEvent);
    }
    
    public function delete(){
        $this->autoRender = false;
        $listEvent = $this->Participate->find('all',  array('conditions' => array('Participate.id_events' => $this->request->data['Event'])));
        foreach ($listEvent as $value) {
            $this->Participate->delete($value['Participate']['id']);
        }
        $this->Event->delete($this->request->data['Event']);  
    }
    
    public function geoloc(){
        $listEvent = $this->Participate->find('all',  array('conditions' => array('Participate.id_users' => $this->Auth->user('id'))));
        $this->set('ListEvents', $listEvent);
    }
    
    public function get_event_json(){
        $this->autoRender = false;
        $listEvent = $this->Participate->find('all',  array('conditions' => array('Participate.id_users' => $this->Auth->user('id'))));
        echo json_encode(array($this->User->find('first', array('conditions' => array ('id' => $this->Auth->user('id')))),$listEvent));
    }
}
