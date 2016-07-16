<?php
namespace Index\Util;
class Page{
    private $parameter;      //分页地址参数
    private $totalPage;      //总页数
    private $currentPage;    //当前页数
    private $startPage;      //数字开始页数
    private $stopPage;       //数字结束页数
    
    private $url     = ''; //当前链接URL
    //-----------------------------------
    public function __construct($totalRows,$listRows=20,$parameter = array()) {
        $this->totalPage   = ceil($totalRows/$listRows); //设置总页数
        $this->parameter   = empty($parameter) ? $_GET : $parameter;
        $this->currentPage = empty($_GET[C('VAR_PAGE')]) ? 1 : intval($_GET[C('VAR_PAGE')]);
        $this->startPage   = $this->currentPage - 3 ;   //区间数字页数的起始
        $this->stopPage   = $this->currentPage + 3 ;    //区间数字页数的结束
        $this->prePage   = $this->currentPage - 1 ;     //上一页
        $this->nextPage   = $this->currentPage + 1 ;    //下一页
    }
    
    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }
    //-----------------------------------
    public function show() { 
        $this->parameter[C('VAR_PAGE')] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        
        $link_page = '';
        //判断是否有上一页
        if($this->prePage >= 1 ){  //假如有上一页(上一页页数大于等于1)
            $link_page .= '<a class="paginator-item paginator-item-prev" href="' . $this->url($this->prePage) . '">' .'上一页'. '</a>';
        }
        //先循环到当前页
        if($this->startPage <= 4){    //假如起始页数少于等于4
            for($i = 1; $i <= $this->currentPage; $i++){
                if($i==$this->currentPage){
                   $link_page .= '<a class="paginator-item paginator-item-selected" href="' . $this->url($i) . '">' . $i . '</a>'; 
                }else{
                   $link_page .= '<a class="paginator-item" href="' . $this->url($i) . '">' . $i . '</a>'; 
                }
            }
        }else{
            $link_page .= '<a class="paginator-item" href="' . $this->url(1) . '">' . 1 . '</a>';
            $link_page .= '<span class="omit">...</span>';
            for($i = $this->startPage; $i <= $this->currentPage; $i++){
                if($i==$this->currentPage){
                   $link_page .= '<a class="paginator-item paginator-item-selected" href="' . $this->url($i) . '">' . $i . '</a>'; 
                }else{
                   $link_page .= '<a class="paginator-item" href="' . $this->url($i) . '">' . $i . '</a>'; 
                }
            }
        }
        //当前页之后到结尾
        if($this->totalPage > $this->currentPage){ 
            $stopPage = $this->stopPage >= $this->totalPage ? $this->totalPage:$this->stopPage;
            for($i = $this->nextPage; $i <= $stopPage;$i++){               
                    $link_page .= '<a class="paginator-item" href="' . $this->url($i) . '">' . $i . '</a>';
            }
            if($this->totalPage > $stopPage) $link_page .= '<span class="omit">...</span>'; 
        }
        //判断是否下一页
        if($this->nextPage <= $this->totalPage ){  //假如有下一页(下一页页数少于总页数)
            $link_page .= '<a class="paginator-item paginator-item-next" href="' . $this->url($this->nextPage) . '">' .'下一页'. '</a>';
        }
        $link_page .= '共有'.$this->totalPage.'页'; 
        return "<div class='m-paginator'>{$link_page}</div>";
    }
//------------------------------------------------------------------
}

?>