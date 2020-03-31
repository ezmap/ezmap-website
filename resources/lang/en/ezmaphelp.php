<?php
return [
    "help"   => "help",
    "intro"  => "Making Google Maps for your website or page has traditionally been a complete pain. This tool takes that pain away.",
    "intro2" => "This page should help you with all you need to know about EZ Map and its options.",

    "settings" => [
        "settings"       => "settings",
        "title"          => "If you are signed in to your account, you can give your map a title so you can find it again in your dashboard.",
        "apiKey"         => [
            "text"     => "Users of the Google Map API are supposed to use their own API key. This is a series of numbers and letters that identifies the map as belonging to you. If you have an API key you can also restrict the use of your maps to only your own domain(s). You can get an API key",
            "linkText" => "at this page.",
            "warn1"    => "If your map will feature in a commercial product you",
            "warn2"    => "MUST",
            "warn3"    => "use an API key. You should contact the",
            "warn4"    => "people for advice. If you do not have an API key you can still make maps but there will be restrictions on how many times your maps can be viewed each day.",
        ],
        "containerID"    => "This is just the ID of the element you want your map to appear in. It's produced in the code given so unless you have another element on your page with the same ID you don't probably need to change this.",
        "dimensions"     => "This is the width and height of your map in pixels. If you want your map to be as wide as whatever bit of the page you put it in, select the \"Responsive Width\" option. If you choose the responsive option your map will remain centered if the visitor re-sizes the page.",
        "latLong"        => "These numbers are the center point of your map. If you know the latitude and longitude you want to center your map at you can fill this in. Otherwise, just drag the map around and these numbers will update to match the current center position.",
        "mapTypeControl" => "The Map Type Control is the control in the top-left of a map that determines the basic look of the map. You can use the selector to choose whether to display this control as buttons or as a dropdown.",
        "mapTypeIntro"   => "This is the type of map that's shown in the \"Map Type\" control on the map itself. You can choose to have one of the following map types:",
        "mapTypeOutro"   => "You can use this option to change the type of map shown to visitors, especially handy if removing the control from the map itself.",
        "zoomLevel"      => "This sets how zoomed in on the map you are when it loads. Any whole number between 0 (zoomed right out) and around 21, depending on the particular map position can be used here. This reacts to the current map zoom too so if you don't know how zoomed in you want, just zoom the map itself an this field will update for you.",
        "markers"        => [
            "intro"      => "Here you can add markers (pins) to your map. The sequence to add a marker is:",
            "step1"      => [
                "clickThe" => "click the",
                "or"       => "or",
                "button"   => "button",
            ],
            "step2"      => [
                "drop"    => "If you chose to drop a marker, click on the map roughly where you want the pin to go.",
                "address" => "If you chose to add a marker by address, enter the adddress or postcode etc into the box that appears, and click",
            ],
            "step3"      => [
                "dismiss" => "If you don't want the marker to have an information window when someone clicks the marker click",
                "confirm" => "Otherwise, fill in any fields in the form that appear that you want (keep it short-ish). Anything not filled in won't be used and each individual element gets it's own class so you can style these to suit the page you'll be putting the finished map if you want. When you're happy with the details click",
            ],
            "step4"      => [
                "text"    => "Your pin will now be on the map. You can click it to see how any info window looks and drag it around to reposition it if it was a bit off.",
                "infoBox" => "Although you are able to drag markers on this map, the map produced for your site will not have draggable markers. This is purely to allow you to drag n drop markers to get them exactly where you want them. Top tip, try zooming right in to where you want your marker before placing it, then zoom out for your actual map.",
            ],
            "operations" => [
                "intro"       => "For each marker you create you'll see some more options. You can do the following operations on a marker.",
                "markerTitle" => "Just type it's new name in the space and it'll be automatically updated. (The title shows when you hover on a marker on the map)",
                "changeIcon"  => "If you click this button you'll be presented with an overlay containing a number of different icons you can use for your marker. If you are logged in, you can also save your own icon, which will be available to just select for all your maps. Just click on an icon to use it for that marker.",
                "centerHere"  => "Clicking this button will center the map on that marker's position. If you resize the map or move it around you may need to click this button again to re-center it here before copying your code.",
                "delete"      => "Finally you can delete the individual marker altogether by clicking this button for that marker.",
            ],

        ],
        "other"          => [
            "mapTypeControl"  => "You can use this toggle to remove the map type control from the map altogether.",
            "fullscreen"      => "This is a small button on the top-right of the map that allows a person to make the map full-screen on their device.",
            "streetview"      => "Shows or hides the streetview option on the bottom-right of the map.",
            "streetviewInfo"  => "If you are in streetview mode when you save a map, this won't be shown, you will get the map as it was before entering streetview.",
            "zoom"            => "The zoom control is the plus and minus on the bottom right of a map. Deselecting this option will remove those buttons from the map. Zooming will still be available by way of the other zoom options (Keyboard Controls, Doubleclick Zoom and Scrollwheel Zoom) unless these are also turned off.",
            "scale"           => "This is a small part on the bottom of the map showing the scale of the map at the current zoom level for that area, clicking it changes the scale from metric to imperial units.",
            "draggable"       => "If this is deselected the person will not be able to drag the map around.",
            "doubleclickzoom" => "You can double-click your mouse on the map to zoom in (and hold ctrl and double-click to zoom out). Deselecting this option stops this functionality on your map.",
            "scrollwheel"     => "You can use the scroll function of your mouse to zoom in and out of a map. Turning off this option stops this functionality.",
            "keyboard"        => [
                "linktext" => "You can control Google Maps navigation by using the keyboard.",
                "deselect" => "Deselecting this option disables that functionality on your map.",
            ],
            "poi"             => "At some zoom levels some local attractions or places of interest will show up. These have little information windows attached to them by default that will show up when you click on the place. Deselecting this stops these places from being clickable on your map. Of course you can still place a marker anywhere you want an information window to appear.",
        ],
        "saveMap"        => [
            "intro"        => "You can click this button to save your map to your account. It'll then be available for you to re-visit and edit.",
            "info"         => "You can only save a map when signed in.",
            "otherButtons" => "If you are working on a saved map you will also have these buttons",
            "clone"        => "Cloning a map means making an exact copy of the map and saving it as a new map. This can be useful if you've set up a map to be your base map setup that you use to make your other maps from. Just clone the base and work from there.",
            "getImage"     => "Getting a static image is a way to save your map as a non-interactive image, perhaps for use in a piece of print work or some other image use.",
            "delete"       => "Deleting a map simply removes all records of the map from your account. Any marker icons saved during this maps's creation are not removed from your account so you can use them again in future maps.",
            "getImageInfo" => "You can only get an image if your map has an API key saved.",

        ],
    ],
    "howDoI"   => [
        "howDoI"           => "how do I",
        "findCoordinates"  => "find out the coordinates of my shop/office/address?",
        "removeBrokenIcon" => "remove a broken marker icon?",
        "reportBug"        => "report a bug?",
        "designTheme"      => "design a map theme?",
        "styleInfoWindow"  => "style my markers' info windows?",
        "saveAsImage"      => "save my map as a static image?",
        "coordSteps"       => [
            "step1" => "Zoom in on the map then click this button and position your marker on the location you want.",
            "step2" => "Now click the center here button next to your marker. The Latitude and Longitude boxes will now be filled with the coordinates of your place.",
        ],
        "brokenSteps"      => [
            "intro" => "If you've put the wrong URL in the box so you're getting a broken image indicator rather than the image you wanted for your marker, follow these steps",
            "step1" => "Click this button for the marker, then find your broken icon in the list.",
            "step2" => "Click this button under the broken image indicator to delete it.",
        ],
        "bugIntro"         => "If you think you might have found a bug, please fill in this form:",
        "design"           => "have a brilliant wizard style tool to make designing your own themes simple.",
        "style"            => [
            "intro"          => "You can add the following rules to your CSS. (If you are not using the automatic update code you'll see this in your code output)",
            "description"    => "this is where you can style your elements to suit your page. If you do nothing they'll take on your default styling for the type of element they are:",
            "containsAnchor" => "contains an 'a' element",
        ],
    ],

    "theme" => [
        "theme"        => "theme",
        "from"         => "themes from",
        "clickToApply" => "Click on a theme's image to apply the theme to your map",
        "showing"      => "showing",
        "of"           => "of", // showing 24 ...OF... 150 themes
        "show"         => "show",
        "all"          => "all",
        "tagged"       => "tagged",
        "color"        => "color",
        "brilliant"    => "The brilliant people at",
        "available"    => "have made their theme repository available to me for you to use on your maps.",
        "currently"    => "There are currently",
        "tryIt"        => "If you want to try a theme on your map just click the preview icon for it and your map will be instantly updated.",
        "clearIt"      => "You can click this button near your map preview if you want to remove a theme and go back to the Google Map defaults. This button only appears if you have a currently applied theme.",
    ],


];
