<div id="markerInfoWindow"><h3 v-if="infoTitle" class="infoTitle">@{{ infoTitle }}</h3><p><span v-if="infoWebsite" class="infoWebsite"><a href="@{{ infoWebsite }}">@{{ infoWebsite }}</a><br></span><span v-if="infoEmail" class="infoEmail"><a href="mailto:@{{ infoEmail }}">@{{ infoEmail }}</a><br></span><span v-if="infoTelephone" class="infoTelephone">@{{ infoTelephone }}</span></p><p v-if="infoDescription" class="infoDescription">@{{{ infoDescription | nl2br }}}</p></div>