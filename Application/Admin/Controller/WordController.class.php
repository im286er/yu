<?php
namespace Admin\Controller;
use Think\Controller;
class WordController extends CommonController{
	//-------------------------------------
	public function sensitive(){  
       if(IS_POST){
        $word_arr = explode(';',I('word'));
        F('sensitive_word',$word_arr);
       }
       $this->word = implode(";",F('sensitive_word'));
       $this->display();
	}
    //-------------------------------------
	public function search(){  
       if(IS_POST){
        $word_arr = explode(';',I('word'));
        F('search_word',$word_arr);
       }
       $this->word = implode(";",F('search_word'));
       $this->display();
	}
	
//----------------------------------------
}

?>