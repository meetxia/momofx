<?php
//版本v1.1
!defined('EMLOG_ROOT') && exit('access deined!');
error_reporting(E_ERROR | E_PARSE);
class skycaijiart{
    public $charset;//编码
	public $pluginLang;//语言包
	public $funcDataPost;//获取$_POST参数函数
	public $funcDataGet;//获取$_GET参数函数
	public $funcError;//报错函数
	
	public function __construct($charset='utf-8'){
	    $this->charset=strtolower($charset);
	}
	
	//运行错误
	protected function doError($msg){
	    if($this->funcError){
	        call_user_func($this->funcError, $msg);
	    }else{
	        exit($msg);
	    }
	}
	public function caiji($url){
	    $data=array('code'=>0,'msg'=>'','data'=>array());
	    if(empty($url)){
	        $data['msg']='请输入网址';
	    }else{
	        $html=$this->getHtml($url);
	        if(!empty($html)){
	            $data['code']=1;
	            $data['data']['title']=$this->getTitle($html);
	            $data['data']['content']=$this->getContent($html);
	            $data['data']['content']=trim($data['data']['content']);
	        }
	    }
	    if(empty($data['msg'])&&(empty($data['data'])||empty($data['data']['content']))){
	        $data['code']=-1;
	        $data['msg']='抱歉，抓取失败！无法采集非文章格式或ajax渲染后的内容，推荐使用蓝天采集器可精准采集任何内容包括渲染后的网页，定时定量全自动批量采集发布数据';
	        $data['data']['title']=$data['data']['title']?:'';
	    }
	    return $data;
	}
	
	public function initCaijiHtml($html,$url){
	    //模板中必须包含skycaiji_art_url/skycaiji_art_btn/skycaiji_art_func
	    $html=$html?:'';
$html.=<<<EOF

<script type="text/javascript">
(function(){
    document.getElementById('skycaiji_art_btn').onclick=function(){
        var btnObj=this;
        var btnHtml=this.innerHTML.replace('...','');
        this.innerHTML=btnHtml.replace(/[\u4e00-\u9fa5]+/,function(str){
            return str+='...';
        });
        var url=document.getElementById('skycaiji_art_url').value;
        url='{$url}'+encodeURIComponent(url?url:'');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.send(null);
        xhr.onreadystatechange=function(){
            if(this.readyState==4&&this.status==200){
                btnObj.innerHTML=btnHtml;
                var data=xhr.responseText?JSON.parse(xhr.responseText):{};
                if(data.code==1){
                    data=data.data;
                    skycaiji_art_func(data.title,data.content);
                }else if(data.code==-1){
                    if(confirm(data.msg)){
                        window.open('https://www.skycaiji.com','_blank');
                    }
                }else{
                    alert(data.msg);
                }
            }
        };
    };
})();
</script>

EOF;
        return $html;
	}
	public function json($data){
	    ob_clean();
	    if($this->charset!='utf-8'){
	        $data=$this->convertCharset($data,$this->charset,'utf-8');
	    }
	    header('content-type:application/json;charset=utf-8');
	    $data=json_encode($data);
	    exit($data);
	}
	//转换编码
	public function convertCharset($data,$from,$to){
	    if($from!=$to){
    	    if(is_array($data)){
    	        foreach ($data as $k=>$v){
    	            $data[$k]=$this->convertCharset($v,$from,$to);
    	        }
    	    }else{
    	        //$data=iconv($from,$to.'//IGNORE',$data);
    	        $data=diconv($data,$from,$to);
    	    }
	    }
	    return $data;
	}
	
	public function getHtml($url) {
	    if(!preg_match('/^\w+\:\/\//', $url)){
	        //补全网址
	        $url='http://'.$url;
	    }
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 15 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt ( $ch, CURLOPT_ENCODING , '');//自动解码
		
		//https模式
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

		$html = curl_exec ( $ch );
		curl_close ( $ch );
		
		$header='';
		
		if($html){
		    $headerPos=strpos($html, "\r\n\r\n");//头信息定位索引
		    if($headerPos!==false){
		        $headerPos=intval($headerPos)+strlen("\r\n\r\n");//加上换行的长度等于头大小
		    }
		    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);//头大小，如果通过代理访问会有bug
		    $headerSize=intval($headerSize);
		    if($headerSize<$headerPos){
		        $headerSize=$headerPos;
		    }
		    $header = substr($html, 0, $headerSize);//头信息
		    $html = substr($html, $headerSize);//body内容
		}
		
		if($html){
		    $fromEncode='auto';
		    if ($fromEncode == 'auto') {
		        //转码
		        $htmlCharset='';//html网页编码
		        if(preg_match ( '/<meta[^<>]*?content=[\'\"]text\/html\;\s*charset=(?P<charset>[^\'\"\<\>]+?)[\'\"]/i', $html, $htmlCharset ) || preg_match ( '/<meta[^<>]*?charset=[\'\"](?P<charset>[^\'\"\<\>]+?)[\'\"]/i', $html, $htmlCharset )){
		            $htmlCharset=strtolower(trim($htmlCharset['charset']));
		            if('utf8'==$htmlCharset){
		                $htmlCharset='utf-8';
		            }
		        }else{
		            $htmlCharset='';
		        }
		        $headerCharset='';//返回头信息编码
		        if(preg_match('/\bContent-Type\s*:[^\r\n]*charset=(?P<charset>[\w\-]+)/i', $header,$headerCharset)){
		            $headerCharset=strtolower(trim($headerCharset['charset']));
		            if('utf8'==$headerCharset){
		                $headerCharset='utf-8';
		            }
		        }else{
		            $headerCharset='';
		        }
		        if(!empty($htmlCharset)&&!empty($headerCharset)&&strcasecmp($htmlCharset,$headerCharset)!==0){
		            //都检测出编码且不一致
		            $zhCharset=array('gb18030','gbk','gb2312');
		            if(in_array($htmlCharset,$zhCharset)&&in_array($headerCharset,$zhCharset)){
		                //都是中文编码
		                $fromEncode='gb18030';
		            }else{
		                //不是中文编码
		                $autoEncode = mb_detect_encoding($html, array('ASCII','UTF-8','GB2312','GBK','BIG5'));//自动检测编码
		                if(strcasecmp($htmlCharset,$autoEncode)==0){
		                    $fromEncode=$htmlCharset;
		                }elseif(strcasecmp($headerCharset,$autoEncode)==0){
		                    $fromEncode=$headerCharset;
		                }else{
		                    $fromEncode=$autoEncode;
		                }
		            }
		        }elseif(!empty($htmlCharset)){
		            //html编码
		            $fromEncode=$htmlCharset;
		        }elseif(!empty($headerCharset)){
		            //header编码
		            $fromEncode=$headerCharset;
		        }else{
		            //自动检测编码
		            $fromEncode = mb_detect_encoding($html, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		        }
		        $fromEncode=empty($fromEncode)?null:$fromEncode;
		    }
		    $fromEncode=trim($fromEncode);
		    
		    if(!empty($fromEncode)){
		        //转码
		        $fromEncode=strtolower($fromEncode);
		        switch ($fromEncode){
		            case 'utf8':$fromEncode='utf-8';break;
		            case 'cp936':$fromEncode='gbk';break;
		            case 'cp20936':$fromEncode='gb2312';break;
		            case 'cp950':$fromEncode='big5';break;
		        }
		        $html=$this->convertCharset($html,$fromEncode,$this->charset);
		    }
		}
		return $html;
	}
	
	public function getContent($html){
	    try {
	        if($this->charset!='utf-8'){
	            $html=$this->convertCharset($html,$this->charset,'utf-8');
	        }
	        require_once dirname(__FILE__).'/Readability.php';
	        $html=new Readability($html,'utf-8');//只能处理utf8编码
	        $html=$html->getContent();//只返回content值，其他注释掉防止报错
	        $html=$html['content'];
	        if($this->charset!='utf-8'){
	            $html=$this->convertCharset($html,'utf-8',$this->charset);
	        }
	    }catch (\Exception $ex){
	        $html='';
	    }
	    return $html?$html:'';
	}
	
	//获取标题
	public function getTitle($html){
	    //匹配h1标签
	    if(preg_match_all('/<h1\b[^<>]*?>(?P<content>[\s\S]+?)<\/h1>/i', $html,$title)){
	        if (count($title['content'])>1){
	            //h1标签不是唯一的
	            $title=null;
	        }else{
	            $title=strip_tags(reset($title['content']));//不能加trim，&nbsp;会出问题
	            if (preg_match('/^((\&nbsp\;)|\s)*$/i', $title)){
	                $title=null;
	            }
	        }
	    }else{
	        $title=null;
	    }
	    if (empty($title)){
	        $pattern = array (
	            '<(h[12])\b[^<>]*?(id|class)=[\'\"]{0,1}[^\'\"<>]*(title|article)[^<>]*>(?P<content>[\s\S]+?)<\/\1>',
	            '<title>(?P<content>[\s\S]+?)([\-\_\|][\s\S]+?)*<\/title>'
	        );
	        $title=$this->returnPregMatch($pattern, $html);
	    }
	    return trim(strip_tags($title));
	}
	//匹配规则的值 规则 $pattern 来源内容 $content 返回值得键名 $reg_key
	public function returnPregMatch($pattern,$content,$reg_key='content'){
	    if(is_array($pattern)){
	        //是数组，循环匹配
	        foreach ($pattern as $patt){
	            if(preg_match('/'.$patt.'/i', $content,$cont)){
	                $cont=$cont[$reg_key];
	                break;//匹配到就跳出
	            }else{
	                $cont=false;
	            }
	        }
	    }else{
	        if(preg_match('/'.$pattern.'/i', $content,$cont)){
	            $cont=$cont[$reg_key];
	        }else{
	            $cont=false;
	        }
	    }
	    return empty($cont)?'':$cont;
	}
	
	//获取post参数
	protected function dataPost($key){
	    if(empty($this->funcDataPost)){
	        return isset($_POST[$key])?$_POST[$key]:'';
	    }else{
	        return call_user_func($this->funcDataPost,$key);
	    }
	}
	//获取get参数
	protected function dataGet($key){
	    if(empty($this->funcDataGet)){
	        return isset($_GET[$key])?$_GET[$key]:'';
	    }else{
	        return call_user_func($this->funcDataGet,$key);
	    }
	}
}
?>