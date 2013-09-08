<?php	
namespace LaPoiz\WindBundle\core\graph;

	
/**
 * @author dapoi
 * var data = [10,4,17,50,25,19,20,25,30,29,30,29,10,4,17,50,25,19,20,25,30,29,30,29];
 * var tabLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
 */
class TransformPrevisionDate {
	public static function  previsionDateToLine($lastPrevisionDate) {
			
 		$tabLabel=array();
		$tabData=array();
		$tabWind=array();
		$nbElem=0;
		$isFirst=true;
		$isSecond=true;
		
		// transform to correct array
		foreach ($lastPrevisionDate as $previsionDate) {
			$tabLabel[]=$previsionDate->getDatePrev()->format('D j M');
			if ($isFirst) {
				$isFirst=false;
			} elseif ($isSecond) {
				$nbElem = count($previsionDate->getListPrevision());
				$isSecond=false;
			}
			$tabElem=array();
			foreach ($previsionDate->getListPrevision() as $prevision) {
				$tabElem[]=$prevision->getWind();
			}
			$tabWind[]=$tabElem;
		}
		// first prevision could begin in midle of the day
		for ($i = 0; $i < ($nbElem-count($tabWind[0])); $i++) {
			$tabData[]=0;
		}
		
		foreach ($tabWind as $previsions) {
			foreach ($previsions as $wind) {
				$tabData[]=$wind;
			}
		}
		
		// last prevision could finish in midle of the day
		for ($i = 0; $i < ($nbElem-count($tabWind[count($tabWind)-1])); $i++) {
			$tabData[]=0;
		}
		
		return array('tabLabel'=>$tabLabel, 'tabData'=> $tabData);
	}
	
	
	
	public static function  previsionDateToBar($lastPrevisionDate) {
			
		$tabLabel=array();
		$tabWind=array();
		$maxSize=0;
		// transform to correct array
		foreach ($lastPrevisionDate as $previsionDate) {
			$tabLabel[]=$previsionDate->getDatePrev()->format('D j M');
			foreach ($previsionDate->getListPrevision() as $prevision) {
				$time=$prevision->getTime()->format('H');
				if (!isset($tabWind[$time])) {
					$tabWind[$time]=array();
				}
				$tabWind[$time][]=$prevision->getWind();
				$maxSize = $maxSize>=count($tabWind[$time])?$maxSize:count($tabWind[$time]); 
			}
		}
		// $tabWind = [ "08"=>[5,10,30,5], "10"=>[12, 6,11,30,5], "12"=>[12,7,12,31, 5] ...
		
		
		//$maxSize == count($tabLabel)
		$tabData=array();
		foreach ($tabWind as $elemWind) {
			if (count($elemWind)<$maxSize) {
				$elemWindCompleted=array();
				for ($i = 0; $i < $maxSize-count($elemWind); $i++) {
					$elemWindCompleted[]=0;
				} 
				foreach ($elemWind as $value) {
					$elemWindCompleted[]=$value;
				}
				$elemWind=$elemWindCompleted;
			}
			$tabData[]=$elemWind;
		}
		
		//var tabData = [[0,5,10,30,5],[12,6,11,30,5],....];
		//var tabLabel = ['mond 27 fev','thus 28 fev','....];
		return array('tabLabel'=>$tabLabel, 'tabData'=> $tabData);
	} 
	
}