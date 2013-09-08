<?php
namespace LaPoiz\WindBundle\core\graph;

use LaPoiz\WindBundle\core\graph\spotJsonObject\Serie;

/**
 * @author dapoi
 * Utiliser pour transformer les données du modele en donnée compatible au Bundle LaPoiz\HighchartsBundle
 */
class TransformeToHighchartsDataTabForJson   {
	static private $beginHour= '9'; // begin kite session
	static private $endHour= '19'; // end kite session
	
// For same WebSite
	/*
	{
		"name":"Windguru",
		"data": [
			[1362991572000,11],
            [1362992572000,8] -> depuis 1/1/1970 x 1 000 pour javascript
		]

	}
	From previsionDateList from the same spot/Website
	*/
	static function transformePrevisionDateList($previsionDateList) {

		$highchartsData=array();
		$xAxisData=array();
		$currentDay='';
		$firstTime=true;
		$serieName=null;

		$serie = new Serie();
		$serie->data=array();
		foreach ($previsionDateList as $previsionDate) {
			foreach ($previsionDate->getListPrevision() as $prevision) {
				$date=$previsionDate->getDatePrev();
				$date->setTime($prevision->getTime()->format('H'),0);
				$serie->data[]=array($date->getTimestamp()*1000,$prevision->getWind()); // not need m-1 because jan=0, feb=1 ... dec=11

				if ($currentDay!=$date->format('d')) {
					$xAxisData[]=array('year'=>$date->format('Y'),'month'=>$date->format('m-1'),'day'=>$date->format('d'));//, 'hour'=>TransformeToHighchartsData::$beginHour);
					$currentDay=$date->format('d');
				}
			}
			if ($firstTime) {
				$serieName=$previsionDate->getDataWindPrev()->getWebSite()->getNom();
				$serie->name=$serieName;
				$firstTime=false;
			} 
			
		}
		
		return $serie;
		//return array(	"name" => $serieName,
		//				"data" => $highchartsData);
						//"xAxisData" => $xAxisData);	
	}

	static function createResultJson($spot) {
		$spotJsonObject=new SpotJsonObject($spot->getNom());
		return $spotJsonObject;
	}




	static function transformePrevisionDate($previsionDate) {
		$highchartsData=array();
		//$xAxisData=array();
		$currentDay='';
		foreach ($previsionDate->getListPrevision() as $prevision) {
			$date=$previsionDate->getDatePrev();
			$date->setTime($prevision->getTime()->format('H'),0);
			$highchartsData[]=array('date'=>$date->format('Y, m-1, d, H'),'wind'=>array($prevision->getWind()));
			if ($currentDay!=$date->format('d')) {
				//$xAxisData[]=array('year'=>$date->format('Y'),'month'=>$date->format('m-1'),'day'=>$date->format('d'));
				$currentDay=$date->format('d');
			}
		}

		return array("serieName" => $previsionDate->getDataWindPrev()->getWebSite()->getNom(),
					"highchartsData" => $highchartsData
					);
					//,"xAxisData" => $xAxisData);		
	}

	
	// get elem from $dataGraphArray for return array with:
	// [from=>[year,month,day,hour], to=>[year,month,day,hour]]
	static function createElemForXAxisHighchart($dataGraphArray) {
		$xAxisDateTmp=array();
		foreach ($dataGraphArray as $dataGraph) {
			foreach ($dataGraph['dataGraph']['xAxisData'] as $datesData) {
					$xAxisDateTmp[$datesData['year']+'-'+$datesData['month']+'-'+$datesData['day']]=$datesData;
			}
		}

		$xAxisGraphData=array();
		$isFirst=true;
		$previousDateData=null;
		foreach ($xAxisDateTmp as $dateData) {
			if ($isFirst) {
				$isFirst=false;
			} else {
				//$previousDateData['hour']= TransformeToHighchartsData::$beginHour;
				//$dateData['hour']= TransformeToHighchartsData::$endHour;
				$xAxisGraphData[]=array('from'=>$previousDateData, 'to'=>$dateData);
			}
			$previousDateData=$dateData;
		}
		return $xAxisGraphData;
	}
}