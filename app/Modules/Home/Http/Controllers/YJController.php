<?php

namespace App\Modules\Home\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class YJController extends Controller
{
    protected $uri = 'https://gateway.watsonplatform.net/assistant/api/v1';
    protected $version = '?version=2018-07-10';
    protected $name = "b45b1ce6-3f1d-4dd7-a744-1a64a33dd862";
    protected $pwd = "QBRv8rU7oWIb";

    public function __construct()
    {
//        $this->uri = 'https://gateway.watsonplatform.net/assistant/api';
    }

    /**
     * 生成相应的地址
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
//        dd($url);
        $url .= $this->version;
//        dd($url);
        //拼接相应的地址
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $url .= '&' . $k . '=' . $v;
            }
        }
        return $url;
    }

    //////-----工作区相关------///////

    /**
     * 列出工作区
     * @return \Illuminate\Http\JsonResponse
     * 可选参数
     * [
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => false     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  false    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getWs()
    {
        $url = $this->pubAddr([
            'workspaces' => ''
        ],[
//            'version' => $this->version,
            'include_audit' => 'true',
            'page_limit' => 99999,
        ]);
//        dd($url);
//        https://gateway.watsonplatform.net/assistant/api/v1/workspaces?version=2018-07-10
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }
    public function getOneWs(Request $request)
    {
        $url = $this->pubAddr([]);

    }

    /**
     * @param string $url
     * @return mixed
     */
    public function doGet($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
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
     * @param string $post_data
     * @return mixed
     */
    public function doPost($url,$post_data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
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
    public function doDel($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
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
