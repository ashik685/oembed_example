<?php 


function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}

// Get the wordpress custom field value
$youtubeid = get_post_meta($post->ID, "youtubeid", true);

if($youtubeid !== '') :
	// define URLS
	$video_url = 'http://youtube.com/watch?v='.$youtubeid;
	$oembed_endpoint = 'http://www.youtube.com/oembed';

	// request Oembed in XML format and proccess it with curl (the function above)
	$oembed_url = $oembed_endpoint . '?url=' . rawurlencode($video_url) . '&format=xml';
	$oembed = simplexml_load_string(curl_get($oembed_url));
	
	// get size values and embed code
	$youtubeW = html_entity_decode($oembed->width);
	$youtubeH = html_entity_decode($oembed->height);
	$youtube_code = html_entity_decode($oembed->html);
	
	// define your video width
	$column_w = 712;
	
	// see the difference between original size and your size							
	$percentResized = ($column_w*1)/$youtubeW;
	
	// make the original height proportional to your width
	$newYoutubeH = $youtubeH*$percentResized;
	
	// create size arrays for replacing on the $youtube_code string
	$W_and_H = array($youtubeW, $youtubeH);
	$Big_W_and_H = array($column_w, $newYoutubeH);
	
	// find the original values and replace them with the resized values
	$bigEmbed = str_replace($W_and_H, $Big_W_and_H, $youtube_code);
	
								// hallo todas las cosas q esten entre comillas en mi bigEmbed code
							preg_match_all('/"([^"]+)"/',
							$bigEmbed,
							$salida, PREG_PATTERN_ORDER);
							
							// guardo el valor de la url de youtube
							$clean_movieParama_value =  $salida[1][3] ;
							
							//echo $clean_movieParama_value;
							
							
							$urlWithOptions = $clean_movieParama_value . "&rel=1&autoplay=1&color1=0xffffff&color2=0xffffff&border=0&fs=1";
							
							
							// remplaza url limpia con url con parametros
							$bigEmbed_withOptions = str_replace($clean_movieParama_value, $urlWithOptions, $bigEmbed);
							
							
							echo $bigEmbed_withOptions;
							
	
endif;

?>
