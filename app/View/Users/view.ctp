<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>

<div class="container">
    <div class="span12" style="float:none; margin-right:auto; margin-left:auto;" >
        <div style="padding:30px 0px 30px 50px; background: rgba(999, 999, 999, 0.3); border-radius: 5px; color: White;"> 
            <div class="bs-docs-grid">
                <div class="row-fluid show-grid">
                    <div class="span6">
                        <legend>Données Personnelles</legend>
                    </div>
                </div>
                <div class="row-fluid show-grid">
                    <form class="form-horizontal ">
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">Nom d'utilisateur</label>
                            <div class="controls">
                            <input type="text" id="inputName" value="<?php echo $user['User']['name']; ?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputEmail">Email</label>
                            <div class="controls">
                            <input type="text" id="inputEmail" value="<?php echo $user['User']['email']; ?>" readonly>
                            </div>
                        </div>
                        <div id="div_password" class="control-group">
                            <label class="control-label" for="inputPassword">Password</label>
                            <div class="controls">
                            <input type="password" id="inputPassword" placeholder="Password">
                            </div>
                        </div>
                        <div id="div_new_password" class="control-group" style="display: none;">
                            <label class="control-label" for="inputPassword">Password</label>
                            <div class="controls">
                            <input type="password" id="inputNewPassword" placeholder="Password">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputAdresse">Adresse</label>
                            <div class="controls">
                            <div class="input-append">
                                <input  id="inputAdresse" type="text" class="input-xxlarge" value="<?php echo $user['User']['address']; ?>">
                                <button class="btn" type="button" data-toggle="modal" onClick="$('#myModal').modal('show');"><i class="icon-map-marker"></i></button>
                            </div>
                            </div>
                        </div>
                        <div id="flag" style="display: none;"></div>
                    </form>
                </div>
                <br>
                <div class="row-fluid show-grid">
                    <div class="span6">
                        <legend>Préférences du Calendrier</legend>
                    </div>
                    <div class="row-fluid show-grid">
                        <form class="form-horizontal ">
                            <div class="control-group">
                                <label class="control-label" for="heure_fin">1er jour/semaine</label>
                                <div class="controls">
                                    <?php echo ($this->Form->input('jour_debut', array('options' => array(1 => 'lundi', 2 => 'mardi', 3 => 'mercredi', 4 => 'jeudi', 5 => 'vendredi', 6 => 'samedi', 7 => 'dimanche') , 'default' => $user['User']['day_start'], 'label' => '', 'id' => 'jour_debut'))); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="heure_debut">Heure de début</label>
                                <div class="controls">
                                    <input class="input-mini" type="text" id="heure_debut" placeholder="Email" value="<?php echo $user['User']['hour_start']; ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="heure_fin">Heure de fin</label>
                                <div class="controls">
                                    <input class="input-mini" type="text" id="heure_fin" placeholder="Email" value="<?php echo $user['User']['hour_end']; ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="heure_fin">Vue par défaut</label>
                                <div class="controls">
                                    <?php echo ($this->Form->input('vue_defaut', array('options' => array('agendaDay' => 'Jour', 'agendaWeek' => 'Semaine', 'month' => 'Mois') , 'default' => $user['User']['view_default'], 'label' => '', 'id' => 'vue_defaut'))); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="heure_fin">Alert email</label>
                                <div class="controls">
                                    <label class="checkbox">
                                        <input id="alert_email" type="checkbox" <?= $user['User']['email_notification'] ? "checked" : '' ?>>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Placer votre marker</h3>
  </div>
  <div class="modal-body">
    <div id="mapCanvas" style="width: 560px; height: 350px; margin: -15px"></div>
  </div>
</div>


<script>
var marker;
var map;
var input = document.getElementById('inputAdresse');
var options = {types: []};
var autocomplete = new google.maps.places.Autocomplete(input, options);
var geocoder = new google.maps.Geocoder();
var bool = false;

function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
      SaveAdresse(responses[0].formatted_address, pos);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}


function updateMarkerAddress(str) {
    $("#inputAdresse").val(str);
}

function SaveAdresse(str, position) {
    var TheUser =  { 
                id: <?php echo($user['User']['id']); ?>,
                address: str,
                lat: position.lat(),
                lng: position.lng()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
}

google.maps.event.addListener(autocomplete, 'place_changed', function() {
    bool = true;
    var place = autocomplete.getPlace();
    marker.setPosition(place.geometry.location);
    SaveAdresse(place.formatted_address, place.geometry.location);
});

$('#inputAdresse').change(function(){
        if (bool != true){
            $("#flag").html('<div id="AlertAdresse" class="alert alert-block alert-error fade in" style="width: 500px; margin-left: 180px; margin-top: 20px;"><button type="button" class="close" onClick="CloseAlert();">×</button><p>Veuillez sélectionner une adresse dans la liste des suggestions.<br>Ou placer le marker de la carte sur votre adresse.</p></div>');
            $('#flag').show("drop", { to: {} }, 1000);
        }
        bool = false;
});

function CloseAlert(){
    $("#flag").hide("drop", { to: {} }, 1000);
}

function initialize() {
    
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
        zoom: 5,
        center: new google.maps.LatLng(51.86292391360244, -8.4814453125),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        streetViewControl: false
    });
    
    var latlng = new google.maps.LatLng(48.856614, 2.3522219000000177);
    var adresse = "<?php echo $user['User']['address']; ?>";
    if (adresse != ""){
        latlng = new google.maps.LatLng(<?php if ($user['User']['lat'] != '') echo $user['User']['lat']; else echo '""'; ?>, <?php if ($user['User']['lng']) echo $user['User']['lng']; else echo '""'; ?>);
    }
        
    marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: 'http://maps.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png',
        draggable: true
    });
    // Update current position info.
//    updateMarkerPosition(latlng);
//    geocodePosition(latlng);

    
    $('#myModal').on('shown', function () { 
        google.maps.event.trigger(map, "resize"); 
    });
  
    google.maps.event.addListener(marker, 'dragend', function() {
        alert(marker.getPosition().lat());
        geocodePosition(marker.getPosition());
    });
}

// Onload handler to fire off the app.
google.maps.event.addDomListener(window, 'load', initialize);

$('#heure_debut').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            hour_start: $('#heure_debut').val()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$('#heure_fin').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            hour_end: $('#heure_fin').val()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$('#vue_defaut').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            view_default: $('#vue_defaut').val()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$('#jour_debut').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            day_start: $('#jour_debut').val()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$('#alert_email').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            email_notification: $(this).is(':checked') ? 1 : 0
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$('#inputName').change(function(){
    var TheUser =  { 
            id: <?php echo($user['User']['id']); ?>,
            name: $(this).val()
    };
    $.ajax({
        type: 'POST',
        url: '/Agenda/Users/update',
        data: {User: TheUser}
    });
});

$("#inputPassword")


</script>