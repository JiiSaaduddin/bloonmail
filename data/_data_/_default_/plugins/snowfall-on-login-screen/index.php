<?php

class SnowfallOnLoginScreenPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	public function Init()
	{
		$this->addJs('js/snowfall.js');
		$this->addJs('js/include.js');
	}
}
