<?php
return [
    "help"   => "hauners",
    "intro"  => "Making Google Maps tae put on yer website or project has aye been a complete fanny. This tries tae help.",
    "intro2" => "This page should help ye wae a' ye need tae ken aboot EZ Map and its options.",

    "settings" => [
        "settings"       => "settins",
        "title"          => "If yer signed intae yer account, ye can gie yer map a name so ye can find it again in the dashboard.",
        "apiKey"         => [
            "text"     => "Users o the Google Map API are supposed tae use their ain API key. This is a load o nummers and letters that tells Google it's yer ain map and naebdy else's. If ye've got an API key ye can also stop ither fowk using yer map. Ye can get an API key",
            "linkText" => "ower here.",
            "warn1"    => "If yer map's gaun intae somehin that costs money, ye",
            "warn2"    => "HUV TAE",
            "warn3"    => "hae an API key. Ye should get in touch wae",
            "warn4"    => "for advice if yer no sure. If ye've no got an API key ye can still make maps but ye'll need tae watch how many fowk are seeing them coz they don't let too many see the wans wae nae API key.",
        ],
        "containerID"    => "This is jist the ID o the hing ye want yer map tae go intae. It's set up in the code ye get so unless ye've an'er element on yer page wi the same ID ye'll probly no need tae change this.",
        "dimensions"     => "This is the width and height o yer map in pixels. If ye want yer map tae be as wide as whatever bit o the page ye put it in, select the \"Responsive Width\" option. If ye choose the responsive option yer map will remain centered if the visitor re-sizes the page.",
        "latLong"        => "These numbers are the middle o yer map. If ye know the latitude and longitude ye want tae centre yer map at ye can fill this in. Otherwise, jist drag the map around and these numbers will update tae match the current middle.",
        "mapTypeControl" => "The Map Type Control is the control in the top-left o a map that determines the basic look o the map. ye can use the selector tae choose whether tae display this control as buttons or as a drapdoon.",
        "mapTypeIntro"   => "This is the type o map that's shown in the \"Map Type\" control on the map itself. ye can choose tae have one o the following map types:",
        "mapTypeOutro"   => "Ye can use this option tae change the type o map shown tae visitors, gid tae be able tae set this when ye remove the control fae the map itsel.",
        "zoomLevel"      => "This sets how zoomed in on the map ye are when it loads. Any whole nummer between 0 (zoomed right oot) and aroon 21, dependin on where in the world the map's lukkin at, can be used here. This reacts tae the current map zoom anaw so if ye don't know how zoomed in ye want, jist zoom the map itself an this'll update for ye.",
        "markers"        => [
            "intro"      => "Here ye can add markers (pins) tae yer map. Dae this tae add a marker:",
            "step1"      => [
                "clickThe" => "click the",
                "or"       => "or",
                "button"   => "button",
            ],
            "step2"      => [
                "drop"    => "If ye chose tae drop a marker, click on the map roughly where ye want the pin tae go.",
                "address" => "If ye chose tae add a marker by address, enter the address or postcode or whatever intae the box that appears, and click",
            ],
            "step3"      => [
                "dismiss" => "If ye don't want the marker tae have an information windae when sumbdy clicks the marker click",
                "confirm" => "Otherwise, fill in any fields in the form that appear that ye want (don't blether on). Anyhin no filled in wullny be used and each individual element gets it's ain class so ye can style these tae suit yersel. When you're happy wae the details click",
            ],
            "step4"      => [
                "text"    => "Yer pin will noo be on the map. ye can click it tae see how any info window looks and drag it around tae reposition it if it was wonky.",
                "infoBox" => "Although yer able tae drag markers on this map, the map produced for yer site wullny hae draggable markers. This is jist tae allow ye tae drag n drop markers tae get them exactly where ye want them. Top tip, try zooming right in tae where ye want yer marker before placing it, then zoom out for yer actual map.",
            ],
            "operations" => [
                "intro"       => "For each marker ye create ye'll see some more options. ye can dae this stuff on a marker.",
                "markerTitle" => "Jist type it's new name in the space and it'll be automatically updated. (The title shows when ye hover on a marker wae yer moose)",
                "changeIcon"  => "If ye click this button ye'll get an overlay wae a nummer o different icons ye can use for yer marker. If yer logged in, ye can also save yer ain icon, an that'll be available tae jist select for a' yer maps. Jist click on an icon tae use it for that marker.",
                "centerHere"  => "Clicking this button'll centre the map on that marker's position. If ye resize the map or move it aroon ye might need tae click this button again tae re-center it here before copying yer code.",
                "delete"      => "Finally ye can delete the marker a'thegether by clicking this button for that marker.",
            ],

        ],
        "other"          => [
            "mapTypeControl"  => "Ye can use this toggle tae remove the map type control fae the map a'thegether.",
            "fullscreen"      => "This is a wee button on the top-right o the map that allows sumbdy tae make the map full-screen on their device.",
            "streetview"      => "Shows or hides the streetview option (the wee yella guy) on the bottom-right o the map.",
            "streetviewInfo"  => "If ye are in streetview mode when ye save a map, it'll no be in streetview mode in yer code, ye will get the map as it was before entering streetview. So don't bother wae it.",
            "zoom"            => "The zoom control is the add sign and take away sign on the bottom right o a map. Turning this aff'll get rid ae they buttons fae the map. Zooming will still be available by way o the other zoom options (Keyboard Controls, Doubleclick Zoom and Scrollwheel Zoom) unless these are aff anaw.",
            "scale"           => "This is a small part on the bottom o the map showing the scale o the map at the current zoom level for that area, clicking it changes the scale fae metric tae imperial units.",
            "mapmaker"        => "These are jist a different set o images that make up the base-map. If ye are trying tae hide place names, try these tiles. ye can click through tae Google's Map Maker by clicking the link tae see what that's a' aboot.",
            "draggable"       => "If this isnae selected the person will no be able tae drag the map aroon.",
            "doubleclickzoom" => "Ye can double-click yer moose on the map tae zoom in (and haud ctrl and double-click tae zoom oot). Turnin' this aff stops this functionality on yer map.",
            "scrollwheel"     => "Ye can use the scroll function o yer moose tae zoom in and out o a map. Turning aff this option stops this functionality.",
            "keyboard"        => [
                "linktext" => "Ye can control Google Maps navigation by using the keyboard.",
                "deselect" => "Deselecting this option disables that functionality on yer map.",
            ],
            "poi"             => "At some zoom levels some local attractions or places o interest will show up. These huv wee information windaes attached tae them by default that'll show up when ye click on the place. Deselecting this stops these places fae being clickable on yer map. Course ye can still place a marker anywhere ye want an information window tae appear.",
        ],
        "saveMap"        => [
            "intro"        => "Ye can click this button tae save yer map tae yer account. It'll then be available for ye tae re-visit and edit.",
            "info"         => "Ye can only save a map when yer signed in.",
            "otherButtons" => "If yer working on a saved map ye'll also hae these buttons",
            "clone"        => "Cloning a map means making an exact copy o the map and saving it as a new map. This can be useful if ye've set up a map tae be yer base map setup that ye use tae make yer other maps fae. Jist clone the base and work fae there.",
            "delete"       => "Deleting a map simply removes a' records o the map fae yer account. Any marker icons saved during this maps's creation are not removed fae yer account so ye can use them again in future maps.",
        ],
    ],
    "howDoI"   => [
        "howDoI"           => "how dae ah",
        "findCoordinates"  => "find oot the coordinates o my shop/office/address?",
        "removeBrokenIcon" => "remove a buggered marker icon?",
        "reportBug"        => "tell ye there's a bug?",
        "designTheme"      => "design a map theme?",
        "styleInfoWindow"  => "style ma markers' info windaes?",
        "coordSteps"       => [
            "step1" => "Zoom in on the map then click this button and position yer marker on the location ye want.",
            "step2" => "Now click the centre here button next tae yer marker. The Latitude and Longitude boxes will noo be filled wi' the coordinates o yer place.",
        ],
        "brokenSteps"      => [
            "intro" => "If ye've put the wrong URL in the box so you're getting a buggered image indicator rather than the image ye wanted for yer marker, follow these steps",
            "step1" => "Click this button for the marker, then find yer broken icon in the list.",
            "step2" => "Click this button under the broken image indicator tae delete it. Happy days.",
        ],
        "bugIntro"         => "If ye think ye've fun a bug, please fill in this form:",
        "design"           => "have a belter o a tool tae make designing yer ain themes nae bother.",
        "style"            => [
            "intro"          => "ye can add the following rules tae yer CSS. (If yer no using the automatic update code ye'll see this in yer code output)",
            "description"    => "this is where ye can style yer elements tae suit yer page. If ye do nothin they'll take on yer default styling for the type o element they are:",
            "containsAnchor" => "hus an 'a' element in it anaw",
        ],
    ],

    "theme" => [
        "theme"        => "theme",
        "from"         => "themes fae",
        "clickToApply" => "Click on a theme's image tae apply the theme tae yer map",
        "showing"      => "showing",
        "of"           => "o", // showing 24 ...OF... 150 themes
        "show"         => "show",
        "all"          => "all",
        "tagged"       => "tagged",
        "color"        => "colour",
        "brilliant"    => "The brilliant fowk at",
        "available"    => "have made their theme repository available tae me for ye tae use on yer maps.",
        "currently"    => "There are currently",
        "tryIt"        => "If ye want tae try a theme on yer map jist click the preview icon for it and yer map will be instantly updated.",
        "clearIt"      => "Ye can click this button near yer map preview if ye want tae remove a theme and go back tae the Google Map defaults. This button only appears if ye've got a currently applied theme.",
    ],


];