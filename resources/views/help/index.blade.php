@extends('layouts.master')
@section('title', 'EZ Map - Help')

@section('appcontent')
    <div class="col-sm-8 col-sm-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">Help</div>
            <div class="panel-body">
                <p>Making Google Maps for your website or page has traditionally been a complete pain. This tool takes
                   that pain
                   away.</p>
                <p>This page should help you with all you need to know about EZ Map and its options.</p>
                <p class="lead">Contents</p>
                <ol>
                    <li>Settings
                        <ul class="list-unstyled">
                            <li><a href="#faq0">Map Title</a></li>
                            <li><a href="#faq1">API Key?</a></li>
                            <li><a href="#faq2">Map Container ID</a></li>
                            <li><a href="#faq3">Dimensions</a></li>
                            <li><a href="#faq4">Latitude & Longitude</a></li>
                            <li><a href="#faq5">Map Type Control</a>
                                <ul>
                                    <li style="list-style:none;"><a href="#faq5.1">Map Type</a></li>
                                </ul>
                            </li>
                            <li><a href="#faq6">Zoom Level</a></li>
                            <li><a href="#faq7">Markers</a></li>
                            <li><a href="#faq8">Other Options</a></li>
                            <li><a href="#faq9">Save Map</a></li>
                        </ul>
                    </li>
                </ol>
                <hr class="brand-shadow">
                <div id="faq0">
                    <p class="lead">Map Title</p>
                    <p>If you are signed in to your account, you can give your map a title so you can find it again in
                       your dashboard.</p>
                    <p class="alert alert-warning">You must be signed in to save maps.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq1">
                    <p class="lead">What's an API Key?</p>
                    <p>Users of the Google Map API are supposed to use their own API key. This is simply a series of
                       numbers and letters that identifies the map as belonging to you.</p>
                    <p>If you do not have an API key you can still make maps but there will be restrictions on how many
                       times your maps can be viewed each day.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq2">
                    <p class="lead">Map Container ID</p>
                    <p>This is simply the ID of the element you want your map to appear in. It's produced in the code
                       given so unless you have another element on your page with the same ID you don't need to change
                       this.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq3">
                    <p class="lead">Dimensions</p>
                    <p>This is the width and height of your map in pixels. If you want your map to be as wide as
                       whatever bit of the page you put it in, check the "Responsive Width" checkbox. If you choose the
                       responsive option your map will remain centered if the visitor re-sizes the page.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq4">
                    <p class="lead">Latitude & Longitude</p>
                    <p>These numbers are the center of your map. If you know the latitude and longitude you want to
                       center your map at you can fill this in. Otherwise, just drag the map around and these numbers
                       will update to match the current position.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq5">
                    <p class="lead">Map Type Control</p>
                    <img class="img img-thumbnail pull-right brand-shadow" src="images/map-type-control.png" alt="map type control">
                    <p>The "Map Type" Control is the control in the top-left of a map that determines the basic look of
                       the map. You can use this checkbox to remove the control from the map altogether.
                    </p>
                    <p>Alternatively you can use the selector to choose whether to display this control as buttons or as
                       a dropdown.</p>
                    <div class="clearfix"></div>
                    <div id="faq5.1" class="col-xs-offset-1">
                        <p class="lead">Map Type</p>
                        <p>This is the type of map that's shown in the "Map Type" control on the map itself. You can
                           choose to have one of the following map types:
                        <ul>
                            <li>Road Map</li>
                            <li>Road Map with terrain</li>
                            <li>Satellite</li>
                            <li>Satellite with labels</li>
                        </ul>
                        You can use this option to change the type of map shown to visitors, especially handy if
                        removing the control from the map itself.
                        </p>
                    </div>
                    <hr class="brand-shadow">
                </div>
                <div id="faq6">
                    <p class="lead">Zoom Level</p>
                    <p>This sets how zoomed in on the map you are when it loads. Any whole number between 0 (zoomed
                       right out) and 21 (zoomed right in) can be used here. This reacts to the current map zoom too so
                       if you don't know how zoomed in you want, just zoom the map itself an this field will update for
                       you.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq7">
                    <p class="lead">Markers</p>
                    <p>Here you can add markers (pins) to your map. The sequence to add a marker is thus:</p>
                    <ol>
                        <li>
                            <p>Click the
                                <button class="btn btn-primary btn-xs">Add a Marker</button>
                               button
                            </p>
                        </li>
                        <li><p>Click on the map roughly where you want the pin to go.</p></li>
                        <li>
                            <ul>
                                <li>
                                    <p>If you don't want the marker to have an information window when someone clicks
                                       the
                                       marker click
                                        <button type="button" class="btn btn-default btn-xs">I Don't Need This</button>
                                    </p>
                                </li>
                                <li>
                                    <p>Otherwise, fill in any fields in the form that appear that you want (keep it
                                       short-ish). Anything not filled in won't be used and each individual element gets
                                       it's own class so you can style these to suit the page you'll be putting the
                                       finished map if you want. When you're happy with the details click
                                        <button class="btn btn-primary btn-xs">Add Info Box</button>
                                    </p>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <p>Your pin will now be on the map. You can click it to see how any info window looks and
                               drag it around to reposition it if it was a bit off. You will also see a
                                <button class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i> Delete All Markers?
                                </button>
                               button. Clicking this will remove every marker you have created from the map in one go.
                            </p>
                            <p>For each marker you create you'll see some more options. You can do the following
                               operations on a marker.</p>
                            <ul>
                                <li>
                                    <p>Marker Title - Just type it's new name <input type="text" value="in the box"> and
                                       it'll be automatically updated. (The title shows when you hover on a marker on
                                       the map)</p>
                                </li>
                                <li>
                                    <p>Change Icon - If you click
                                        <button class="btn btn-info btn-xs">
                                            <i class="fa fa-map-marker fa-fw"></i>
                                        </button>
                                       you'll be presented with an overlay containing a number of different icons you
                                       can use for your marker. If you are logged in, you can also save your own icon,
                                       which will be available to just select for all your maps. Just click on an icon
                                       to use it for that marker.
                                    </p>
                                </li>
                                <li>
                                    <p>Center Here - Clicking the
                                        <button class="btn btn-info btn-xs">
                                            <i class="fa fa-crosshairs fa-fw"></i>
                                        </button>
                                       button will center the map on that marker's position. If you resize the map or
                                       move it around you may need to click this button again to re-center it here
                                       before copying your code.
                                    </p>
                                </li>
                                <li>
                                    <p>Finally you can delete the individual marker altogether by clicking the
                                        <button class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash fa-fw"></i>
                                        </button>
                                       for that marker.
                                    </p>
                                </li>
                            </ul>

                        </li>
                    </ol>
                    <hr class="brand-shadow">
                </div>
                <div id="faq8">
                    <p class="lead">Other Options</p>
                    <ul class="list-unstyled">
                        <li><h4>Streetview Control</h4>
                            <p>Shows or hides the streetview option on the bottom-right of the map.</p>
                            <p class="alert alert-warning">If you are in streetview mode when you save a map,
                                                           this won't be shown,
                                                           you
                                                           will get the map as it was before entering
                                                           streetview.
                            </p>
                        </li>
                        <li>
                            <h4>Use "Map Maker" Tiles</h4>
                            <p>These are just a different set of images that make up the
                               base-map. If you are trying to hide place names, try these tiles. You can click through
                               to Google's Map Maker by clicking the link to see what's going on there.</p></li>

                        <li>
                            <h4>Scale Control</h4>
                            <img class="img img-thumbnail pull-right brand-shadow" src="images/scale-control.png" alt="map type control">
                            <p>This is a small part on the bottom of the map showing the scale of the map at the current
                               zoom level for that area, clicking it changes the scale from metric to imperial
                               units.</p>
                            <div class="clearfix"></div>
                        </li>
                        <li>
                            <h4>Fullscreen Control</h4>
                            <p>
                                This is a small button on the top-right of the map that allows a
                                person to make the map full-screen on their device.
                            </p>
                        </li>
                        <li>
                            <h4>Draggable Map</h4>
                            <p>If this is un-checked the person will not be able to drag the map
                               around.</p>
                        </li>
                        <li>
                            <h4>Keyboard Shortcuts</h4>
                            <p>You can control Google Maps navigation
                                <a href="https://sites.google.com/a/umich.edu/going-google/accessibility/google-maps-keyboard-shortcuts" target="_blank">
                                    by using the keyboard</a>. Un-checking this box disables that functionality on your
                               map.</p>
                        </li>
                        <li>
                            <img class="img img-thumbnail pull-right brand-shadow" src="images/clickable-points-of-interest.png" alt="map type control">
                            <h4>Clickable Points of Interest</h4>
                            <p>At some zoom levels some local attractions or places of interest will show up. </p>
                            <p>These have little information windows attached to them by default
                               that will show up when you click on the place. Un-checking this box stops these places
                               from being clickable on your map.</p>
                            <p>Of course you can still place a <a href="#faq7">marker</a> on anywhere you
                               want an information window to appear.</p>
                            <div class="clearfix"></div>
                        </li>
                        <li>
                            <h4>Zoom Control</h4>
                            <p>The zoom control is the plus and minus on the bottom right of a map. Un-checking this
                               option will remove those buttons from the map.</p>
                            <p>Zooming will still be available by way of the other zoom options (Keyboard Controls,
                               Doubleclick Zoom and Scrollwheel Zoom) unless these are also turned off.</p>
                        </li>
                        <li>
                            <h4>Doubleclick Zoom</h4>
                            <p>You can double-click your mouse on the map to zoom in (and hold <kbd>ctrl</kbd> and
                               double-click to zoom out). Un-checking this option stops this functionality on your map.
                            </p>
                        </li>
                        <li>
                            <h4>Scrollwheel Zoom</h4>
                            <p>You can use the scroll function of your mouse to zoom in and out of a map. Turning
                               off this option stops this functionality.</p>
                        </li>
                    </ul>
                    <hr class="brand-shadow">

                </div>
                <div id="faq9">
                    <p class="lead">Save Map</p>
                    <p>You can click the
                        <button class="btn btn-primary btn-xs"><i class="fa fa-save"></i> Save Map</button>
                       button to save your map to your account. It'll then be available for you to
                       re-visit and edit.
                    </p>
                    <p class="alert alert-warning">You can only save a map when signed in.</p>

                    <p>If you are working on a saved map you will also have the options to
                        <button class="btn btn-primary btn-xs">
                            <i class="fa fa-clone"></i> Clone Map
                        </button>
                       or
                        <button class="btn btn-danger btn-xs">
                            <i class="fa fa-trash"></i> Delete Map
                        </button>
                    </p>
                    <p>Cloning a map means making an exact copy of the map and saving it as a new map. This can be
                       useful if you've set up a map to be your "base" map setup that you use to make your other maps
                       from. Just clone the base and work from there.
                    </p>
                    <p>Deleting a map simply removes all records of the map from your account. Any marker icons saved
                       during this maps's creation are not removed from your account so you can use them again in future
                       maps.</p>
                    <hr class="brand-shadow">
                </div>
                <div id="faq10">
                    <p class="lead">Themes</p>
                    <p>The brilliant people at <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a> have made their theme
                       repository available to us for you to use on your maps. We currently have 1000 themes in our
                       platform but if you see one on <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a> that we don't
                       have, let us know and we'll add it.</p>
                    <p>If you want to try a theme on your map just click the preview icon for it and your map will be
                       instantly updated. If you want to remove a theme back to the Google Map defaults you can click
                        <button class="btn btn-primary btn-xs">Clear Applied Theme</button>
                       in "Other Options". This button only appears if you have a currently applied
                       theme.
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    Vue.filter('nl2br', function (value) {
        return value.replace(/\n/g, '<br>');
    });
    Vue.filter('jsonShrink', function (value) {
        return value.replace(/\n/g, '');
    });
    var helpVue = new Vue({
        el: '#app',
        data: {}
    });
</script>
@endpush