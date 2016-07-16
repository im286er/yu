<?php
//-------------------------------------------------------------------------------工具函数
//打印测试数据
function pr($res){
    echo '<pre>';
    print_r($res);  
}
//标红关键字
function keywordTag($str,$keyword){
    if($keyword==''){
        return $str;
    }else{
       return str_replace($keyword,"<font color='red'>$keyword</font>",$str); 
    }
}
//手机邮箱隐藏中间
function hideKey($key) {
   $str1 = substr($key,0,3);
   $str2 = substr($key,-3,3);
   return $str1.'******'.$str2;
}
//敏感词替换
function word_filter($str){
   $word_arr = F('word');
   if(!empty($word_arr)){
      $badword = array_combine($word_arr,array_fill(0,count($word_arr),'*'));
      $str = strtr($str, $badword);
   }
   return $str;  
}
//截取中英文字符串 抄自discuz
function cutstr($string, $length, $dot = '...') {
    if (strlen($string) <= $length) {return $string;}
        $pre = chr(1);$end = chr(1);
        $string = str_replace ( array ('&amp;', '&quot;', '&lt;', '&gt;' ), array ($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end ), $string );  
        $strcut = '';  
        if('utf-8') {
            $n = $tn = $noc = 0;
            while ( $n < strlen ( $string ) ) {
            $t = ord ( $string [$n] );
            
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                // 如果是英语半角符号等,$n指针后移1位,$tn最后字是1位
                $tn = 1;
                $n++;
                $noc++;
                } elseif (194 <= $t && $t <= 223) {
                // 如果是二字节字符$n指针后移2位,$tn最后字是2位
                $tn = 2;
                $n += 2;
                $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                // 如果是三字节(可以理解为中字词),$n后移3位,$tn最后字是3位
                $tn = 3;
                $n += 3;
                $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
                } else {
                $n++;
            }
            // 超过了要取的数就跳出连续循环
            if($noc >= $length){
            break;
            }
        }
        // 这个地方是把最后一个字去掉,以备加$dot
        if ($noc > $length) {
          $n -= $tn;
        }
        $strcut = substr ( $string, 0, $n );
    
    } else {
        // 并非utf-8编码的全角就后移2位
        for ($i = 0; $i < $length; $i ++) {
           $strcut .= ord ( $string [$i] ) > 127 ? $string [$i] . $string [++ $i] : $string [$i];
        }
    }
    $strcut = str_replace( array ($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end ), array ('&amp;', '&quot;', '&lt;', '&gt;' ), $strcut );
    $pos = strrpos ( $strcut, chr ( 1 ) );
            if ($pos !== false) {
            $strcut = substr ( $strcut, 0, $pos );
    }    
    return $strcut . $dot; // 最后把截取加上$dot输出
}
//标准对象转换数组
function object_array($array){
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}
//获取用户真实Ip地址
function getIp(){ 
        $onlineip=''; 
        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){ 
            $onlineip=getenv('HTTP_CLIENT_IP'); 
        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){ 
            $onlineip=getenv('HTTP_X_FORWARDED_FOR'); 
        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){ 
            $onlineip=getenv('REMOTE_ADDR'); 
        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){ 
            $onlineip=$_SERVER['REMOTE_ADDR']; 
        } 
        return $onlineip; 
}
?>