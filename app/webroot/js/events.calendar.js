    function notification_click(tache_id, participate_id){
        var calEvent = $('#fullcalendar').fullCalendar('clientEvents', tache_id)[0];
        $('#input_id_modif').val(calEvent.id);
        $("#EventTitle_modif").val(calEvent.title);
        
        $("#input_date_start_modif").val($.fullCalendar.formatDate(new Date(calEvent.start), "dd MMMM yyyy - h:mm"));
        $("#input_date_end_modif").val($.fullCalendar.formatDate(new Date(calEvent.end), "dd MMMM yyyy - h:mm"));

        if (calEvent.allDay){
            $("#EventAllday_modif").attr('checked', true);
        }else{
            $("#EventAllday_modif").attr('checked', false);
        }

        $("#EventDescription_modif").val(calEvent.description);
        $("#EventAddress_modif").val(calEvent.address);

        var TheParticipate =  { 
            id : participate_id,
            notification: 1
        };  
        $.ajax({
                type: "POST",
                url: "/Agenda/Participates/update",
                data: {Participate: TheParticipate},
                success: function(data){ 
                    $("#" + tache_id).remove();
                    $('#span_nb_notification').text($('[name="li_notification"]').length);
                    $("#panel").show();
                },
                error: function(data){ 
                }
        });
      
    }
    

$(window).load(function(){

var id_event = $(location).attr('hash');
if (id_event){
    $('#fullcalendar').fullCalendar( 'changeView', 'agendaDay');
    var calEvent = $('#fullcalendar').fullCalendar('clientEvents', id_event.substr(1, id_event.lenght))[0];
    $('#fullcalendar').fullCalendar( 'gotoDate', calEvent.start);
    //calEvent.click();
}

function ValidateEmail(a){
    valide1 = false;
    for(var j=1;j<(a.length);j++){
            if(a.charAt(j)=='@'){
                    if(j<(a.length-4)){
                            for(var k=j;k<(a.length-2);k++){
                                    if(a.charAt(k)=='.') valide1=true;
                            }
                    }
            }
    }
    if(valide1 == false)  { $('#alert_email').show();}
    
    if (valide1)
        return a;
    else
        return false;
}

    $("#input_participants_update").tagmanager({
        initialCap: false,
        tagFieldName: 'List_Email_Update',
        validateHandler: function(tagManager, tag, isImport) {
            if (!ValidateEmail(tag)){
                return false;
            }else{
                if (isImport) return tag;
                var index = $.inArray(tag, tagManager.tagStrings);
                if (index != -1) {
                    $('#' + tagManager.tagIds[index]).effect(
                        "highlight", {}, 3000);
                    return false;
                }
                return tag;
            }
                
        }
    });
    
    
    $("#input_participants_create").tagmanager({
        initialCap: false,
        tagFieldName: 'List_Email_Create',
        createElementHandler: function(tagManager, tagElement, isImport) {
            $(tagElement).appendTo('#div_modal_tag');
        },
        validateHandler: function(tagManager, tag, isImport) {
            if (!ValidateEmail(tag)){
                return false;
            }else{
                var index = $.inArray(tag, tagManager.tagStrings);
                if (index != -1) {
                    $('#' + tagManager.tagIds[index]).effect(
                        "highlight", {}, 3000);
                    return false;
                }
                return tag;
            }
        }
    });
    
var Contacts = new Array();

$.getJSON("/Agenda/Users/GetContactGoogle",  // le fichier qui recevera la requête
    //{"categorie": "electronique", "produit": 3},  // les paramètres
    function(data){  // la fonction qui traitera l'objet reçu
        data.forEach(function(contact){
            if ($.isArray(contact[1])){
                contact[1].forEach( function(Email){
                    Contacts.push({"label": contact[0] == "null" ? contact[0] : ""  + " - " + Email, "value":Email});
                })
            }else{
                Contacts.push({"label": (contact[0] != null ? contact[0] + " - " : "") +  contact[1], "value": contact[1]});
            }                   
        })
        
        $('#input_participants_create').autocomplete({ 
            source: Contacts,
            select: function(event, ui ) { 
                $(this).data('tagmanager').populate([ui.item.value]); 
                return false; 
            },
            autoFocus: true
        });
        
        $('#input_participants_update').autocomplete({ 
            source: Contacts,
            select: function(event, ui ) { 
                $(this).data('tagmanager').populate([ui.item.value]); 
                return false; 
            }
        });
});


    function TacheInvisible() {
        LstTacheDelete = new Array();
        $('#fullcalendar').fullCalendar('removeEvents', function(event){
            if (event.className == 'tache') {
                LstTacheDelete.push(event)
                return event;
            }
        });
    } 
});

    
function Facebook_Friends() { 
 
    if ($("#button_birthday").attr('name') == 'show'){
        $("#div_override").fadeTo("slow",0);
        $('#div_loading').show();
        FB.api('/me/friends?fields=id,name,birthday', function (response) {
            if (response.data) {
                $.each(response.data, function (index, friend) {
                    try {
                        var date = new Date(friend.birthday);
                        date = new Date(new Date().getFullYear(), date.getMonth(), date.getDate());
                        date.toISOString();

                        var myEvent = {
                            id: friend.id,
                            title: friend.name,
                            allDay: true,
                            start: date,
                            participates: new Array(),
                            address: '',
                            description: 'Anniversaire: ' + friend.name + '\n' + 'Date: ' + friend.birthday,
                            className: 'birthday'
                        };
                        $('#fullcalendar').fullCalendar('renderEvent', myEvent, true);
                        $('#fullcalendar').fullCalendar('unselect');
                    }
                    catch (err) {
                        //Handle errors here
                    }
                });
                } else {
                    alert("Error!");
                }
                $("#button_birthday").text('Masquer les anniversaires')
                $("#button_birthday").attr('name', 'hide')
                $("#div_override").fadeTo("slow", 1);
                $('#div_loading').hide();
            });
        }else{
            $('#fullcalendar').fullCalendar('removeEvents', function(event){
                if (event.className == 'birthday'){return true;}
                else {return false;}
            });
            $("#button_birthday").text('Afficher les anniversaires')
            $("#button_birthday").attr('name', 'show')
        }
}

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    alert(out);

}
