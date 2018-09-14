<?php

/**
 * by fxl 2018-09-03
 * 调用IBM Waston接口使用
 * Class Waston
 */

class WastonImg
{
    protected $uri;                 //uri   请求的基础地址
    protected $version;             //version  请求的版本日期，暂时默认，后续从配置里面取
    protected $apikey;                //用户凭证的apikey

    /**
     * new Waston必传的参数，按格式传递
     * [
     *      'base_uri' => 'https://gateway.watsonplatform.net/visual-recognition/api/v3',
     *      'version' => 'version=2018-03-19',
     *      'apikey' => 'fcoeCiUCAR7qQV6fA4IL8iBNdvR5pOzs_1-p-s9AyO4E',
     * ]
     * Waston constructor.
     * @param array $params
     */
    public function __construct($params = array())
    {
        $this->uri = $params['base_uri'];
        $this->version = $params['version'];
        $this->apikey = $params['apikey'];
    }

    /**
     * 生成相应的地址
     * $param array $skewp   拼接在url后的一些参数
     * @param array $params
     * @return string
     * 暂时仅提供一下集中传递方式---需按照顺序传递，如果最上为空，则下面便不能在传递
     * 1.[
     *      'workspaces' => '{workspace_id}',
     *      'intents' => '{intent}',
     *      'examples' => '{text}',
     * ]
     * 2.[
     *      'workspaces' => '{workspace_id}',
     *      'counterexamples' => '{text}',
     * ]
     * 3.[
     *      'workspaces' => '{workspace_id}',
     *      'entities ' => '{entities }',
     *      'values' =>  '{value}',
     * ]
     * 4.[
     *      'workspaces' => '{workspace_id}',
     *      'dialog_nodes' => '{dialog_node}',
     * ]
     */
    public function pubAddr($skewp = array(),$params = array())
    {
        $url = $this->uri;
        if (!is_array($params)) {
            return $url;
        }
        if (!empty($skewp)) {
            foreach ($skewp as $key => $val) {
                if (empty($val)) {
                    $url .= '/' . $key;
                } else {
                    $url .= '/' . $key . '/' . $val;
                }
            }
        }
        $url .= '?' . $this->version;
        //拼接相应的地址
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $url .= '&' . $k . '=' . $v;
            }
        }
        return $url;
    }

    ////////////--------相关信息的反馈-------////////////////////
    /// pub
    public function ceshi()
    {
//        https://gateway.watsonplatform.net/visual-recognition/api/v3/classifiers?verbose=true&version=2018-03-19
        $url = $this->pubAddr(array('classifiers' => ''));
//        var_dump($url);exit();

//
//        $dog1 = new CURLFile(public_path() . '/Beagle.zip','','beagle_positive_examples');
//        $dog2 = new CURLFile(public_path() . '/GoldenRetriever.zip','','goldenretriever_positive_examples');
//        $dog3 = new CURLFile(public_path() . '/Husky.zip','','husky_positive_examples');
//        $dog4 = new CURLFile(public_path() . '/Cats.zip','','negative_examples');
//
////////
//        $res = $this->doPost($url,array(
//            'beagle_positive_examples' => $dog1,
//            'goldenretriever_positive_examples' => $dog2,
//            'husky_positive_examples' => $dog3,
//            'negative_examples' => $dog4,
//            'name' => 'dogs'
//        ));
//        dd(json_decode($res));

        $url = $this->pubAddr(array('classifiers' => ''),array('verbose' => 'true'));
        $res = $this->doGet($url);
        dd(json_decode($res));
    }

    /**
     * 获取工作区的训练状态
     * @param string $ws
     * @return array|mixed
     */
    public function getStatus($ws = '')
    {
        if (empty($ws)) {
            return array('result' => 'failed','message' => '请传入相关参数');
        }
        $url = $this->pubAddr(array(
            'workspaces' => $ws,
            'status' => '',
        ));
        $request = $this->doGet($url);
        return json_decode($request,true);
    }

    /**
     * 根据用户输入响应信息
     * @param string $ws
     * @param string $text
     * @return array|mixed
     */
    public function message($ws = '',$text = '')
    {
        if (empty($ws) || empty($text)) {
            return array('result' => 'failed','message' => '请传入相关参数');
        }
        $url = $this->pubAddr(array(
            'workspaces' => $ws,
            'message' => '',
        ));

        $data = array(
            'input' => array(
                'text' => $text,
            ),
            'alternate_intents' => true,
        );

        $return = $this->doPost($url,json_encode($data));
        return json_decode($return,true);

    }

    //////////------curl的get,post,delete---------////////
    /**
     * @param string $url
     * @return mixed
     */
    private function doGet($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "apikey:{$this->apikey}");
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        return $output;
    }

    /**
     * @param string $url
     * @param $post_data
     * @return mixed
     */
    private function doPost($url,$post_data)
    {
//        dd($post_data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "apikey:{$this->apikey}");
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 请求头，可以传数组
//        curl_setopt($ch, CURLOPT_HEADER, $header);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * @param $url
     * @return mixed
     */
    private function doDel($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "apikey:{$this->apikey}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        return $output;

    }
}
