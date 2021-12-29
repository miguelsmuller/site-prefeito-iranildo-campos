/*global google:false, common_params:false*/

function initialize() {
	/***************** DEFINE MAP STYLE ******************/
	var mapStyle = [{
        'featureType': 'all',
        'elementType': 'all',
        'stylers': [
            {
                'saturation': '-100'
            },
            {
                'lightness': '60'
            },
            {
                'gamma': '0.30'
            }
        ]
	}];

  /***************** DEFINE MAP OPTIONS ******************/
  var mapOptions = {
    styles: mapStyle,
    zoom: 17,
    scrollwheel: false,
    mapTypeControl: false,
    streetViewControl: false,
    center: new google.maps.LatLng(-22.787319, -43.380682),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  /***************** ADD OPTION TO MAP ******************/
  var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);


  /***************** CREATE A MARKER ******************/
  var iconMarker = common_params.site_url + '/wp-content/themes/iranildo-theme/assets/images/dist/icon-marker.png';
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(-22.787319, -43.380682),
    title: 'Devim',
    icon: iconMarker
  });
  marker.setMap(map);
}

initialize();
