<?php
namespace LaPoiz\WindBundle\core\websiteDataManage;

use LaPoiz\WindBundle\Entity\PrevisionDate;
use LaPoiz\WindBundle\Entity\Prevision;

class MeteoFranceGetData
{
	
	static function getDataURL($url) 
	{

	  $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'LaPoiz Application');
      $cookies = "foo=bar";
      curl_setopt($ch, CURLOPT_COOKIE, $cookies);
      $html = curl_exec($ch);
      curl_close($ch);
      
	  $dom = new DomDocument();
	  @$dom->loadHTML($html);
	  $dom->save('meteoFrancePage.txt');
	  return $html; 		
	}

	
	static function getDataURLOLD($url) 
	{
	    $fp = fopen("cookies.txt",'wb');	
		fclose($fp);
				
		$ch = curl_init();
		$user_agent = "Mozilla/5.0 (X11; U; Linux x86_64; fr; rv:1.9.0.1) Gecko/2008072820 Firefox/3.0.1";
		
		curl_setopt($ch, CURLOPT_URL, $url);
  		curl_setopt($ch, CURLOPT_HEADER, 1); 
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	  	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	  	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
        
	  	$html = curl_exec($ch);
		curl_close($ch);
	  /*
      $curl = curl_init($url); 
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
      curl_setopt($curl, CURLOPT_HEADER, false); 
      curl_setopt($curl, CURLOPT_COOKIEJAR, realpath('cookie.txt'));
      $page = curl_exec($curl);
      
      $error = curl_errno($curl); 
      curl_close($curl);  
     
      $dom = new DomDocument();
	  @$dom->loadHTML($html);
	  $dom->save('meteoFrancePage.txt');
*/	  
	  return $html; 		
	}
	
	
	static function parseURL($url) {
		$tableauData = tableauData($url);
		if (sizeof($tableauData)>0) {
			createPrevisionDate($tableauData);
		}
	}
	
	static function tableauData($url) 
	{ 
		$tableauData = array();
		$file = fopen($url,"r");		
		
		while (!feof($file)) 
		{ // line per line			
  			$line = fgets($file, 4096); // read a line
  			
  			if (WindguruGetData::isGoodLine($line)) {  // choose good line where every data is			    
  				$windPart=WindguruGetData::getPart(WindguruGetData::exprWindPart,$line); // get wind part in line				
  				$tableauData['wind']=WindguruGetData::getElemeInPart($windPart);// transforme to tab		 		
  				
  				$hourePart=WindguruGetData::getHourePart($line);				
  				$tableauData['heure']=WindguruGetData::getElemeInPart($hourePart);// transforme to tab
  				
  				$datePart=WindguruGetData::getDatePart($line);				
  				$tableauData['date']=WindguruGetData::getElemeInPart($datePart);// transforme to tab		 		
  				
  			}
		}		
		fclose($file);
		return $tableauData;
	}
	
	static function createPrevisionDate($tableauData) {
	  $prevDate = new PrevisionDate();
	  $prevDate->setCreated(new \DateTime("now"));

	  $prevDate->setDatePrev();
	  
	  $prev = new Prevision();
	  $prev->setOrientation();
	  $prev->setWind();
	  $prev->setTime();
	  $prev->setPrevisionDate($prevDate);
	  $prevDate->addListPrevision($prev);
	  
	  $prevDate->setWindAverage();
	  $prevDate->setWindGauss();
	  $prevDate->setWindMax();
	  $prevDate->setWindMin();
	  
	} 	
}