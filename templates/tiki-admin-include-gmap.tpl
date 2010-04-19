{remarksbox type="note" title="{tr}Note{/tr}"}
{tr}To use Google Maps, you must generate a Google Maps API Key for your web site. See <a href="http://code.google.com/intl/fr/apis/maps/signup.html">http://code.google.com/intl/fr/apis/maps/signup.html</a> for details.{/tr}
{/remarksbox}


<form action="tiki-admin.php?page=gmap" method="post">
	<input type="hidden" name="gmapsetup" value="" />
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>

	<fieldset class="admin">
		<legend>{tr}Settings{/tr}</legend>
		<div class="adminoptionbox">
			<div class="adminoptionlabel">
				{preference name=gmap_key}
			</div>
		</div>
		<fieldset>
			<legend>{tr}Defaults{/tr}</legend>
				{preference name=gmap_defaultx}
				{preference name=gmap_defaulty}
				{preference name=gmap_defaultz}
		</fieldset>	
	</fieldset>	
	<div class="heading input_submit_container" style="text-align: center">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>
</form>

{if $prefs.feature_gmap eq 'y' and $show_map eq 'y'}
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$prefs.gmap_key}"></script>
<div class="wikitext">
	<div id="map" style="width: 500px; height: 400px;border: 1px solid #000;"></div>
</div>
{jq}
function load() {literal}{{/literal}
  var map = new GMap2(document.getElementById("map"));
  map.addControl(new GLargeMapControl());
  map.addControl(new GMapTypeControl());
  map.setCenter(new GLatLng({$prefs.gmap_defaulty}, {$prefs.gmap_defaultx}), {$prefs.gmap_defaultz});

  GEvent.addListener(map, "zoomend", function(gold, gnew) {literal}{{/literal}
    document.getElementById('gmap_defaultz').selectedIndex = gnew;
  {literal}});{/literal}

  GEvent.addListener(map, "moveend", function() {literal}{{/literal}
    document.getElementById('gmap_defaultx').value = map.getCenter().x;
    document.getElementById('gmap_defaulty').value = map.getCenter().y;
  {literal}});{/literal}

{literal}}{/literal}
window.onload=load;
{/jq}
{/if}
