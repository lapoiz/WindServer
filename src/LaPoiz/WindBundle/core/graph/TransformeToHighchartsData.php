<?php
namespace LaPoiz\WindBundle\core\graph;


/**
 * @author dapoi
 */
class TransformeToHighchartsData   {
	static private $beginHour= '9'; // begin kite session
	static private $endHour= '19'; // end kite session
	
	static function transformePrevisionDate($previsionDate) {
		$highchartsData=array();
		$xAxisData=array();
		$currentDay='';
		foreach ($previsionDate->getListPrevision() as $prevision) {
			$date=$previsionDate->getDatePrev();
			$date->setTime($prevision->getTime()->format('H'),0);
			$highchartsData[]=array('date'=>$date->format('Y, m-1, d, H'),'wind'=>array($prevision->getWind()));
			if ($currentDay!=$date->format('d')) {
				$xAxisData[]=array('year'=>$date->format('Y'),'month'=>$date->format('m-1'),'day'=>$date->format('d'));
				$currentDay=$date->format('d');
			}
		}

		return array("serieName" => $previsionDate->getDataWindPrev()->getWebSite()->getNom(),
					"highchartsData" => $highchartsData,
					"xAxisData" => $xAxisData);		
	}

	// For same WebSite
	static function transformePrevisionDateList($previsionDateList) {

		$highchartsData=array();
		$xAxisData=array();
		$currentDay='';
		$firstTime=true;

		foreach ($previsionDateList as $previsionDate) {
			foreach ($previsionDate->getListPrevision() as $prevision) {
				$date=$previsionDate->getDatePrev();
				$date->setTime($prevision->getTime()->format('H'),0);
				$highchartsData[]=array('date'=>$date->format('Y, m-1, d, H'),'wind'=>$prevision->getWind()); // m-1 because jan=0, feb=1 ... dec=11

				if ($currentDay!=$date->format('d')) {
					$xAxisData[]=array('year'=>$date->format('Y'),'month'=>$date->format('m-1'),'day'=>$date->format('d'));//, 'hour'=>TransformeToHighchartsData::$beginHour);
					$currentDay=$date->format('d');
				}
			}
			if ($firstTime) {
				$serieName=$previsionDate->getDataWindPrev()->getWebSite()->getNom();
				$firstTime=false;
			}
		}
		return array(	"serieName" => $serieName,
						"highchartsData" => $highchartsData,
						"xAxisData" => $xAxisData);	
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