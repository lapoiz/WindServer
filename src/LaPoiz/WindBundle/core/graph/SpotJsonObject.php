<?php

namespace LaPoiz\WindBundle\core\graph;

class SpotJsonObject {
	public $title;
	public $subtitle;
	public $xAxis;
	public $yAxis;
	public $series;
	function __construct($title) {
		$this->title=new Text($title);
		$this->subtitle=new Text("Prévision météo");
		$this->subtitle->text="Prévision météo";
		$this->xAxis=new XAxis();
		$this->yAxis=new YAxis();
	}
}

class XAxis {
	public $type;
	public $tickInterval;
	public $dateTimeLabelFormats;
	public $gridLineWidth;
	public $gridLineDashStyle;
	public $labels;
	function __construct() {
		$this->type="datetime";
		$this->tickInterval=86400000;
		$this->dateTimeLabelFormats = new DateTimeLabelFormats();
		$this->gridLineWidth=1;
		$this->gridLineDashStyle="ShortDash";
		$this->labels=new Labels();
		$this->labels->align="left";
	}
}

class YAxis {
	public $title;
	public $min;
	public $minorGridLineWidth;
	public $gridLineWidth;
	public $gridLineDashStyle;
	//public $labels;
	public $plotBands;
	
	function __construct() {
		$this->title = new Text("Wind (Nd)");
		$this->min=0;
		$this->minorGridLineWidth=1;
		$this->gridLineWidth=1;
		$this->gridLineDashStyle="ShortDash";
		$this->plotBands=array();

		$this->plotBands[]=new PlotBand(0,12,"rgba(68, 170, 213, 0.1)","Light air","#606060");
		$this->plotBands[]=new PlotBand(12,22,"rgba(0, 0, 0, 0)","Kite planning","#606060");
		$this->plotBands[]=new PlotBand(22,40,"rgba(68, 170, 213, 0.1)","Windsurf planning","#606060");
	}
}

class Text {
	public $text;
	function __construct($newText)
	{
		$this->text=$newText;
	}	
}

class DateTimeLabelFormats {
	public $day;
	function __construct() {
		$this->day="%a";
	}
}

class Labels {
	public $align;
	//public $format;
	//public $style;
	function __construct() {
		// default value
		$this->align="center";
		//$this->format="{value}";
		//$this->style=null;
	}
}

class Label {
	public $text;
	public $style;
	function __construct($newText) {
		// default value
		$this->text=$newText;
		$this->style=null;
	}
}
/**
* 
*/
class PlotBand
{
	public $from;
	public $to;
	public $color;
	public $label;
	
	function __construct($newFrom,$newTo,$newColor,$labelText,$labelColor) {
		$this->from=$newFrom;
		$this->to=$newTo;
		$this->color=$newColor;
		$this->label = new Label($labelText);	
		$this->label->style=new Color($labelColor);
	}
}

class Color {
	public $color;
	function __construct($newColor) {
		$this->color=$newColor;
	}
}