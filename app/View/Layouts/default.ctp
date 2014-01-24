<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<?= $this->Facebook->html() ?>
<head>        
	<?= $this->Html->charset() ?>
	<title>
            Agenda Web
		<?php //echo $cakeDescription ?>
		<?php //echo $title_for_layout; ?>
	</title>
    
    <link rel="shortcut icon" href="../Agenda/app/webroot/img/cake.icon.png" type="image/x-icon"/> 
    <link rel="icon" href="../Agenda/app/webroot/img/cake.icon.png" type="image/x-icon"/>
	<?php
		echo $this->Html->meta('icon');
                echo $this->Html->css('bootstrap');
                echo $this->Html->css('fullcalendar');
                echo $this->Html->css('unicorn.main');
                echo $this->Html->css('MenuLeft');
                echo $this->Html->css('unicorn.grey');
                echo $this->Html->css('fullcalendar');
                echo $this->Html->css('datetimepicker');
                echo $this->Html->css('datepicker');
                echo $this->Html->css('font-awesome');
                echo $this->Html->css('jquery.tagedit');
                echo $this->Html->css('tagmanager');
                echo $this->Html->css('loading');
                
                echo $this->Html->script('jquery.min');
                echo $this->Html->script('jquery.ui.custom');
                echo $this->Html->script('bootstrap');
                echo $this->Html->script('fullcalendar.min');
                //echo $this->Html->script('events.calendar');
                echo $this->Html->script('bootstrap-datetimepicker');
                echo $this->Html->script('bootstrap-datepicker');
                echo $this->Html->script('bootstrap-datetimepicker.fr');
                echo $this->Html->script('tagmanager');  
                echo $this->Html->script('jquery.timer');
                
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>

<body>
 
    <div id="container">
<!--		<div id="header">-->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
        <div class="container-fluid">
        <a class="brand" href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'index')); ?>">Agenda Web</a>
            <div id="social_networks_profiles" class="nav-collapse collapse" style="display:none;" >
                <ul id="facebook_profile" class="nav pull-right" >
                    
                    <li class="divider-vertical"></li>
                    <li id="facebook_profile_text" class="dropdown"></li>
                    
                </ul>
            </div>

            <?php  if (!empty($TheUser)) {?>

            <div id="UserName" class="nav-collapse collapse">
                <ul class="nav pull-right">
                  <form class="navbar-search pull-left" method="post" action="<?= $this->Html->url(array('controller' => 'Events', 'action' => 'search')) ?>">
                    <input type="text" class="search-query" placeholder="Rechercher" id="data" name="data[search]" value="<?= isset($search) ? $search : null ?>">
                  </form>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#"  class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-envelope icon-white"></i>
                            <span class="info-label badge badge-info" id="span_nb_notification"><?= $nb_notifications ?></span>
                            <b class="caret"></b>
                        </a>
                        <ul id='MenuNotifications' class="dropdown-menu">
                            <?= $notifications ?>
                        </ul>
                    </li>
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#"  class="dropdown-toggle" data-toggle="dropdown">
                                <?= $img ?>
                            <b id="lbl_UserName" runat="server"><?= $TheUser['User']['name'] ?></b>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li style="cursor:pointer;">
                                <a href="<?= $this->Html->url(array('controller'=>'Users', 'action'=>'view', CakeSession::read("Auth.User.id"))) ?>"><i class="icon-user"></i>&nbsp&nbspCompte</a>
                            </li>
                            <li style="cursor:pointer;">
                                <a href="<?= $this->Html->url(array('controller'=>'Events', 'action'=>'geoloc')) ?>"><i class="icon-map-marker"></i>&nbsp&nbspGéolocalisation</a>
                            </li>                           
                                <?= $TheButton ?>
<!--                            <li style="cursor:pointer;">
                                <a href="<?= $this->Html->url(array('controller'=>'Users', 'action'=>'logout', CakeSession::read("Auth.User.id"))) ?>"><i class="icon-user"></i>&nbsp&nbspDéconnexion</a>
                            </li>-->
                        </ul>
                    </li>
                    

                </ul>
            </div>
        

            <?php } ?>

            </div>
        </div>
    </div>
<!--		</div>-->
		<div style="margin-top:60px;">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
</div>

    <div id="footer" style="background-color:rgba(0, 0, 0, 0.5);">

    </div>
</body>
<?= $this->Facebook->init() ?>
</html>
