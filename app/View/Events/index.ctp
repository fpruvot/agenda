<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>

<?php echo $this->Html->script('events.calendar'); ?>

<style type="text/css">
.modal {
    width:600px;
}

.alert-info{
    font-size: 14px;
}

.alert{
    padding: 8px 28px 8px 8px
}

</style>

<div id="div_loading" class="demo-3 demo-dark" style="display:none;">
<section class="main">
        <!-- the component -->
        <ul class="bokeh">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
        </ul>
</section>
</div>

<div id='div_override'>
<!--<input type="hidden" id="time_start" value="<?php echo($user['User']['hour_start']); ?>">
<input type="hidden" id="time_end" value="<?php echo($user['User']['hour_end']); ?>">-->
<div id="content" style="margin-top: 60px;">

    <div class="widget-box widget-calendar">
            <div class="widget-title">
                    <span class="icon"><i class="icon-calendar"></i></span>
                    <h5>Calendrier</h5>
                    <div class="buttons">
                            <a id="add-event" data-toggle="modal" href="<?php echo $this->Html->url(array('controller' => 'Events', 'action' => 'extraction_ical')); ?>" class="btn-small btn-inverse">Exporter</a>
                    </div>
            </div>
            <div class="widget-content nopadding">
                    <div class="panel-left">
                            <div id="fullcalendar"></div>
                    </div>
                    <div id="external-events" class="panel-right">
                            <div class="panel-title"><h5>Ev&egrave;nements</h5></div>
                            <div class="panel-content" style='display: none;' id='panel'>
                                <div class='alert alert-info' id='block_guauche'>
                                    <input id='input_id_modif' type="hidden" value=''>
                                    Titre: <button type='button' class='close' data-dismiss='alert'>&times;</button><input id='EventTitle_modif' type='text'><br>
                                    <div name="div_anniversaire_hide">Toute la journ&eacute;e: <input type='checkbox' id='EventAllday_modif' value='' style='margin-bottom: 10px;'></br></div>
                                    <div name="">Date d&eacute;but: <input id='input_date_start_modif' class='form_datetime' type='text' readonly='' size='16'><br></div>
                                    <div name="div_anniversaire_hide">Date fin: <input id='input_date_end_modif' class='form_datetime' type='text' readonly='' size='16'><br></div>
                                    <div name="">Description: <textarea id='EventDescription_modif' name='description_modif'></textarea><br></div>
                                    <div name="div_anniversaire_hide">Adresse: <input id='EventAddress_modif' class='input-xlarge' type='text' value='' autocomplete='off'></div>
                                    <div name="div_anniversaire_hide" id="div_participant_hide">Participants: <div id='div_tag'><input id="input_participants_update" type="text" name="tags" placeholder="Saisir email" class="tm-input"/></div></div>
                                    <input id='inputlat_modif' type="hidden" value=''>
                                    <input id='inputlng_modif' type="hidden" value=''>
                                    <div name="div_anniversaire_hide"><button id='btn_submit_modif' class='btn' value='Modifier'>Modifier</button><button id='btn_submit_suppr' class='btn' value='Supprimer'>Supprimer</button></div>
                                </div>
                            </div>
                    </div>
            </div>
    </div>
</div>
</div>

<div class="modal hide fade" id="modal-add-event" role="dialog" aria-hidden="true">
    <?php //echo $this->Form->create('Event', array('default' => false)); ?>
    <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">x</button>
           <h3>Nouvelle Evènement</h3>
    </div>
    <div class="modal-body form-horizontal">

            <div id="alert_periode" class="alert alert-error text-center" style="display: none;">
              La période saisie est invalide !
            </div>

            <div class="control-group">
                <label class="control-label" for="inputPassword">Titre</label>
                <div class="controls">
                    <?php echo $this->Form->input('titre', array('label' => false, 'placeholder' => 'Titre', 'required' => "required", 'id'=>'EventTitre', 'autofocus' => 'autofocus')); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Description</label>
                <div class="controls">
                    <?php echo $this->Form->input('description', array('label' => false, 'placeholder' => 'Description', 'id'=>'EventDescription')); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Début</label>
                <div class="controls">
                    <input id="input_date_start" name="data[Event][date_start]" size="16" type="text" readonly class="form_datetime" placeholder="Date - Heure">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Fin</label>
                <div class="controls">
                    <input id="input_date_end" name="data[Event][date_end]" size="16" type="text" readonly class="form_datetime" placeholder="Date - Heure">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Toute la journée</label>
                <div class="controls">
                    <?php echo $this->Form->checkbox('allday', array('hiddenField' => false, 'id'=>'EventAllday')); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputAdresse">Lieu</label>
                <div class="controls">
<!--                    <input  id="inputAdresse" type="text" class="input-xlarge" name="data[Event][address]" placeholder="Adresse"> -->
                    <?php echo $this->Form->input('address', array('label' => false, 'placeholder' => 'Adresse', 'class' => 'input-xlarge', 'id' => 'EventAddress')); ?>
                    <input id='inputlat' name="data[Event][lat]" type="text" style="display:none">
                    <input id='inputlng' name="data[Event][lng]" type="text" style="display:none">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputAdresse">Participants</label>
                <div class="controls">
                    <div class="autocomplete-ui">
                        <div style="padding-bottom: 5px;">                        
                            <input id="input_participants_create" type="text" name="tags" placeholder="Saisir email" class="tm-input"/>
                        </div>
                    </div>
                    <div id="div_modal_tag"></div>
                </div>
            </div>
        <div class="control-group">
            <div id="alert_email" class="alert alert-error"  style='margin-left: 180px; width: 230px; display: none'>
                <button class="close" onclick="$('.alert').hide(); return false;">&times;</button>
                L'email saisie n'est pas valide.
            </div>
        </div>
        
    </div>
    <div class="modal-footer">
           <a href="#" class="btn" data-dismiss="modal">Annuler</a>
           <button id='btn_submit' type="submit" class="btn" value='Ajouter'>Créer</button>
    </div>
        
    <?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
    
    $( "#progressbar" ).progressbar({
      value: 100
    });

  
    var input_modal = document.getElementById('EventAddress');
    var input_panel = document.getElementById('EventAddress_modif');
    var options = {types: []};
    var autocomplete_modal = new google.maps.places.Autocomplete(input_modal, options);
    var autocomplete_panel = new google.maps.places.Autocomplete(input_panel, options);
    
    //Initialisation de l'autocomplete google dans la fenêtre d'ajout d'évenement
    google.maps.event.addListener(autocomplete_modal, 'place_changed', function() {
        var place = autocomplete_modal.getPlace();
        var position = place.geometry.location;
        $('#inputlat').text(position.lat());
        $('#inputlng').text(position.lng());
    });
    
    //Initialisation de l'autocomplete google dans le panel à droite du calendrier
    google.maps.event.addListener(autocomplete_panel, 'place_changed', function() {
        var place = autocomplete_panel.getPlace();
        var position = place.geometry.location;
        $('#inputlat_modif').text(position.lat());
        $('#inputlng_modif').text(position.lng());
    });
    
    //reglage de l'index de l'automplete dans le modal'
    $(input_modal).focus(function(){
       $('.pac-container').attr({'style' : 'z-index:1000000;'}); 
    });
    
    //Initialisation du datetimepicker
    $(".form_datetime").datetimepicker({
        format: "dd MM yyyy - hh:ii",
        autoclose: true,
        todayBtn: true,
        pickerPosition: "right",
        language: 'fr'
    });
  
    $('.icon-calendar').css( 'cursor', 'pointer' );
    $('.icon-calendar').click(function(){
    $(this).datepicker().on('changeDate', function(ev) { $('#fullcalendar').fullCalendar( 'gotoDate', ev.date); }).data('datepicker');
        $(this).datepicker('show');
    });
    
    var LstEventDelete = new Array();
    var LstTacheDelete = new Array();

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    //Initialisation du calendrier
    var calendar = $('#fullcalendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'today,month,agendaWeek,agendaDay'
        },
        defaultView: '<?php echo($user['User']['view_default']); ?>',
        firstDay : <?php echo($user['User']['day_start']); ?>,
        minTime: <?php echo($user['User']['hour_start']); ?>,
        maxTime: <?php echo($user['User']['hour_end']); ?>,
        selectable: true,
        selectHelper: true,
        editable: true,
        eventClick: function (calEvent, jsEvent, view) {
            $('#input_id_modif').val(calEvent.id);
            $("#EventTitle_modif").val(calEvent.title);
            $("#input_date_start_modif").val($('<div />').html(($.fullCalendar.formatDate(new Date(calEvent.start), "dd MMMM yyyy - HH:mm"))).text());
            $("#input_date_end_modif").val($('<div />').html($.fullCalendar.formatDate(new Date(calEvent.end), "dd MMMM yyyy - HH:mm")).text());
            
            if (calEvent.allDay){
                //Evenement durant une ou plusieurs journées
                if (calEvent.end == null){
                    //Evenement durant une journée
                    $("#input_date_end_modif").val($('<div />').html($.fullCalendar.formatDate(new Date(calEvent.start), "dd MMMM yyyy - HH:mm")).text());
                    $("#EventAllday_modif").attr('checked', true);
                }else{
                    //Evenement durant des journée
                    $("#EventAllday_modif").attr('checked', true);
                } 
            }else{
                //Evenement sur une plage horraire
                $("#EventAllday_modif").attr('checked', false);
            }

            $("#EventDescription_modif").val(calEvent.description);
            $("#EventAddress_modif").val(calEvent.address);   
            $("#input_participants_update").data('tagmanager').empty();
            
            //Initialisation des participants dans les tags
            calEvent.participates.forEach(function(user){
                $("#input_participants_update").data('tagmanager').populate([user]);
            });
            
            //Gestion de l'affichage d'un evenement en fonction de l'utilisateur
            if(calEvent.author != <?= $user['User']['id'] ?>){ //L'utilisateur n'est pas auteur de l'évenement
                $("#div_participant_hide").find('.tagmanagerRemoveTag').each(function(index, element){
                    $(this).hide();
                });
                $("#input_participants_update").attr('readonly', true);
                $("#EventTitle_modif").attr('readonly', true);
                $("#EventTitle_modif").focus();
                $("#input_date_start_modif").datetimepicker('remove');
                $("#input_date_end_modif").datetimepicker('remove');
                $("#EventAllday_modif").attr('readonly', true);
                $("#EventDescription_modif").attr('readonly', true);
                $("#EventAddress_modif").attr('readonly', true);
                $("#btn_submit_modif").attr('disabled', true);
                $("#btn_submit_suppr").attr('disabled', true);
            }else{ //L'utilisateur est auteur de l'évenement
                $("#EventTitle_modif").attr('readonly', false);
                $("#EventTitle_modif").focus();
                $(".form_datetime").datetimepicker({
                    format: "dd MM yyyy - hh:ii",
                    autoclose: true,
                    todayBtn: true,
                    pickerPosition: "right",
                    language: 'fr'
                });
                $("#EventAllday_modif").attr('readonly', false);
                $("#EventDescription_modif").attr('readonly', false);
                $("#EventAddress_modif").attr('readonly', false);
                $("#btn_submit_modif").attr('disabled', false);
                $("#btn_submit_suppr").attr('disabled', false);
            }
            
            if (calEvent.className == 'anniversaire'){
                $('[name="div_anniversaire_hide"]').hide();
            }else{
                $('[name="div_anniversaire_hide"]').show();
            }
            $("#panel").show();
        },
        select: function (start, end, allDay) {
             (start.getHours() == 0) ? start.setHours(<?= $user['User']['hour_start'] ?>) : null;
             (end.getHours() == 0) ? end.setHours(<?= $user['User']['hour_end'] ?>) : null;
            $('#input_date_start').val($('<div />').html($.fullCalendar.formatDate(new Date(start), "dd MMMM yyyy - HH:mm")).text());            
            $('#input_date_end').val($('<div />').html($.fullCalendar.formatDate(new Date(end), "dd MMMM yyyy - HH:mm")).text());
            $("#modal-add-event").modal('show');   
            $('#modal-add-event').on('shown', function() {
                $("#EventTitre").focus();
    })
        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
            
            var date = new Date(event.end);
            var dateStringEnd =  date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":00";
            date = new Date(event.start);
            var dateStringStart =  date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":00";
            
            alert(event.id);
            var TheEvent =  { 
                id: event.id,
                date_start : dateStringStart,
                date_end : dateStringEnd
            };   
            $.ajax({
                type: "POST",
                url: "/Agenda/Events/resize",
                data: {Event: TheEvent}                   
            });
        },
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            var dateEnd = new Date(event.end);
            var dateEndString =  dateEnd.getFullYear() + "-" + dateEnd.getMonth() + "-" + dateEnd.getDate() + " " + dateEnd.getHours() + ":" + dateEnd.getMinutes();
            var dateStart = new Date(event.start);
            var dateStartString =  dateStart.getFullYear() + "-" + dateStart.getMonth() + "-" + dateStart.getDate() + " " + dateStart.getHours() + ":" + dateStart.getMinutes();
            var TheEvent =  { 
                id: event.id,
                date_end : dateEndString,
                date_start: dateStartString,
                allday: allDay ? "1" : "0"
            };   
            $.ajax({
                type: "POST",
                url: "/Agenda/Events/update",
                data: {Event: TheEvent}                   
            });
    }
       ,events: [ <?php echo($ListEvents); ?> ]
    });
    
    $("#btn_submit").click(function() {
        if ($('#EventTitre').val().trim() != ""){ 
            var ListeParticipant = new Array();
            $("#input_participants_create").data('tagmanager').tagStrings.forEach(function(email){
                ListeParticipant.push(email);
            });
            var month = new Array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre")
            var DateStartArray = $('#input_date_start').val().split(' ');
            var DateEndArray = $('#input_date_end').val().split(' ');
            var DateStartString = DateStartArray[2] + "-" + month.indexOf(DateStartArray[1]) + "-" + DateStartArray[0] + " " + DateStartArray[4].split(':')[0] + ":" + DateStartArray[4].split(':')[1] + ":00";
            var DateEndString = DateEndArray[2] + "-" + month.indexOf(DateEndArray[1]) + "-" + DateEndArray[0] + " " + DateEndArray[4].split(':')[0] + ":" + DateEndArray[4].split(':')[1] + ":00";
            var DateStart = new Date(DateStartArray[2], month.indexOf(DateStartArray[1]), DateStartArray[0], DateStartArray[4].split(':')[0], DateStartArray[4].split(':')[1])
            var DateEnd = new Date(DateEndArray[2], month.indexOf(DateEndArray[1]), DateEndArray[0], DateEndArray[4].split(':')[0], DateEndArray[4].split(':')[1])

            if (DateStart <= DateEnd) {
                
            var TheEvent =  { 
                title : $('#EventTitre').val(),
                description : $('#EventDescription').val(),
                date_start : DateStartString,
                date_end : DateEndString,
                address: $('#EventAddress').val(),
                allday: $('#EventAllday').is(':checked') ? "1" : "0",
                lat: $('#inputlat').text(),
                lng: $('#inputlng').text(),
                author: <?php echo($user['User']['id']); ?>
            };  

            $.ajax({
                    type: "POST",
                    url: "/Agenda/Events/add",
                    data: {Event: TheEvent, Participate: ListeParticipant},
                    success: function(data){ 
                        if (data != 'Error')
                            var myEvent = {
                                id: data,
                                title: TheEvent['title'],
                                allDay: $('#EventAllday').is(':checked'),
                                start: DateStart,
                                end: DateEnd,
                                description: TheEvent['description'],
                                address: TheEvent['address'],
                                author: TheEvent['author'],
                                className: 'tache',
                                participates: ListeParticipant
                            };
                            $('#fullcalendar').fullCalendar('renderEvent', myEvent, true);
                            //$('#fullcalendar').fullCalendar('unselect');
                            $('#modal-add-event').modal('hide');
                            $('#alert_periode').hide();
                    },
                    error: function(data){
                        alert(data);
                    }
            });
                    
            } else { $('#alert_periode').show(); }
        }   
    });
    
    $("#btn_submit_modif").click(function() {
        
        var ListeParticipant = new Array();
        
        var month = new Array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "AoÃ»t", "Septembre", "Octobre", "Novembre", "Décembre")
        var DateStartArray = $('#input_date_start_modif').val().split(' ');
        var DateEndArray = $('#input_date_end_modif').val().split(' ');
        var DateStartString = DateStartArray[2] + "-" + month.indexOf(DateStartArray[1]) + "-" + DateStartArray[0] + " " + DateStartArray[4].split(':')[0] + ":" + DateStartArray[4].split(':')[1] + ":00";
        var DateEndString = DateEndArray[2] + "-" + month.indexOf(DateEndArray[1]) + "-" + DateEndArray[0] + " " + DateEndArray[4].split(':')[0] + ":" + DateEndArray[4].split(':')[1] + ":00";
        var DateStart = new Date(DateStartArray[2], month.indexOf(DateStartArray[1]), DateStartArray[0], DateStartArray[4].split(':')[0], DateStartArray[4].split(':')[1])
        var DateEnd = new Date(DateEndArray[2], month.indexOf(DateEndArray[1]), DateEndArray[0], DateEndArray[4].split(':')[0], DateEndArray[4].split(':')[1])
                   
        $('#input_participants_update').data('tagmanager').tagStrings.forEach(function(email){
            ListeParticipant.push(email);
        });
        
        ListeParticipant.push(<?php echo("'".$user['User']['email']."'"); ?>);
        
        var TheEvent =  { 
            id : $('#input_id_modif').val(),
            title : $('#EventTitle_modif').val(),
            description : $('#EventDescription_modif').val(),
            date_start : DateStartString,
            date_end : DateEndString,
            address: $('#EventAddress_modif').val(),
            allday: $('#EventAllday_modif').is(':checked') ? "1" : "0",
            lat: $('#inputlat_modif').text(),
            lng: $('#inputlng_modif').text(),
            author: <?php echo($user['User']['id']); ?>
        };  
        
        $.ajax({
                type: "POST",
                url: "/Agenda/Events/update",
                data: {Event: TheEvent, Participate: ListeParticipant},
                success: function(data){ 
                    
                    var month = new Array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "AoÃ»t", "Septembre", "Octobre", "Novembre", "Décembre")
                    var DateStartArray = $('#input_date_start_modif').val().split(' ');
                    var DateEndArray = $('#input_date_end_modif').val().split(' ');
                    var DateStartString = DateStartArray[2] + "-" + month.indexOf(DateStartArray[1]) + "-" + DateStartArray[0] + " " + DateStartArray[4].split(':')[0] + ":" + DateStartArray[4].split(':')[1] + ":00";
                    var DateEndString = DateEndArray[2] + "-" + month.indexOf(DateEndArray[1]) + "-" + DateEndArray[0] + " " + DateEndArray[4].split(':')[0] + ":" + DateEndArray[4].split(':')[1] + ":00";
                    var DateStart = new Date(DateStartArray[2], month.indexOf(DateStartArray[1]), DateStartArray[0], DateStartArray[4].split(':')[0], DateStartArray[4].split(':')[1])
                    var DateEnd = new Date(DateEndArray[2], month.indexOf(DateEndArray[1]), DateEndArray[0], DateEndArray[4].split(':')[0], DateEndArray[4].split(':')[1])
                    
                    Array.prototype.unset = function(val){
                        var index = this.indexOf(val)
                        if(index > -1){
                            this.splice(index,1)
                        }
                    }

                    ListeParticipant.unset('<?php echo($user['User']['email']); ?>');


                    if (data != 'Error')
                        var myEvent = $('#fullcalendar').fullCalendar('clientEvents', TheEvent['id']);
                        myEvent[0].title = TheEvent['title'];
                        myEvent[0].allDay = $('#EventAllday_modif').is(':checked');
                        myEvent[0].start = DateStart;
                        myEvent[0].end = DateEnd;
                        myEvent[0].description = TheEvent['description'];
                        myEvent[0].address = TheEvent['address'];
                        myEvent[0].participates = ListeParticipant;
                        $('#fullcalendar').fullCalendar('updateEvent', myEvent[0]);
                        $("#panel").hide();
                },
                error: function(data){ 
                }
        });
             
    });
    
$("#btn_submit_suppr").click(function() {
    var id_event = $('#input_id_modif').val();
    $.ajax({
                type: "POST",
                url: "/Agenda/Events/delete",
                data: {Event: id_event },
                success: function(data){ 
                    if (data != 'Error')
                        $('#fullcalendar').fullCalendar('removeEvents', id_event);
                        $("#panel").hide();
                },
                error: function(data){ 
                }
        });
    });
    
    $('#modal-add-event').on('hidden', function () {
        if ($(this).attr("class") == "modal hide fade")
            $("#input_participants_create").data('tagmanager').empty()
    });

    $('#modal-add-event').on('show', function () {
        $("#panel").hide();
    });
    
    function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    alert(out);

}
    
</script>

