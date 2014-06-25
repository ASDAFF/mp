// page init
jQuery(function(){
	initRecoords();
});

// change coords map init
function initRecoords(){
	var beginCoords = myLatlng;

	jQuery('.coords').each(function(){
		var box = $(this),
			items = box.find('li'),
			links = items.find('.coords-btn')
		links.each(function(){
			var btn = jQuery(this);
			btn.click(function(){
				var coords = btn.attr('title');
				if(coords){
					var coordsSplit = btn.attr('title').split(',')
					posX = coordsSplit[0];
					posY = coordsSplit[1];
					myLatlng = new google.maps.LatLng(posX, posY);
					initialize();
					items.removeClass('active')
					btn.parent().addClass('active')
				} else {
					myLatlng = beginCoords;
					initialize();
				}
				return false;
			})
		})
	})
}
// Переменная для задания коордионат карты
var posX = 55.794673;
var posY = 37.538335;
var myLatlng = new google.maps.LatLng(posX, posY);
var marker;
var map;
var MY_MAPTYPE_ID = 'mystyle';

function initialize() {
	if($("#map_canvas").length==0) return;
	
	    var stylez = [{
        featureType: "all",
        elementType: "all",
        stylers: [{
            "hue": "#000000"
        }, {
            "saturation": -100
        }, {
            "visibility": "on"
        }, {
            "gamma": 0.56
        }]
    }];
	
	var myOptions = {
		zoom: 16,
		scrollwheel: false,
		center: myLatlng,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		mapTypeId: MY_MAPTYPE_ID
	}
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	    var styledMapOptions = {
        name: "white-black"
    };
	var jayzMapType = new google.maps.StyledMapType(stylez, styledMapOptions);
    map.mapTypes.set(MY_MAPTYPE_ID, jayzMapType);

	// опции маркера
	var iconBase = 'images/';
	// параметры для тени маркера

	var icons = {
		parking: {
			icon: iconBase + 'marker.png',
			shadow: iconBase + 'shadow-marker.png',
			name: 'Парковки'
		},
		library: {
			icon: iconBase + 'marker.png',
			shadow: iconBase + 'shadow-marker.png',
			name: 'Библиотеки'
		},
		info: {
			icon: iconBase + 'marker.png',
			shadow: iconBase + 'shadow-marker.png',
			name: 'Информация'
		}
	};

	function addMarker(feature) {
		marker = new google.maps.Marker({
			position: feature.position,
			icon: icons[feature.type].icon,
			draggable: false,
			// Анимация для маркера, может быть DROP или BOUNCE
			animation: google.maps.Animation.DROP,
			shadow: {
				url: icons[feature.type].shadow,
				anchor: new google.maps.Point(16, 54)
			},
			map: map
		});
	}

	var features = [{
			position: myLatlng,
			type: 'info'
		}
	];

	for (var i = 0, feature; feature = features[i]; i++) {
		addMarker(feature);
	}

	for (var key in icons) {
		var type = icons[key];
		var name = type.name;
		var icon = type.icon;
		var div = document.createElement('div');
		div.innerHTML = '<img src="' + icon + '"> ' + name;
	}

	google.maps.event.addListener(marker, 'click', toggleBounce);

	google.maps.event.addListener(marker, 'click', function () {
		infowindow.open(map, marker);
	});
}

// Функция анимации маркера
function toggleBounce() {
	if (marker.getAnimation() != null) {
		marker.setAnimation(null);
	} else {
		marker.setAnimation(google.maps.Animation.BOUNCE);
	}
}
google.maps.event.addDomListener(window, 'load', initialize);