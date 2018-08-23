<?php

namespace App\Modules\Home\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class WastonController extends Controller
{
    protected $uri;
    protected $name = "b45b1ce6-3f1d-4dd7-a744-1a64a33dd862";
    protected $pwd = "QBRv8rU7oWIb";

    public function __construct()
    {
        $this->uri = 'https://gateway.watsonplatform.net/assistant/api';
    }

    ///////////////////------工作区部分------/////////////////
    /**
     * 获取工作区内容
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkspace()
    {
        $url = 'https://gateway.watsonplatform.net/assistant/api/v1/workspaces?version=2018-07-10';

        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加工作区
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWs()
    {
        $data = [
              "name" => "API test6666",
              "intents" => [],
              "entities" => [],
              "language" => "en",
              "description" => "Example workspace created via API.",
              "dialog_nodes" => [],
            ];
        $url = 'https://gateway.watsonplatform.net/assistant/api/v1/workspaces?version=2018-07-10';

        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));

    }

    /////////////////////--------意向-------------/////////////////
    /**
     * 获取所有意向
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIntent(Request $request)
    {
        $export = $request->get('export','true');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents?version=2018-07-10&export={$export}";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取单个意向
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneIntent(Request $request)
    {
        $intent = $request->get('intent','hello');
        $export = $request->get('export','true');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents/{$intent}?version=2018-07-10&export={$export}";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }
    /**
     * 添加意向
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIntent()
    {
        $url =  "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents?version=2018-07-10";
        $data = [
                  "intent" => "pipi",
                  "examples" => [
                        ["text" => "pi"],
                        ["text" => "p"],
                        ["text" => "pii"],
                        ["text" => "pipi"],
                      ]
                ];
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 更新意向
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIntent(Request $request)
    {
        $intent = $request->get('intent','hello');
        $url =  "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents/{$intent}?version=2018-07-10";
        $data = [
            "intent" => "hello",
            "examples" => [
                ["text" => "good"],
                ["text" => "Hello"],
            ],
            "description" =>"Updated intent",
        ];
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除意向
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delIntent(Request $request)
    {
        $intent = $request->get('intent','hello');
        $url =  "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents/{$intent}?version=2018-07-10";

        $return = $this->doDel($url);
        return response_success(json_decode($return,true));

    }


    ////////////------------example例子方法--------///////////////////////

    /**
     * 获列出用户输入的示例
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
        {
            "status": "successful",
            "code": 1,
            "data": {
            "examples": [
                    {
                        "text": "good"
                    },
                    {
                        "text": "Hello"
                    }
                ],
                "pagination": {
                    "refresh_url": "/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/intents/hello/examples?version=2018-07-10&export=true"
                }
            }
        }
     */
    public function getExample(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $intent = $request->get('intent','hello');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/intents/{$intent}/examples?version=2018-07-10&export=true";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取用户输入的示例
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
            "text": "good"
            }
        }
     */
    public function getOneExample(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $intent = $request->get('intent','hello');
        $example = $request->get('example','good');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/intents/{$intent}/examples/{$example}?version=2018-07-10";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 创建用户输入的示例
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
                "text": "hello good"
            }
        }
     */
    public function addExample(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $intent = $request->get('intent','hello');
        $data = ["text" => "hello good"];
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/intents/{$intent}/examples?version=2018-07-10";

        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));

    }

    /**
     * 更新用户输入的示例
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
                "text": "hello good yes hello!"
            }
        }
     */
    public function updateExample(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $intent = $request->get('intent','hello');
        $example = $request->get('example','Hello');
        $data = ["text" => "hello good yes hello!"];
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/intents/{$intent}/examples/{$example}?version=2018-07-10";

        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除用户输入的示例
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delExample(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $intent = $request->get('intent','hello');
        $example = $request->get('example','Hello');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/intents/{$intent}/examples/{$example}?version=2018-07-10";

        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }

    /////////-----------实体相关------/////
    public function getEntity(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/entities?version=2018-07-10";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加实体
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
                "entity": "beverage"
            }
        }
     */
    public function addEntity(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/entities?version=2018-07-10";
        $data = [
            "entity" => "beverage",
            "values" => [
                    ["value" => "water"],
                    ["value" => "orange juice"],
                    ["value" => "soda"],
                    ["value" => "coffe"],
                    ["value" => "hot shui"],
                ],
        ];

        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }






    ///////-------dialog-------////

    /**
     * 创建dialog
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
            "dialog_nodes": [],
            "pagination": {
                "refresh_url": "/v1/workspaces/86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f/dialog_nodes?version=2018-07-10"
                }
            }
        }
     */
    public function getDialog(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/dialog_nodes?version=2018-07-10";
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加dialog
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * {
            "status": "successful",
            "code": 1,
            "data": {
                "type": "standard",
                "title": "Greeting",
                "output": {
                        "generic": [
                            {
                                "values": [
                                        {
                                        "text": "Hi! How can I help you?"
                                        }
                                    ],
                                "response_type": "text"
                            }
                        ]
                    },
                "conditions": "#hello",
                "dialog_node": "greeting"
            }
        }
     */
    public function addDialog(Request $request)
    {
        $ws = $request->get('ws','86e2b89a-8bf9-4a33-a8d3-5b2a2b79583f');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/dialog_nodes?version=2018-07-10";
        $data = [
              "dialog_node" => "greeting",
              "conditions" => "#hello",
              "output" => [
                    "generic" => [
                        [
                            "response_type" => "text",
                            "values" => [
                                [
                                    "text" => "Hi! How can I help you?"
                                ],
                            ],
                        ],
                    ],
              ],
              "title" => "Greeting",
        ];
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }









    /////////////////////--------curl方法-----------/////////////
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
