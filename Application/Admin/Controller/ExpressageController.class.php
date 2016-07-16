<?php
namespace Admin\Controller;  //命名空间
use Think\Controller;        //基类
use Think\Page;              //分页类
class ExpressageController extends CommonController {
//--------------------------------------------------
   public function index(){  
      if(IS_POST){
         if(I('area')) $map['area_id'] = M('Area')->where("areaname = '".trim(I('area'))."'")->getField('id');
      }
      $this->mapSearch('Expressage',$map);
      //默认运费
      $this->assign('default',F('default_expressage'));
      $this->display();
   }
   
   //------------------------------------
   public function init(){       
      $arr['init_weight'] = I('init_weight');
      $arr['init_cost'] = I('init_cost');
      $arr['extra_weight'] = I('extra_weight');
      $arr['extra_cost'] = I('extra_cost');
      F('default_expressage',$arr);
   }
   //------------------------------------
   public function add(){       
      $this->assign('province',M('Area')->where('pid = 0')->order('sort desc')->select());
      
      $this->display();
   }
   //------------------------------------
   public function city(){       
      $area = M('Area');
      $map['pid'] = I('pid');
      $city = $area->where($map)->order('sort desc')->select();
      $this->ajaxReturn($city);
   }
   //------------------------------------
   public function insert(){       
      $yf = M('Expressage');
      if($yf->create()){  
          $yf->area_id = I('city_id')!=''?I('city_id'):I('province_id');
          if($yf->add()){ 
             $this->success('新增运费规则成功!');
          } else {
             $this->error('新增运费规则失败!');
          }
      }else{
        $this->error($yf->getError());  
      }
   }
   //------------------------------------
   public function edit(){
      $yfinf = M('Expressage')->find(I('id'));
      $this->yfinf = $yfinf;
      $res =  M('Area')->where('id = '.$yfinf['area_id'])->find();
      if($res['pid']!=0){ //假如地区是城市id
          $pid = $res['pid'];  //上级省份id
          $this->assign('city',M('Area')->where('pid = '.$pid)->order('sort desc')->select()); //所有上级省份下属城市
          $this->assign('province_id',$pid); //所有上级省份下属城市
      }
      $this->assign('province',M('Area')->where('pid = 0')->order('sort desc')->select());
      $this->display();
   }
   //------------------------------------
   public function update() {
      $yf = M('Expressage');
      if($yf->create()){ 
          $yf->area_id = I('city_id')!=''?I('city_id'):I('province_id');
          if($yf->save()){ 
             $this->success('运费规则编辑成功!');
          } else {
             $this->error('运费规则编辑失败!');
          }
      }else{
        $this->error($yf->getError());  
      }
   }
//----------------------------------------
}
?>