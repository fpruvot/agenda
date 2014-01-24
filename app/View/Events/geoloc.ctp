<style type="text/css">
  #map-canvas { 
      height: 90%;
      width: 50%;
      position: absolute;
  }
.gmnoprint img {
  max-width: none;
}

#map-canvas img { 
           max-width: none; 
}

</style>
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBL4gU-EWkL4v7M24_HtLpmYQPKMMFH-fk&sensor=false">
</script>
<script type="text/javascript">
var totalDistance = 0;
var totalDuration = 0;
var infowindow;
var marker;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var trajet = new Array();
var temps_travail = 0;   
function initialize() {

    directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers : true });

    var mapOptions = {
      center: new google.maps.LatLng(46.3399659, 2.6066450),
      zoom: 6,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);
        
    $.ajax({
        url: "/Agenda/Events/get_event_json",
        dataType: "json",
        data: {start_date: "", end_date: "", action: "get_event"},
        success: function(data) // Variable data contains the data we get from serverside
        {
            var infowindow =  new google.maps.InfoWindow({
                content: ""
            });
            marker = new google.maps.Marker({
                    icon: "http://mt.google.com/vt/icon?color=ff135C13&name=icons/spotlight/spotlight-waypoint-a.png&scale=1",
                    position: new google.maps.LatLng(data[0]['User']['lat'], data[0]['User']['lng']),
                    map: map
            });
            
            bindInfoWindow(marker, map, infowindow, "<h5>".concat(data[0]['User']['name']).concat("<h5><p style='font-size:11px; font-weight:normal;'>").concat(data[0]['User']['address']).concat("</p>"), null);
            data[1].forEach(function(event){
                
                var date_debut = new Date(event['Event']['date_start']);
                var date_fin = new Date(event['Event']['date_end']);
                if (!event['Event']['allday'])
                    temps_travail += (date_fin.getTime() - date_debut.getTime()) / 1000;
                var latlng = new google.maps.LatLng(event['Event']['lat'], event['Event']['lng'])
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                
                bindInfoWindow(marker, map, infowindow, "<h5>".concat(event['Event']['title']).concat("<h5><p style='font-size:11px; font-weight:normal;'>").concat(event['Event']['address']).concat("</p>"), event);                            
                trajet.push({location: latlng});
            });
            
            var t = secondsToTime(temps_travail);
            $('#span_temps_travail').text(t['h']+"h"+t['m']);
                        
            var request = {
                origin: new google.maps.LatLng(data[0]['User']['lat'], data[0]['User']['lng']),
                destination: new google.maps.LatLng(data[0]['User']['lat'], data[0]['User']['lng']),
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                durationInTraffic:true,
                waypoints: trajet
            };
  
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                    directionsDisplay.setMap(map);
                    var legs = response.routes[0].legs;
                    for(var i=0; i<legs.length; ++i) {
                        totalDistance += legs[i].distance.value;
                        totalDuration += legs[i].duration.value;
                    }
                    var time = secondsToTime(totalDuration);
                    $('#span_temps_route').text(time['h']+"h"+time['m']);
                    
                    $('#span_pourcent').text((totalDuration / temps_travail * 100).toFixed(0));
                }
            });

        }
    });
    
}
  
function bindInfoWindow(marker, map, infowindow, strDescription, event) {   
    google.maps.event.addListener(marker, 'mouseover', function() {
        infowindow.setContent(strDescription);
        infowindow.open(map, marker);
    });

    google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.setContent(strDescription);
        infowindow.close(map, marker);
    });      
    google.maps.event.addListener(marker, 'click', function() {
        ///!event ? $(('#').concat(event['Event']['id'])).click() : "";
        $(('#').concat(event['Event']['id'])).collapse('toggle');
    });
}

function secondsToTime(secs)
{
    var hours = Math.floor(secs / (60 * 60));
   
    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);
 
    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);
   
    var obj = {
        "h": n(hours),
        "m": n(minutes),
        "s": n(seconds)
    };
    return obj;
}
function n(n){
    return n > 9 ? "" + n: "0" + n;
}


google.maps.event.addDomListener(window, 'load', initialize);
</script>




<div class="row">
    <div class="span8">
        <div id="map-canvas"></div>
    </div>
    
    <div class="span6 offset9">
    <div class="row">

        <div class="span7 widget-box" style="min-height: 100px;">
            <div class="widget-title">
                    <span class="icon"><i class="icon-bar-chart"></i></span>
                    <h5>Statistiques</h5>
            </div>
            <div class="widget-content">
                <ul class="inline" style="margin-bottom: 0px;">
                    <li style="font-size: 14px;">Temps sur route: <span id="span_temps_route"></span></li>
                    <li style="margin-left: 50px; font-size: 14px;">Temps au travail: <span id="span_temps_travail"></span></li>
                </ul>
                <div style="font-size: 12px; margin-left: 5px;"><i class="icon-warning-sign"></i> Vous passez <span id="span_pourcent"></span>% de votre temps sur la route</div>

            </div>
            <div class="text-center">
                <ul class="nav nav-list">
                    <li><a href="#">Sélectionner une nouvelle journée</a></li>
                  </ul>
            </div>
            
        </div>
    
    <div class="span3">   
        <div class="accordion" id="accordion2" style="background: rgba(0, 0, 0, 0.3); border-radius: 5px;">
        <?php foreach ($ListEvents as $value) {
        ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a style="color: gainsboro; font-size: 15px;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#<?= $value['Event']['id'] ?>">
                    <?= $value['Event']['title'] ?>
                </a>
                </div>
            <div id="<?= $value['Event']['id'] ?>" class="accordion-body collapse" style="background: rgba(999, 999, 999, 0.3); border-radius: 2px; color: white;">
                    <div class="accordion-inner">
                        <?= $value['Event']['description'] ?>
                    </div>
                </div>
        </div>
        <?php
        }
        ?>
    </div>
    </div>
        </div>
    </div>
</div>  