<?php

/**
 * @copyright   Copyright (C) 2016 Oliver Neff. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Rest Handler handles rest requests to Schach-in-Starkenburg website.
 *
 * @author Oliver Neff
 */
class RestHandler {

	  const URL = '173.249.18.182:80/webservicetest/api/';

    function getMKTabellen($url, $action, $parameters) {
        //var_dump($parameters);
        $service_url = $url. 'MKTabellen/' . $action . '?' . 'turnierid=' . $parameters['turnierid'] . '&spielklassenid=' . $parameters['spielklassenid'] . '&runde=' . $parameters['runde'];
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,5);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        
        
        $decoded = json_decode($curl_response, true);
        return $decoded;
    }
	
	function getSpieltage($url, $action, $parameters) {
        //var_dump($parameters);
        $service_url = $url. 'Spieltage/' . $action . '?' . 'turnierid=' . $parameters['turnierid'] . '&spielklassenid=' . $parameters['spielklassenid'];
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,5);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        
return json_decode($curl_response, true);
//$decoded = json_decode($curl_response, true);
        //return $decoded['rows'];
    }

}
