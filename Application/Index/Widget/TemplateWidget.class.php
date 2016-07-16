<?php
namespace Index\Widget;
use Think\Controller;
class TemplateWidget extends Controller{
    
	public function toolbar(){
		$this->display('Widget:Toolbar:toolbar');
	}
//-----------------------------------
}
?>