<?php
namespace Index\Model;
use Think\Model\RelationModel;
class ExpressageModel extends RelationModel{   

   //------------------------
   public function areaExist($area_id){
       $map['area_id']  = $area_id;
       if($this->where($map)->count()){
          return true;
       }else{
          return false;
       }
   }
   //------------------------
   public function cal($weight,$area_id){
       $cost = 0;
       if($area_id > 0){
            $map['area_id']  = $area_id;
            $area_cal = $this->where($map)->find();
            $over_weight = floatval($weight-$area_cal['init_weight']);
            $over_cost = ceil($over_weight/$area_cal['extra_weight']) * $area_cal['extra_cost'];
            $cost = (float)($area_cal['init_cost']+$over_cost);
       }else{
         $default_cal = F('default_expressage');        //默认运费计算
         if(empty($default_cal)){
            $cost = 0;
         }else{
            $over_weight = floatval($weight-$default_cal['init_weight']);
            $over_cost = ceil($over_weight/$default_cal['extra_weight']) * $default_cal['extra_cost'];
            $cost = (float)($default_cal['init_cost']+$over_cost); 
         }
       }
       return $cost;
   }
//----------------------------------
}

?>