<?php
class template
	{
	var $TAGS=array();
	var $THEME;
	var $CONTENT;
	function define_tag($tagname,$varname)
		{
		$this->TAGS[$tagname]=$varname;
		}
	function define_theme($themename)
		{
		$this->THEME=$themename;
		}
	function parse()
		{
		$this->CONTENT=file($this->THEME);
		$this->CONTENT=implode("",$this->CONTENT);
			while (list($key,$val)=each($this->TAGS))
			{
			$this->CONTENT=preg_replace($key,$val,$this->CONTENT);
			}
		}
				
	function printproses()
		{
		echo $this->CONTENT;
		}
	}
include "../__conf/config.db.php";
include "menu.php";
include "../function/function.php";

?>