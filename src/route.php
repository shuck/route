<?php 
namespace Shuck\Route;

class Route
{
    /**
     * 提供匹配的uri
     * @var String
     */
    private $pattern = null;

    /**
     * 提供匹配uri的数组形式
     * @var String
     */
    private $pattern_arr = null;

    /**
     * 存储在本Route的数据
     * @var array
     */
    private $storage = array();

    /**
     * 可接受的访问HTTP方法
     * @var array
     */
    private $methods = array(
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH'
    );

    /**
     * 最后的匹配获得的参数
     * @var array
     */
    private $params;

    /**
     * 构造函数
     * @param $pattern  string  匹配的路由规则 eg: /users/:id/edit
     * @param $storage    array   本路由存储的信息
     * @param null $methods array   本路由可接受的HTTP方法
     */
    public function __construct($pattern, $storage, $methods = null)
    {
        $this->pattern = $pattern;
        $this->storage = $storage;
        if (!is_null($methods)) {
            $this->methods = (array)$methods;
        }
    }

    /**
     * 重新设置本路由接受的HTTP方法
     */
    public function via(){
        $methods = (array)func_get_args();
        if(count($methods) === 1 and is_array($methods[0])){
            $methods = $methods[0];
        }
        $this->methods = array_map('strtoupper',$methods);
    }

    /**
     * 将URI转换成可用的数组形式
     */
    private function uri_to_array($uri)
    {
        // reg = '/+'
        return preg_split('|(?mi-Us)/+|', trim($uri, '/'));
    }

    /**
     * 返回Route RUI中匹配得到的参数数组
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 获取存入本Route的信息
     * @return array
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 获取本路由的允许匹配的URI
     * @return String
     */
    public function getUri()
    {
        return $this->pattern;
    }

    /**
     * 获取本路由允许的Method方法
     * @return array
     */
    public function getMethods()
    {
        return array_unique($this->methods);
    }

    /**
     * 判断参数方法是否是本路由可接受的
     * @param $methods string/array HTTP方法
     * @return bool
     */
    public function allow_method($methods){
        $methods = array_map('strtoupper',(array)$methods);
        foreach((array)$methods as $method){
            if($method == 'HEAD'){
                $method = 'GET';
            }
            if(in_array($method, $this->methods)){
                return true;
            }
        }
        return false;
    }
}
