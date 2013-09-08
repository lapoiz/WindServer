<?php	
namespace LaPoiz\WindBundle\core\graph;

	
/**
 * @author dapoi
 */
class TransformSpot   {

	function public spotToBar($spot) {
		
		
		// transform to correct tab
		
		//var tabData = [[10,75],[32, 44],[61, 56],[91, 81],[92, 8],[34, 57],[56, 62],[55, 45],[36, 12],[44, 56],[51, 66]];
		//var tabLabel = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov'];
		return array('tabLabel'=>$tabLabel, 'tabData'=> $tabData);
		
	} 
	
}