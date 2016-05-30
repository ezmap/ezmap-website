<p class="lead">Contents</p>
<ul class="list-unstyled">
    <li><a href="#hdi1">...find out the coordinates of my shop/office/address?</a></li>
    <li><a href="#hdi2">...remove a broken marker icon?</a></li>
    <li><a href="#hdi3">...report a bug?</a></li>
    <li><a href="#hdi4">...design a map theme?</a></li>
</ul>
<hr>
<ul class="list-unstyled">
    <li id="hdi1">
        <p class="lead">...find out the coordinates of my shop/office/address?</p>
        <p>Find your place on the map by zooming in to the area where your place is. Once you've found it, zoom as close
            as the map allows, then click on the
            <ui-button raised color="primary" icon="add_location">
                Add a Marker
            </ui-button>
            button and drop your marker onto the building. Now click the
            <ui-icon-button color="accent" icon="my_location"></ui-icon-button>
            button next to your marker. The Latitude and Longitude boxes will now be filled with the coordinates of your
            place.
        </p>
        <hr>
    </li>
    <li id="hdi2">
        <p class="lead">...remove a broken marker icon?</p>
        <p>If you've put the wrong URL in the box so you're getting a broken image indicator rather than the image you
            wanted for your marker, click on the
            <ui-icon-button color="accent" icon="place"></ui-icon-button>
            button for that marker, then find your broken icon in the "Your Icons" list and hit the
            <ui-button color="danger" icon="delete">Delete</ui-button>
            button under the broken image indicator.
        </p>
        <hr>
    </li>
    <li id="hdi3">
        <p class="lead">...report a bug?</p>
        <p>If you think you might have found a bug, please fill in this form:</p>
        @include('partials.feedbackForm')
        <hr>
    </li>
    <li id="hdi4">
        <p class="lead">...design a map theme?</p>
        <p>The guys over at <a href="https://snazzymaps.com">Snazzy Maps</a> have a
            <a href="https://snazzymaps.com/editor">brilliant wizard style tool</a> to make designing your own themes
            extremely easy. I'd encourage you to use that. Once you've saved your map let us know and we'll import it
            for everyone to use here.</p>
        <p>Soon we'll be adding support to let anyone import their own Snazzy Maps "My Styles" and "Favorites" for their
            own use. (i.e your imported themes won't show in the main list but will be saved for your use here!)</p>
        <hr>
    </li>
</ul>