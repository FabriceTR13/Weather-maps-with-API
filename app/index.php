<?php 

error_reporting(E_ERROR);

/* example of Previmeteo weather API use with a weather map creation

First, download the background of your weather map from Google

http://maps.googleapis.com/maps/api/staticmap?center=44.5,0.15&zoom=8&size=400x400&sensor=false

in this case, the map is centered on latitude 44.5 and longitude 0.15 (Marmande, our office's city), 
with a 400x400 pixels map and zoom 8 (around 100km x 100km square)
save it as staticmap.png in images directory

Place pictograms in the same directory

Previmeteo.com - 2013

*/

//define size of final map
$height = 400;
$width = 400;

//fill your api key you have pickup from http://api.previmeteo.com
$API_KEY="YOUR_API_KEY";

//language and unit of data (en = english and imperial units, fr = french and metric units, de, es,…)
$hl="fr";

//you can pass the forecast day in the URL ?day=0 (today) or ?day=1 (tomorrow), etc.. if not provided, today is used
$available_days=Array("0","1","2","3");
if (in_array($_GET["day"],$available_days)) $day_forecast=$_GET["day"]; else $day_forecast=0;

//place where you want the weather
$Places = Array();

$Places[0]=Array("name"=>"Marmande,FR","x"=>180,"y"=>200);//first point with x and y position on the map in pixel Top-left=0,0
$Places[1]=Array("name"=>"Bordeaux,FR","x"=>30,"y"=>90);
$Places[2]=Array("name"=>"Mont-de-Marsan","x"=>60,"y"=>310);
$Places[3]=Array("name"=>"Agen,FR","x"=>260,"y"=>275);
$Places[4]=Array("name"=>"Bergerac,FR","x"=>240,"y"=>50);

//we collect weather iformation from Previmeto API
$Weather_data=Array();

foreach ($Places AS $Place) {
	//we lorad the data from the API
	$data_tmp=simplexml_load_file("http://api.previmeteo.com/".$API_KEY."/ig/api?weather=".$Place["name"]."&hl=".$hl);
	foreach ($data_tmp->weather->forecast_conditions AS $forecast_day) {
		//we populate the $Weather_data to have one single array for all the cities $Weather_data[$city][$Day]=Array("icon"=>.. , "tmin"=>.. , "tmax"=> ..)
		$Weather_data[$Place["name"]][]=Array("icon"=>(string)$forecast_day->icon["data"],"tmin"=>(integer)$forecast_day->low["data"],"tmax"=>(integer)$forecast_day->high["data"]);
	}
}

//use the background image
$image = imagecreatetruecolor($width,$height);
$background = imagecreatefrompng("images/staticmap.png");
//copy background map in the final image

imagecopy($image,$background,0,0,0,0,$width,$height);
//put the icons on map

foreach ($Places AS $Place) {
	//create the icon from gif file
	$icon = imagecreatefromgif(".".$Weather_data[$Place["name"]][$day_forecast]["icon"]);
	//copy the icon at the right place in image
	imagecopy($image,$icon,$Place["x"],$Place["y"],0,0,imagesx($icon),imagesy($icon));	
	//destroy icon
	imagedestroy($icon);
	}

//push the image to the web browser
header("Content-type: image/png");
imagepng($image);
//destroy image
imagedestroy($image);	


?>