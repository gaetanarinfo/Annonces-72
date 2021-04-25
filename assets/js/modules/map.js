var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

export default class Map {

    static init() {
        let map = document.querySelector('#map')
        if (map === null) {
            return
        }

        let centers = [map.dataset.lng, map.dataset.lat]

        if (centers !== null) {
            mapboxgl.accessToken = 'pk.eyJ1IjoibGVmb3JnZXVyNzIiLCJhIjoiY2ttdDBoYnNkMGl3dDJvbnNyeHF1ZXFqOCJ9.3pv-3De2cjyRbzGTNMC8qg';
            map = new mapboxgl.Map({
                container: map, // container id
                style: 'mapbox://styles/mapbox/streets-v11', // style URL
                center: centers, // starting position [lng, lat]
                zoom: 13 // starting zoom
            })
            map.loadImage(
                '/img/marker-icon.png',
                function(error, image) {
                    map.addImage('custom-marker', image);

                    map.addSource('points', {
                        'type': 'geojson',
                        'data': {
                            'type': 'FeatureCollection',
                            'features': [{
                                // feature for Mapbox DC
                                'type': 'Feature',
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': centers
                                }
                            }]
                        }
                    });

                    map.addLayer({
                        'id': 'points',
                        'type': 'symbol',
                        'source': 'points',
                        'layout': {
                            'icon-image': 'custom-marker',
                            // get the title name from the source's "title" property
                        }
                    });

                })
        }
    }

}