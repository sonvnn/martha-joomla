EasyBlog.require()
.library('leaflet', 'leaflet-providers')
.done(function($) {
	osm = L.map('map-<?php echo $uid; ?>', {
		zoom: 12
	});

	osm.fitWorld();

	L.tileLayer.provider('Wikimedia').addTo(osm);

	var latlng = {
		lat: parseFloat(<?php echo $post->latitude; ?>),
		lng: parseFloat(<?php echo $post->longitude; ?>)
	}

	// if (marker !== undefined) {
	// 	osm.removeLayer(marker);
	// }

	osm.flyTo(latlng, 10, {
		"duration": 3
	});

	marker = L.marker(latlng).addTo(osm);
});
