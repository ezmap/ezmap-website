/**
 * EZ Map - Map Editor Alpine.js Component
 *
 * Provides all map editing functionality: marker management, heatmap management,
 * theme application, code generation, and Google Maps interaction.
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('mapEditor', (config) => ({
        // State
        addingPin: false,
        addingPinByAddress: false,
        addingHotSpot: false,
        apikey: config.apikey || '',
        codeCopied: false,
        currentTheme: config.currentTheme || { id: '0' },
        directionsDisplays: [],
        doubleClickZoom: config.doubleClickZoom ?? true,
        embeddable: config.embeddable ?? false,
        height: config.height ?? 420,
        heatmap: null,
        heatMapData: [],
        heatmapLayer: config.heatmapLayer || { dissipating: false, opacity: 0.6, radius: 2 },
        geocoder: {},
        infoDescription: '',
        infoEmail: '',
        infoTelephone: '',
        infoTitle: '',
        infoWebsite: '',
        infoTarget: false,
        lat: config.lat ?? 56.4778058625534,
        lng: config.lng ?? -2.86748333610688,
        map: {},
        mapcontainer: config.mapcontainer || 'ez-map',
        mapLoaded: false,
        mapTypeControlDropdown: config.mapTypeControlDropdown || [],
        mapTypes: config.mapTypes || [],
        mapTypeId: {},
        markers: [],
        joiningMarkers: false,
        joinStart: null,
        joinStop: null,
        responsive: config.responsive ?? true,
        show: true,
        width: config.width ?? 560,
        themeApplied: config.themeApplied ?? false,
        mapOptions: config.mapOptions || {},
        mapOpacity: 1,
        mapBackground: 'none',

        // For new icon form
        newIconName: '',
        newIconUrl: '',

        // For geocode address
        geocodeAddressText: '',

        // For hotspot prompt
        hotSpotTitle: '',
        hotSpotWeight: 1,
        pendingHotSpotEvent: null,

        // Marker being changed icon for
        iconChangeMarkerIndex: null,

        // Saved data references
        savedMarkers: config.savedMarkers || [],
        savedHeatmap: config.savedHeatmap || [],
        savedHeatmapLayer: config.savedHeatmapLayer || null,

        // Map routes
        mapId: config.mapId || null,
        storeUrl: config.storeUrl || '',
        imageUrl: config.imageUrl || '',
        kmlUrl: config.kmlUrl || '',
        kmzUrl: config.kmzUrl || '',
        addIconUrl: config.addIconUrl || '',
        deleteIconUrl: config.deleteIconUrl || '',
        csrfToken: config.csrfToken || '',

        // Debounce timer
        _resizeTimer: null,
        _centerTimer: null,
        _zoomTimer: null,

        // Getters (computed)
        get hasDirections() {
            return this.directionsDisplays.length > 0;
        },

        get styleObject() {
            if (this.responsive) {
                return {
                    height: this.height + 'px',
                    width: '100%'
                };
            }
            return {
                height: this.height + 'px',
                width: this.width + 'px'
            };
        },

        get markersToString() {
            const out = [];
            for (let i = 0; i < this.markers.length; i++) {
                const marker = this.markers[i];
                out.push({
                    title: marker.title,
                    icon: marker.icon,
                    lat: marker.position.lat(),
                    lng: marker.position.lng(),
                    infoWindow: {
                        content: marker.infoWindow.content || ''
                    }
                });
            }
            return JSON.stringify(out);
        },

        get heatMapToString() {
            return JSON.stringify(this.heatMapData);
        },

        get mapTypeControlStyle() {
            return this.mapOptions.mapTypeControlOptions ? this.mapOptions.mapTypeControlOptions.style : 0;
        },

        set mapTypeControlStyle(val) {
            if (!this.mapOptions.mapTypeControlOptions) {
                this.mapOptions.mapTypeControlOptions = {};
            }
            this.mapOptions.mapTypeControlOptions.style = parseInt(val);
            this.optionschange();
        },

        get selectedMapTypeId() {
            return this.mapTypeId.mapTypeId || 'roadmap';
        },

        set selectedMapTypeId(val) {
            this.mapTypeId = this.mapTypes.find(t => t.mapTypeId === val) || this.mapTypes[0];
            this.optionschange();
        },

        get generatedCode() {
            const opts = {
                center: { lat: this.lat, lng: this.lng },
                clickableIcons: this.mapOptions.clickableIcons,
                disableDoubleClickZoom: !this.doubleClickZoom,
                draggable: this.mapOptions.draggable,
                fullscreenControl: this.mapOptions.fullscreenControl,
                keyboardShortcuts: this.mapOptions.keyboardShortcuts,
                mapTypeControl: this.mapOptions.mapTypeControl,
                mapTypeControlOptions: { style: this.mapOptions.mapTypeControlOptions ? this.mapOptions.mapTypeControlOptions.style : 0 },
                mapTypeId: this.mapTypeId.mapTypeId || 'roadmap',
                rotateControl: true,
                scaleControl: this.mapOptions.scaleControl,
                scrollwheel: this.mapOptions.scrollwheel,
                streetViewControl: this.mapOptions.streetViewControl,
                styles: this.mapOptions.styles || false,
                zoom: this.mapOptions.zoom,
                zoomControl: this.mapOptions.zoomControl
            };

            // Remove styles if false
            const optsClean = { ...opts };
            if (!optsClean.styles) {
                delete optsClean.styles;
            }

            const optsJson = JSON.stringify(optsClean);
            const libs = this.heatMapData.length ? '&libraries=visualization' : '';

            let code = `<!-- Google map code from EZ Map - https://ezmap.co -->\n`;
            code += `<script src='https://maps.googleapis.com/maps/api/js?key=${this.apikey}${libs}'><\/script>\n`;
            code += `<script>\n`;
            code += `  function init() {\n`;
            code += `    var mapOptions = ${optsJson};\n`;
            code += `    var mapElement = document.getElementById('${this.mapcontainer}');\n`;
            code += `    var map = new google.maps.Map(mapElement, mapOptions);\n`;
            code += `    ${this.markersLoop()}${this.heatmapLoop()}\n`;
            code += `    ${this.responsiveOutput()}\n`;
            code += `  }\n`;
            code += `window.addEventListener('load', init);\n`;
            code += `<\/script>\n`;
            code += this.mapStyling() + '\n';
            code += `<div id='${this.mapcontainer}'></div>\n`;
            code += `<!-- End of EZ Map code - https://ezmap.co -->`;

            return code;
        },

        get generatedEmbedCode() {
            if (!this.mapId) return this.generatedCode;
            let code = `<!-- Google map code from EZ Map - https://ezmap.co -->\n`;
            code += `<script src="//ezmap.co/map/${this.mapId}" id="ez-map-embed-script-${this.mapId}"><\/script>\n`;
            code += `<!-- End of EZ Map code - https://ezmap.co -->`;
            return code;
        },

        get displayedCode() {
            if (this.embeddable && this.mapId) {
                return this.generatedEmbedCode;
            }
            return this.generatedCode;
        },

        // Methods
        init() {
            // Set mapTypeId from options
            const currentMapTypeId = this.mapOptions.mapTypeId || 'roadmap';
            this.mapTypeId = this.mapTypes.find(t => t.mapTypeId === currentMapTypeId) || this.mapTypes[0] || { mapTypeId: 'roadmap' };

            // Wait for Google Maps to load then init
            if (typeof google !== 'undefined' && google.maps) {
                this.initMap();
            } else {
                window._mapEditorInitCallback = () => this.initMap();
            }

            // Listen for theme selection from Livewire ThemeBrowser component
            window.addEventListener('theme-selected', (e) => {
                const { id, json } = e.detail;
                this.currentTheme = { id: String(id), json: json };
                this.mapOptions.styles = json;
                this.themeApplied = true;
                this.optionschange();
            });

            // Listen for marker icon clicks
            this.$el.addEventListener('click', (e) => {
                if (e.target.classList.contains('markericon')) {
                    this.setMarkerIcon(e);
                }
            });
        },

        showCenter() {
            this.mapOpacity = this.mapOpacity === 1 ? 0.5 : 1;
            const isDark = document.documentElement.classList.contains('dark');
            const crosshair = isDark ? '/images/crosshairs-white.svg' : '/images/crosshairs.svg';
            this.mapBackground = this.mapOpacity === 0.5 ? `transparent url(${crosshair}) center center no-repeat` : 'none';
            const mapEl = document.getElementById('map');
            if (mapEl && mapEl.firstChild) {
                mapEl.firstChild.style.opacity = this.mapOpacity;
            }
            if (mapEl) {
                mapEl.style.background = this.mapBackground;
            }
        },

        clearDirections() {
            for (let i = 0; i < this.directionsDisplays.length; i++) {
                this.directionsDisplays[i].setMap(null);
            }
            this.directionsDisplays = [];
        },

        beginJoin(markerIndex) {
            this.joiningMarkers = true;
            this.joinStart = this.markers[markerIndex];
        },

        endJoin(markerIndex) {
            this.joiningMarkers = false;
            this.joinStop = this.markers[markerIndex];
            this.calcRoute(this.joinStart, this.joinStop);
        },

        calcRoute(start, end) {
            const displayer = new google.maps.DirectionsRenderer({
                draggable: true,
                suppressMarkers: true,
                preserveViewport: true
            });
            const directionsService = new google.maps.DirectionsService();
            const request = {
                origin: start.position,
                destination: end.position,
                travelMode: google.maps.TravelMode.DRIVING
            };
            const self = this;
            directionsService.route(request, (response, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    displayer.setDirections(response);
                    displayer.setMap(self.map);
                    self.directionsDisplays.push(displayer);
                } else {
                    alert('Route failed: ' + status);
                }
            });
        },

        clearTheme() {
            this.mapOptions.styles = [];
            this.currentTheme = { id: '0' };
            this.themeApplied = false;
            this.optionschange();
        },

        markersLoop() {
            let str = '';
            for (let i = 0; i < this.markers.length; i++) {
                const marker = this.markers[i];
                str += 'var marker' + i + ' = new google.maps.Marker({title: "' + marker.title + '", icon: "' + marker.icon + '", position: new google.maps.LatLng(' + marker.position.lat() + ', ' + marker.position.lng() + '), map: map});\n';
                if (marker.infoWindow.content) {
                    str += 'var infowindow' + i + ' = new google.maps.InfoWindow({content: ' + JSON.stringify(marker.infoWindow.content) + ',map: map});\n';
                    str += 'marker' + i + ".addListener('click', function () { infowindow" + i + '.open(map, marker' + i + ') ;});infowindow' + i + '.close();\n';
                }
            }
            return str;
        },

        heatmapLoop() {
            if (this.heatMapData.length === 0) return '';
            let str = '';
            str += 'var heatmap = new google.maps.visualization.HeatmapLayer({data: [';
            for (let i = 0; i < this.heatMapData.length; i++) {
                str += '{ location: new google.maps.LatLng(' + this.heatMapData[i].weightedLocation.location.lat + ',' + this.heatMapData[i].weightedLocation.location.lng + '), weight: ' + this.heatMapData[i].weightedLocation.weight + '},';
            }
            str += ']});';
            str += 'heatmap.setOptions(' + JSON.stringify(this.heatmapLayer) + ');';
            str += 'heatmap.setMap(map);';
            return str;
        },

        responsiveOutput() {
            if (this.responsive) {
                return 'window.addEventListener("resize", function() { var center = map.getCenter(); google.maps.event.trigger(map, "resize"); map.setCenter(center); });';
            }
            return '';
        },

        mapStyling() {
            let str = '<style>\n  ';
            str += '#' + this.mapcontainer + ' { ';
            str += 'min-height:150px; min-width:150px; height: ' + this.styleObject.height + '; width: ' + this.styleObject.width + ';';
            str += ' }';

            if (this.markers.length) {
                str += '\n  #' + this.mapcontainer + ' .infoTitle { /*marker window title styles*/ }';
                str += '\n  #' + this.mapcontainer + ' .infoWebsite { /*marker window website styles*/ }';
                str += '\n  #' + this.mapcontainer + ' .infoEmail { /*marker window email address styles*/ }';
                str += '\n  #' + this.mapcontainer + ' .infoTelephone { /*marker window telephone styles*/ }';
                str += '\n  #' + this.mapcontainer + ' .infoDescription { /*marker window description styles*/ }';
            }
            str += '\n</style>';
            return str;
        },

        debouncedMapResized() {
            clearTimeout(this._resizeTimer);
            this._resizeTimer = setTimeout(() => this.mapresized(), 500);
        },

        mapresized() {
            if (!this.mapLoaded) {
                this.initMap();
                return;
            }
            google.maps.event.trigger(this.map, 'resize');
        },

        async copied() {
            const code = this.displayedCode;
            try {
                await navigator.clipboard.writeText(code);
            } catch (e) {
                // Fallback
                const tempTextArea = document.createElement('textarea');
                tempTextArea.textContent = code;
                document.body.append(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                tempTextArea.remove();
            }
            this.codeCopied = true;
            setTimeout(() => { this.codeCopied = false; }, 3000);
        },

        zoomchanged() {
            this.map.setZoom(parseInt(this.mapOptions.zoom));
        },

        debouncedCenterChanged() {
            clearTimeout(this._centerTimer);
            this._centerTimer = setTimeout(() => this.centerchanged(), 500);
        },

        centerchanged() {
            if (!this.mapLoaded) return;
            this.map.setCenter(new google.maps.LatLng(parseFloat(this.lat), parseFloat(this.lng)));
            this.optionschange();
        },

        mapmoved() {
            this.mapOptions.center = this.map.getCenter();
            this.lat = this.mapOptions.center.lat();
            this.lng = this.mapOptions.center.lng();
        },

        mapzoomed() {
            this.mapOptions.zoom = this.map.getZoom();
        },

        optionschange() {
            if (!this.mapLoaded) return;
            this.mapOptions.disableDoubleClickZoom = !this.doubleClickZoom;
            this.mapOptions.mapTypeId = this.mapTypeId.mapTypeId || 'roadmap';
            this.map.setOptions(this.mapOptions);
        },

        heatmapChange() {
            if (!this.mapLoaded) return;
            if (this.heatmap === null) {
                this.heatmap = new google.maps.visualization.HeatmapLayer([]);
            }
            const data = [];
            for (let i = 0; i < this.heatMapData.length; i++) {
                const location = this.heatMapData[i].weightedLocation.location;
                const weight = this.heatMapData[i].weightedLocation.weight;
                data.push({ location: new google.maps.LatLng(location.lat, location.lng), weight: weight });
            }
            this.heatmap.setData(data);
            this.heatmap.setOptions(this.heatmapLayer);
        },

        maptypeidchanged() {
            this.mapOptions.mapTypeId = this.map.getMapTypeId();
            this.mapTypeId = this.mapTypes.find(t => t.mapTypeId === this.mapOptions.mapTypeId) || this.mapTypes[0];
        },

        buildInfoWindowContent() {
            let html = '';
            if (this.infoTitle) {
                html += '<h3 class="infoTitle">' + this.escapeHtml(this.infoTitle) + '</h3>';
            }
            html += '<p>';
            if (this.infoWebsite) {
                const target = this.infoTarget ? ' target="_blank"' : '';
                html += '<span class="infoWebsite"><a' + target + ' href="' + this.escapeHtml(this.infoWebsite) + '">' + this.escapeHtml(this.infoWebsite) + '</a><br></span>';
            }
            if (this.infoEmail) {
                html += '<span class="infoEmail"><a href="mailto:' + this.escapeHtml(this.infoEmail) + '">' + this.escapeHtml(this.infoEmail) + '</a><br></span>';
            }
            if (this.infoTelephone) {
                html += '<span class="infoTelephone">' + this.escapeHtml(this.infoTelephone) + '</span>';
            }
            html += '</p>';
            if (this.infoDescription) {
                html += '<p class="infoDescription">' + this.escapeHtml(this.infoDescription).replace(/\n/g, '<br>') + '</p>';
            }
            return html;
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        addInfoBox() {
            const marker = this.markers[this.markers.length - 1];
            const content = this.buildInfoWindowContent();
            const infowindow = new google.maps.InfoWindow({ content: content });
            marker.infoWindow = infowindow;
            marker.title = this.infoTitle !== '' ? this.infoTitle : marker.title;
            marker.setTitle(marker.title);
            const map = this.map;
            marker.addListener('click', () => {
                infowindow.open(map, marker);
            });
            Flux.modal('marker-info').close();
            this.centerchanged();
        },

        dismissInfoBox() {
            // Just close without adding info window content
            Flux.modal('marker-info').close();
        },

        removeMarker(item) {
            this.markers[item].setMap(null);
            this.markers.splice(item, 1);
        },

        removeAllMarkers() {
            if (window.confirm('Are you sure you want to delete the ' + this.markers.length + ' markers from this map?')) {
                for (let i = 0; i < this.markers.length; i++) {
                    this.markers[i].setMap(null);
                }
                this.markers = [];
            }
        },

        removeAllHotSpots() {
            if (window.confirm('Are you sure you want to delete the ' + this.heatMapData.length + ' hotspots from this map?')) {
                this.heatMapData = [];
            }
            this.heatmapChange();
        },

        centerOnMarker(item) {
            this.map.setCenter(this.markers[item].position);
        },

        centerOnHotSpot(item) {
            const loc = this.heatMapData[item].weightedLocation.location;
            this.map.setCenter(new google.maps.LatLng(loc.lat, loc.lng));
        },

        changeMarkerIcon(item) {
            this.iconChangeMarkerIndex = item;
            // Update all markericon images with the correct data-for-marker
            document.querySelectorAll('.markericon').forEach(el => {
                el.dataset.forMarker = item;
            });
            Flux.modal('marker-pin').show();
        },

        setMarkerIcon(event) {
            const newIcon = event.target;
            if (!newIcon.classList.contains('markericon')) return;
            const markerIndex = this.iconChangeMarkerIndex !== null ? this.iconChangeMarkerIndex : parseInt(newIcon.dataset.forMarker);
            if (this.markers[markerIndex]) {
                this.markers[markerIndex].setIcon(newIcon.getAttribute('src'));
                this.markers[markerIndex].icon = newIcon.getAttribute('src');
            }
            Flux.modal('marker-pin').close();
        },

        showAddressModal() {
            this.addingPinByAddress = true;
            this.geocodeAddressText = '';
            Flux.modal('geocode-address').show();
            this.$nextTick(() => {
                const input = document.getElementById('geocodeAddress');
                if (input) input.focus();
            });
        },

        geocodeAddress() {
            const address = this.geocodeAddressText;
            if (!address) return;
            this.geocoder.geocode({ address: address }, (results, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    Flux.modal('geocode-address').close();
                    this.placeMarker({ latLng: results[0].geometry.location });
                    this.geocodeAddressText = '';
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        },

        placeMarker(event) {
            if (this.addingPin || this.addingPinByAddress) {
                const marker = new google.maps.Marker({
                    icon: 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png',
                    position: event.latLng,
                    map: this.map,
                    draggable: true,
                    title: 'No Title',
                    infoWindow: { content: '' },
                    startsRoutes: [],
                    endsRoutes: []
                });
                this.markers.push(marker);
                this.infoTitle = '';
                this.infoEmail = '';
                this.infoWebsite = '';
                this.infoTelephone = '';
                this.infoDescription = '';
                this.infoTarget = false;
                Flux.modal('marker-info').show();
                this.addingPin = this.addingPinByAddress = false;
            }
        },

        placeHotSpot(event) {
            if (this.addingHotSpot) {
                this.pendingHotSpotEvent = event;
                this.hotSpotTitle = event.latLng.toString();
                this.hotSpotWeight = 1;
                Flux.modal('hotspot-prompt').show();
            }
        },

        confirmHotSpot() {
            if (this.pendingHotSpotEvent) {
                this.heatMapData.push({
                    title: this.hotSpotTitle || this.pendingHotSpotEvent.latLng.toString(),
                    weightedLocation: {
                        location: this.pendingHotSpotEvent.latLng.toJSON(),
                        weight: Number(this.hotSpotWeight) || 1
                    }
                });
                this.pendingHotSpotEvent = null;
            }
            this.addingHotSpot = false;
            this.heatmapChange();
            Flux.modal('hotspot-prompt').close();
        },

        removeHotSpot(item) {
            this.heatMapData.splice(item, 1);
            this.heatmapChange();
        },

        duplicateMap() {
            const form = document.getElementById('mainForm');
            const titleInput = form.querySelector('input[name="title"]');
            if (titleInput) {
                titleInput.value = titleInput.value + ' - copy';
            }
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.value = 'POST';
            }
            form.action = this.storeUrl;
            form.submit();
        },

        openImage() {
            if (this.imageUrl) {
                window.open(this.imageUrl, '_blank');
            }
        },

        addSavedInfoWindow(marker, infoWindow) {
            const map = this.map;
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        },

        async addNewIcon() {
            if (!this.newIconName || !this.newIconUrl) return;
            try {
                const response = await fetch(this.addIconUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        newIconName: this.newIconName,
                        newIconURL: this.newIconUrl
                    })
                });
                if (response.ok) {
                    const msg = await response.json();
                    // Reload page to show new icon
                    window.location.reload();
                }
            } catch (e) {
                console.error('Failed to add icon:', e);
            }
        },

        initMap() {
            this.mapOptions.center = new google.maps.LatLng(this.lat, this.lng);
            this.map = new google.maps.Map(document.getElementById('map'), this.mapOptions);
            this.geocoder = new google.maps.Geocoder();
            this.mapLoaded = true;
            this.mapmoved();

            // Load saved markers
            if (this.savedMarkers && this.savedMarkers.length) {
                for (let i = 0; i < this.savedMarkers.length; i++) {
                    const savedMarker = this.savedMarkers[i];
                    const marker = new google.maps.Marker({
                        icon: savedMarker.icon,
                        position: new google.maps.LatLng(savedMarker.lat, savedMarker.lng),
                        map: this.map,
                        draggable: true,
                        title: savedMarker.title,
                        infoWindow: savedMarker.infoWindow
                    });
                    if (savedMarker.infoWindow && savedMarker.infoWindow.content) {
                        const infowindow = new google.maps.InfoWindow(savedMarker.infoWindow);
                        this.addSavedInfoWindow(marker, infowindow);
                    }
                    this.markers.push(marker);
                }
            }

            // Load saved heatmap data
            if (this.savedHeatmap && this.savedHeatmap.length) {
                this.heatMapData = this.savedHeatmap;
            }
            if (this.savedHeatmapLayer) {
                this.heatmapLayer = this.savedHeatmapLayer;
            }

            // Initialize heatmap layer
            this.heatmap = new google.maps.visualization.HeatmapLayer([]);
            this.heatmapChange();
            this.heatmap.setOptions(this.heatmapLayer);
            this.heatmap.setMap(this.map);

            // Map event listeners
            google.maps.event.addListener(this.map, 'resize', () => this.centerchanged());
            google.maps.event.addListener(this.map, 'center_changed', () => this.mapmoved());
            google.maps.event.addListener(this.map, 'zoom_changed', () => this.mapzoomed());
            google.maps.event.addListener(this.map, 'maptypeid_changed', () => this.maptypeidchanged());
            google.maps.event.addListener(this.map, 'click', (e) => this.placeMarker(e));
            google.maps.event.addListener(this.map, 'click', (e) => this.placeHotSpot(e));
        }
    }));
});
