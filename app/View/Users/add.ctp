<!-- app/View/Users/add.ctp -->
<div class="users form">
<?php echo $this->Form->create('User');?>
    
    <div class="container" style="margin-top:6%;">
        <div class="span5" style="float:none; margin-right:auto; margin-left:auto;" >
            <div style="padding:30px 0px 30px 50px; background: rgba(0, 0, 0, 0.3); border-radius: 5px; color: White;">
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Nom d'utilisateur :</label>
                    <div class="input-prepend input-append">
                        <span class="add-on"><i class="icon-user"></i></span>
                        <input name="data[User][name]" placeholder="Nom Prenom" maxlength="50" type="text" id="UserNomUser" required="required">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">Email :</label>
                    <div class="input-prepend input-append">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input name="data[User][email]" placeholder="xxxx@xxx.xxx" type="email" id="UserEmailUser" required="required">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">Mot de passe :</label>
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-key"></i></span>
                        <input name="data[User][password]"  placeholder="5 caratères minimum" type="password" id="UserMotDePasse" required="required">
                    </div>
                </div>
                <div class="control-group"> 
                    <button type="submit" class="btn" value='Ajouter'>Créer</button>
                </div>
            </div>
        </div>
    </div>
</div>