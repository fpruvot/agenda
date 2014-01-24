<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User');?>
   <div class="container" style="margin-top:6%;">
        <div class="span5" style="float:none; margin-right:auto; margin-left:auto;" >
            <div style="padding:30px 0px 30px 50px; background: rgba(0, 0, 0, 0.3); border-radius: 5px; color: White;">
                <a href="<?= $this->Html->url(array('controller'=>'Users', 'action'=>'add')) ?>"><p style="text-align: right; padding-right: 3%; margin-top: -5%;">Créer votre compte</p></a>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Email :</label>
                    <div class="input-prepend input-append">
                        <span class="add-on"><i class="icon-user"></i></span>
                        <input class="add-on" name="data[User][email]" type="email" id="email" required="required">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Mot de passe :</label>
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-off"></i></span>
                        <input class="add-on" name="data[User][password]" type="password" id="mot_de_passe" required="required">
                    </div>
                </div>
                <div class="control-group"> 
                    <button type="submit" class="btn" value='Connexion'>Connexion</button>
                </div>                
                <a href=""><p style="text-align: left; margin-bottom: -3%;">Mot de passe oublié</p></a>
            </div>
        </div>
    </div>
    <div style="text-align:center;">
        <button class="btn" type="button" onclick="FB.login(function(response){ if (response.authResponse) window.location.reload(); });  return false;" style="margin:20px"><img alt="" src="../img/Facebook.png" width="40px" style="padding:5px 5px 2px 5px;"><br /><small>Facebook</small></button>
        <button class="btn" type="button" onclick="window.location.href='<?php echo $HrefGoogle; ?>';" style="margin:20px"><img alt="" src="../img/Google.png" width="40px" style="padding:5px 5px 2px 5px;"><br /><small>Google+</small></button>
        <button class="btn" type="button" style="margin:20px"><img alt="" src="../img/Twitter.png" width="40px" style="padding:12px 5px 8px 5px;"><br /><small>Twitter</small></button>
    </div>
</div>

