<?php

/****************************
REMOVE '_default' FROM FILE NAME
****************************/

$CLIENT_ID = ''; // client id from your app - get from https://developers.eveonline.com/applications
$CLIENT_SECRET = ''; // client secret of your app - get from https://developers.eveonline.com/applications

$redirect_uri = ''; // callback url of your app - get from https://developers.eveonline.com/applications
$scopes = 'esi-location.read_location.v1 esi-location.read_ship_type.v1 esi-skills.read_skills.v1 esi-skills.read_skillqueue.v1 esi-clones.read_clones.v1 esi-assets.read_assets.v1 esi-characters.read_standings.v1 esi-characters.read_corporation_roles.v1 esi-clones.read_implants.v1 esi-characters.read_fatigue.v1 esi-characters.read_titles.v1'; // list of scopes - get from https://developers.eveonline.com/applications


$eve_url = 'https://esi.tech.ccp.is/latest'; // eve api source
$eve_datasource = '?datasource=tranquility'; // eve api datasource

?>
