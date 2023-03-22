var map, infoWindow, markers = [], markers1 = [];
var iconBase = 'uploads/icon.png';
var styleMap = [
                    {
                      "featureType": "administrative",
                      "elementType": "geometry",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "poi",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "road",
                      "elementType": "labels.icon",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "transit",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    }
                  ];

  function getLocation(){
    $.ajax({
        url: "http://ip-api.com/json",
        type: "GET",
        success: function(data){
          geoSuccess(data);
        }        
   });
  }

  function initMap() {

    getLocation();
    
    // navigator.geolocation.getCurrentPosition(geoSuccess);

  }

  function getUrlParameter(name) {
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  };

   function geoSuccess(position){
    var loc = getUrlParameter('loc');
    var id = getUrlParameter('id');
    var loc = loc.split(",");
    var lat = parseFloat(loc[0]);
    var lng = parseFloat(loc[1]);
      clearMarkers();
      clearMarkers1();

      var pos = {
            lat: position.lat,
            lng: position.lon
          };


      map = new google.maps.Map(document.getElementById('map-canvas'), {
        center: pos,
        zoom: 18,
        disableDefaultUI: true,
        styles: styleMap
      });

      var goldStar = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: 'blue',
        fillOpacity: 1,
        scale: 10,
        strokeColor: 'white',
        strokeWeight: 3
      };

      myOptions = {
          zoom: 7,
          center: pos,
          disableDefaultUI: true,
          styles: styleMap
        }

      map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),
          // Instantiate a directions service.
          directionsService = new google.maps.DirectionsService,
          directionsDisplay = new google.maps.DirectionsRenderer({
            map: map
          })

      marker = new google.maps.Marker({
          position: map.getCenter(),
          map: map,
          icon: goldStar
          // draggable: true
      });
      markers1.push(marker);

      lat = parseFloat (lat);
      lng = parseFloat (lng);
      id = parseFloat (id);

      var pos1 = {
            lat: lat,
            lng: lng
          };

      myAjax(iconBase,id,position.lat,position.lon);

      calculateAndDisplayRoute(directionsService, directionsDisplay, pos, pos1);

      map.addListener('dragend', function() {
            // $( "#theContent" ).html('');
            // clearMarkers();
            // $('#loading').show();
            // console.log('drag end');
            myAjaxDrag(iconBase,id,position.lat,position.lon)

          });

      map.addListener('drag', function() {
        $( "#theContent" ).html('');
        $('#query').val('');
        // clearMarkers();
        // clearMarkers1();
        // console.log('drag start');
        });

  }

  function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
    directionsService.route({
      origin: pointA,
      destination: pointB,
      travelMode: google.maps.TravelMode.DRIVING
    }, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      } else {
        window.alert('Directions request failed due to ' + status);
      }
    });
  }

  function myAjax(iconBase,id,lat,lng){
    $('#loading').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
          $( "#theContent" ).html('');
           $.ajax({
            url: "map1",
            type: "POST",
            data: {
              "_token": CSRF_TOKEN,
              "id": id,
              "lat": lat,
              "lng": lng
            },
            success: function(data){
              // console.log(data);
              features = data;

              if(features.length == 0){
                  $('#libil').show();
                }else {
                   $('#libil').hide();
                }

               var str1 = "";
                features.forEach(function(feature) {

                var lat = parseFloat(feature.lat);
                var lng = parseFloat(feature.lng);

                var myLatLng = {lat: lat, lng: lng};

                var marker = new google.maps.Marker({
                  position: myLatLng,
                  map: map,
                  icon: iconBase,
                  title: feature.hotel_name
                });
                marker.addListener('click', function() {

                window.history.pushState('page2', 'Title', '?loc='+lat+','+lng+'&id='+feature.id);

                navigator.geolocation.getCurrentPosition(geoSuccess);
                  

                });
                markers.push(marker);

                str1 = str1.concat(feature.interface)

              });
              $('#loading').hide();
              $( "#theContent" ).html(str1);
                
            }        
       });
  }

  function myAjaxDrag(iconBase,id,lat,lng){
        $('#loading').show();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var id = getUrlParameter('id');
        var bottomCorner1 = map.getBounds().getSouthWest().lng();
        var bottomCorner2 = map.getBounds().getSouthWest().lat();
        var topCorner1 = map.getBounds().getNorthEast().lng()
        var topCorner2 = map.getBounds().getNorthEast().lat()
         $.ajax({
          url: "map2",
          type: "POST",
          data: {
              "bottomCorner1": bottomCorner1,
              "bottomCorner2": bottomCorner2,
              "topCorner1": topCorner1,
              "topCorner2": topCorner2,
              "id": id,
              "_token": CSRF_TOKEN,
              "lat": lat,
              "lng": lng

          },
          success: function(data){

            // console.log(data);

              var features = data;

              if(features.length == 0){
                  $('#libil').show();
              }else {
                 $('#libil').hide();
              var str1 = "";

              features.forEach(function(feature) {

              var lat = parseFloat(feature.lat);
              var lng = parseFloat(feature.lng);

              var myLatLng = {lat: lat, lng: lng};

              var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: iconBase,
                title: feature.hotel_name
              });
              marker.addListener('click', function() {
                // console.log(feature);
                window.history.pushState('page2', 'Title', '?loc='+lat+','+lng+'&id='+feature.id);
                navigator.geolocation.getCurrentPosition(geoSuccess);
                // getLocation1(lat,lng,id,feature);
              });
              markers.push(marker);

              str1 = str1.concat(feature.interface)

            });
             $('#loading').hide();
             $( "#theContent" ).append(str1);

            }
              
          }        
     });
  }

  function cabang(id,lat,lng){
    // directionsDisplay.setMap(null);
    var lat = parseFloat(lat);
    var lng = parseFloat(lng);
    var id = parseInt(id);

    window.history.pushState('page2', 'Title', '?loc='+lat+','+lng+'&id='+id);

    // navigator.geolocation.getCurrentPosition(geoSuccess);

    getLocation();
    
  }

  function clearMarkers() {
        setMapOnAll(null);
        markers = [];
      }

  function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
  }

  function clearMarkers1() {
        setMapOnAll1(null);
        markers1 = [];
      }

  function setMapOnAll1(map) {
    for (var i = 0; i < markers1.length; i++) {
      markers1[i].setMap(map);
    }
  }

  $( "#query" ).focus(function() {
      // alert( "Handler for .focus() called." );
      $('#libil').show();
      $('#query').val('');
      $('#theContent').html('');
  });

  $( "#query" ).focusout(function() {
      // alert( "Handler for .focus() called." );
      // $('#search_result').hide();
    });

  $( "#query" ).keyup(function() {
      clearMarkers();
      $('#theContent').html('');
      // alert( "Handler for .focus() called." );
      var query = $('#query').val();
      var iconBase = 'uploads/icon.png';
      $('#libil').hide();
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
            url: "map3",
            type: "POST",
            data: {
                "data": query,
                "_token": CSRF_TOKEN
            },
            success: function(data){

                features = data;
                if(features.length == 0){
                    $('#libil').show();
                }else {
                   $('#libil').hide();
                }
                 var str1 = "";
                features.forEach(function(feature) {

                var lat = parseFloat(feature.lat);
                var lng = parseFloat(feature.lng);

                var myLatLng = {lat: lat, lng: lng};

                var marker = new google.maps.Marker({
                  position: myLatLng,
                  map: map,
                  icon: iconBase,
                  title: feature.hotel_name
                });
                marker.addListener('click', function() {
                  // console.log(feature);
                  window.history.pushState('page2', 'Title', '?loc='+lat+','+lng+'&id='+feature.id);
                  getLocation1(lat,lng,id,feature);
                  

                });
                markers.push(marker);

                str1 = str1.concat(feature.interface)

              });
              $('#theContent').html('');
              $( "#theContent" ).append(str1);

            }        
       });

    });