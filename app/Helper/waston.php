<?php

/**
 * by fxl 2018-09-03
 * 调用IBM Waston接口使用
 * Class Waston
 */

class Waston
{
    protected $uri;                 //uri   请求的基础地址
    protected $version;             //version  请求的版本日期，暂时默认，后续从配置里面取
    protected $name;                //用户凭证的用户
    protected $pwd;                 //用户凭证的密码

    /**
     * new Waston必传的参数，按格式传递
     * [
     *      'base_uri' => 'https://gateway.watsonplatform.net/assistant/api/v1',
     *      'version' => 'version=2018-07-10',
     *      'name' => 'b45b1ce6-3f1d-4dd7-a744-1a64a33dd862',
     *      'pwd' => 'QBRv8rU7oWIb',
     * ]
     * Waston constructor.
     * @param array $params
     */
    public function __construct($params = array())
    {
        $this->uri = $params['base_uri'];
        $this->version = $params['version'];
        $this->name = $params['name'];
        $this->pwd = $params['pwd'];
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

    //////-----工作区相关------///////
    /**
     * 获取工作区---project
     * @param array $skewpNew
     *              ['workspaces' => '']
     * @param array $paramNew
     * 可选参数（$paramNew）
     * [
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => false     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  false    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     * @return mixed
     */
    public function getWs($skewpNew = array(),$paramNew = array())
    {
        //此处有默认值，可以不传参数
        $skewpDef = array('workspaces' => '');
        $paramDef = array('include_audit' => 'true', 'page_limit' => 100);

        //合并相关数组
        $skewp = array_merge($skewpDef,$skewpNew);
        $param = array_merge($paramDef,$paramNew);

        //生成新的可用地址
        $url = $this->pubAddr($skewp,$param);
        //发送数据
        $return = $this->doGet($url);
        //返回数组
        return json_decode($return,true);
    }

    /**
     * 获取相关工作区的信息
     * @param string $ws        传入的时工作区的id      example ---- "af05911e-0e36-4243-a39a-2d693374ab60"
     * @param array $paramNew
     * (第二个可选参数)
     * [
     *      'export' => 'false', //是否在返回的数据中包含所有元素内容。如果export = false，则返回的数据仅包含有关元素本身的信息。如果export = true，则包括所有内容，包括子元素。
     *      'include_audit' => 'false',是否在响应中包含审计属性（已创建和更新的时间戳）默认false
     * ]
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getOneWs($ws,$paramNew = array())
    {
        if (empty($ws)) {
            return array('result' => 'failed','message' => '请选择要操作的工作区');
        }
        $skewp = array('workspaces' => $ws);
        $paramDef = array('include_audit' => 'true','export' => 'true');

        //合并相关数组
        $param = array_merge($paramDef,$paramNew);

        //生成新的可用地址--发送请求
        $url = $this->pubAddr($skewp,$param);
        $return = $this->doGet($url);

        return json_decode($return,true);
    }

    /**
     * 添加工作区
     * todo 此处仅用来添加工作区，不支持在添加工作区的时候再添加intents/entities/dialog_nodes，后续单独添加
     * @param string $wsname        工作区名称
     * @return mixed
     */
    public function addWs($wsname = '',$desc = '')
    {
        if (empty($wsname)) {
            $wsname = "test" . uniqid();
        }

        $data = [
            "name" => $wsname,
            "intents" => array(),
            "entities" => array(),
            "language" => "en",
            "description" => empty($desc) ? "About {$wsname} somethings" : $desc,
            "dialog_nodes" => array(),
        ];
        $url = $this->pubAddr(array('workspaces' => ''),array());
        $return = $this->doPost($url,json_encode($data));
        return json_decode($return,true);

    }

    /**
     * 更新工作区
     * todo 此处仅用来更新工作区，不支持在更新工作区的时候操作intents/entities/dialog_nodes，后续单独添加
     * @param string $wsid              原工作区id          example ---- "af05911e-0e36-4243-a39a-2d693374ab60"
     * @param string $wsNewname         要修改成的工作区名称
     * @return array|mixed
     */
    public function upWs($wsid = '', $wsNewname = '',$desc = '')
    {
        if (empty($wsid) || empty($wsNewname)) {
            return array('result' => 'failed','message' => '请传入相关参数');
        }
        $data = [
            "name" => $wsNewname,
            "intents" => array(),
            "entities" => array(),
            "language" => "en",
            "description" => empty($desc) ? "About {$wsNewname} somethings" : $desc,
            "dialog_nodes" => array(),
        ];
        $url = $this->pubAddr(array('workspaces' => $wsid),array());
        $return = $this->doPost($url,json_encode($data));
        return json_decode($return,true);
    }

    /**
     * 删除工作区
     * @param string $wsid          workspaceid  example ---- "af05911e-0e36-4243-a39a-2d693374ab60"
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function delws($wsid = '')
    {
        if (empty($wsid)) {
            return array('result' => 'failed','message' => '请选择要操作的工作区');
        }
        $url = $this->pubAddr(array('workspaces' => $wsid));
        $return = $this->doDel($url);
        return json_decode($return,true);
    }


    ///////////------intents相关-----------////////////////

    /**
     * 列出intents
     * @param array $skewpNew
     * ($skewpNew)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'intents' => '',//'传空'
     *
     * ]
     * @param array $paramNew
     * ($paramNew)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     *     'export' =>  'false'    //是否在返回的数据中包含所有元素内容。如果export = false，则返回的数据仅包含有关元素本身的信息。如果export = true，则包括所有内容，包括子元素
     * ]
     * @return array|mixed
     */
    public function getIntents($skewpNew = array(),$paramNew = array())
    {
        if (!isset($skewpNew['workspaces']) || empty($skewpNew['workspaces'])) {
            return array('result' => 'failed','message' => '请选择要操作的工作区');
        }
        $skewpDef = array('workspaces' => $skewpNew['workspaces'],'intents' => '');
        $paramDef = array(
            'include_count' => 'true',
            'include_audit' => 'true',
            'page_limit' => '100',
            'export' => 'true',
        );

        $skewp = array_merge($skewpDef,$skewpNew);
        $param = array_merge($paramDef,$paramNew);
        $url = $this->pubAddr($skewp,$param);

        $return = $this->doGet($url);
        return json_decode($return,true);
    }

    /**
     * 获取intents的信息
     * @param array $skewp
     * @param array $paramNew
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getOneIntents($skewp = array(),$paramNew = array())
    {
        if (empty($skewp['workspaces']) || empty($skewp['intents'])) {
            return array('result' => 'failed','message' => '请传入相关才能书');
        }
        $paramDef = array('include_audit' => 'true', 'export' => 'true');
        $params = array_merge($paramDef,$paramNew);

        $url = $this->pubAddr(array('workspaces' => $skewp['workspaces'],'intents' => $skewp['intents']),$params);
        $return = $this->doGet($url);
        return json_decode($return,true);
    }

    /**
     * 添加intent
     * @param array $skewp
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function addIntents($skewp = array())
    {
        if (empty($skewp['workspaces']) || empty($skewp['intents'])) {
            return array('result' => 'failed','message' => '请传入相关参数');
        }
        $data = [
            "intent" => $skewp['intents'],
            "examples" => array(),
        ];
        $url = $this->pubAddr(array('workspaces' => $skewp['workspaces'],'intents' => ''));
        $return = $this->doPost($url,json_encode($data));
        return json_decode($return,true);
    }

    /**
     * 更新intent
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upIntents(Request $request)
    {
        $ws = $request->get('ws','');   //获取workspaces_id
        $intents = $request->get('intent','');  //获取intent
        $upintents = $request->get('upintent','');  //获取更新intent
        if (empty($ws) || empty($intents) || empty($upintents)) {
            return response_failed('请填写相应参数');
        }
        $data = [
            "intent" => $upintents,
            "examples" => [],
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'intents' => $intents]);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除intent
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delIntents(Request $request)
    {
        $ws = $request->get('ws','');   //获取workspaces_id
        $intents = $request->get('intent','');  //获取intent
        if (empty($ws) || empty($intents)) {
            return response_failed('请填写相应参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'intents' => $intents]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }


    ////////////----examples相关------------////////
    /**
     * 列出examples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * (第一个参数)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'intents' => 'intent',//必传字段
     *      'examples' => ''
     * ]
     * (第二个参数)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getExamples(Request $request)
    {
        $ws = $request->get('ws','');
        $intents = $request->get('intent','');
        if (empty($ws) || empty($intents)) {
            return response_failed('请填写相应参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,
            'intents' => $intents,
            'examples' => ''
        ],[
            'include_count' => 'true',
            'include_audit' => 'true',
            'page_limit' => '100',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加examples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addExamples(Request $request)
    {
        $ws = $request->get('ws','');
        $intents = $request->get('intent','');
        $examples = $request->get('examples','');
        if (empty($ws) || empty($intents) || empty($examples)) {
            return response_failed('请填写相应参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,
            'intents' => $intents,
            'examples' => ''
        ]);
        $data = [
            'text' => $examples,
        ];
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 获取examples信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneExamples(Request $request)
    {
        //获取数据
        $ws = $request->get('ws','');
        $intents = $request->get('intent','');
        $examples = $request->get('examples','');
        if (empty($ws) || empty($intents) || empty($examples)) {
            return response_failed('请填写相应参数');
        }
        //整合请求地址
        $url = $this->pubAddr([
            'workspaces' => $ws,
            'intents' => $intents,
            'examples' => $examples
        ],['include_audit' => 'true']);
        //发送请求并返回数据
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 更新examples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upExamples(Request $request)
    {
        //获取参数并进行判断
        $ws = $request->get('ws','');
        $intents = $request->get('intent','');
        $examples = $request->get('examples','');
        $newexamples = $request->get('newex','');
        if (empty($ws) || empty($intents) || empty($examples) || empty($newexamples)) {
            return response_failed('请填写相应参数');
        }

        $url = $this->pubAddr([
            'workspaces' => $ws,
            'intents' => $intents,
            'examples' => $examples
        ]);
        $data = [
            'text' => $newexamples,
        ];
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除examples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delExamples(Request $request)
    {
        //获取参数并进行判断
        $ws = $request->get('ws','');
        $intents = $request->get('intent','');
        $examples = $request->get('examples','');
        if (empty($ws) || empty($intents) || empty($examples)) {
            return response_failed('请填写相应参数');
        }

        $url = $this->pubAddr([
            'workspaces' => $ws,
            'intents' => $intents,
            'examples' => $examples
        ]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));

    }


    ////////-------counterexamples部分----------/////////////

    /**
     * 获取counterexamples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * (第一个参数)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'counterexamples' => '',//必传字段为空
     * ]
     * (第二个参数)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getCexamples(Request $request)
    {
        $ws = $request->get('ws','');
        if (empty($ws)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,'counterexamples' => ''],[
            'include_count' => 'true',
            'include_audit' => 'true',
            'page_limit' => '100',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取counterexamples的信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneCexamples(Request $request)
    {
        $ws = $request->get('ws','');
        $cexmaple = $request->get('cexamples','');
        if (empty($ws) || empty($cexmaple)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,'counterexamples' => $cexmaple],[
            'include_audit' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加counterexamples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCexamples(Request $request)
    {
        $ws = $request->get('ws','');
        $text = $request->get('cexamples','Make me a sandwich');
        if (empty($ws) || empty($text)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
            "text" => $text,
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'counterexamples' => '']);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 更新counterexamples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upCexamples(Request $request)
    {
        $ws = $request->get('ws','');
        $text = $request->get('cexamples','');
        $new = $request->get('newc','Make me a sandwich update');
        if (empty($ws) || empty($text) || empty($new)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
            "text" => $new,
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'counterexamples' => $text]);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除couterexamples
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delCexamples(Request $request)
    {
        $ws = $request->get('ws','');
        $text = $request->get('cexamples','');
        if (empty($ws) || empty($text)) {
            return response_failed('请输入相应的参数');
        }

        $url = $this->pubAddr(['workspaces' => $ws,'counterexamples' => $text]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }


    //////////-------entities部分------///////////

    /**
     * 列出entities
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * (第一个参数)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'entities' => '',//必传字段为空
     * ]
     * (第二个参数)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'export' =>  'false'    //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getEntity(Request $request)
    {
        $ws = $request->get('ws','');
        if (empty($ws)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,'entities' => ''],[
            'include_count' => 'true',
            'include_audit' => 'true',
            'page_limit' => '100',
            'export' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取entities的信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneEntity(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        if (empty($ws) || empty($entity)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr([
            'workspaces' => $ws,'entities' => $entity],[
            'include_audit' => 'true',
            'export' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加entity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEntity(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        if (empty($ws) || empty($entity)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
            'entity' => $entity,
            'values' => [],
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => '']);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 更新entity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upEntity(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        $new = $request->get('new','');
        if (empty($ws) || empty($entity) || empty($new)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
            'entity' => $new,
            'values' => [],
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity]);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除entity
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delEntity(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        if (empty($ws) || empty($entity)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }


    //////---mentions部分----///////////

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mentions(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        if (empty($ws) || empty($entity)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'mentions' => ''],[
            'export' => 'true',
            'include_audit' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }


    /////------------entity values--------////////////

    /**
     * 列出entity values
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * (第一个参数)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'entities' => 'entity',//必传字段
     *      'values' => '',//必传字段为空
     * ]
     * (第二个参数)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'export' =>  'false'    //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getValue(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        if (empty($ws) || empty($entity)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'values' => ''],[
            'export' => 'true',
            'include_audit' => 'true',
            'page_limit' => 100,
            'include_count' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取entity values的信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneValue(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        $value = $request->get('value','');
        if (empty($ws) || empty($entity) || empty($value)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'values' => $value],[
            'export' => 'true',
            'include_audit' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加entity value
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addValue(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        $value = $request->get('value','');
        if (empty($ws) || empty($entity) || empty($value)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
//            'type' => 'patterns',
            'value' => $value,
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'values' => '']);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 修改entity value
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upValue(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        $value = $request->get('value','');
        $new = $request->get('new','');
        if (empty($ws) || empty($entity) || empty($value) || empty($new)) {
            return response_failed('请输入相应的参数');
        }
        $data = [
//            'type' => 'patterns',
            'value' => $new,
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'values' => $value]);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除entity value
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delValue(Request $request)
    {
        $ws = $request->get('ws','');
        $entity = $request->get('entity','');
        $value = $request->get('value','');
        if (empty($ws) || empty($entity) || empty($value)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'entities' => $entity,'values' => $value]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }



    ///////------------synonyms部分----///////////
    ///
    ///
    ///
    ///





    //////--------dialog部分-----///////////////
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * * (第一个参数)[
     *      'workspaces' => 'workspace_id',//'必传字段'
     *      'dialog_nodes' => '',//必传字段为空
     * ]
     * (第二个参数)[
     *     'version' => '2018-07-10'    //版本号为必须字段，因此在地址初始化时已做处理
     *     'page_limit' => '100'        //每一页显示的数据，默认100
     *     'include_count' => 'false'     //是否包含有关返回的记录数的信息，默认false
     *     'sort' =>      //返回结果的排序。要反转排序顺序，请在值前加一个减号（-）
     *     'cursor' =>      //标识要检索的结果页面的标记
     *     'include_audit' =>  'false'    //是否在响应中包含审计属性（已创建和更新的时间戳），默认false
     * ]
     */
    public function getDialog(Request $request)
    {
        $ws = $request->get('ws','');
        if (empty($ws)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'dialog_nodes' => ''],[
            'include_audit' => 'true',
            'page_limit' => 100,
            'include_count' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取dialog_nodes县官信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneDialog(Request $request)
    {
        $ws = $request->get('ws','');
        $dialog_nodes = $request->get('node','');
        if (empty($ws) || empty($dialog_nodes)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'dialog_nodes' => $dialog_nodes],[
            'include_audit' => 'true',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 添加dialog
     * tex image ...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDialog(Request $request)
    {
        $ws = $request->get('ws','');
        if (empty($ws)) {
            return response_failed('请输入相应的参数');
        }
//        $data = [
//            "dialog_node" => "laohu",
//            "conditions" => "#laohu",
//            "output" => [
//                "generic" => [
//                    [
//                        "response_type" => "image",
//                        "values" => [
//                            [
//                                "text" => "Hi! How can I help you?"
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//            "title" => "Greeting",
//        ];
        $data = [
            "type" => "standard",
            "title" => "laohu",
            "output" => [
                "generic" => [
                    [
                        "title"=> "laohu",
                        "source"=> "https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1535444990&di=5ec03aa8bf59de246b385f911826682a&src=http://pic151.nipic.com/file/20180102/24969966_073424759037_2.jpg",
                        "description"=> "laohu",
                        "response_type"=> "image"
                    ]
                ]
            ],
//                "metadata" => [],
            "conditions" => "#laohu",
            "dialog_node" =>"Welcome1"
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'dialog_nodes' => '']);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 更新dialog_nodes
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upDialog(Request $request)
    {
        $ws = $request->get('ws','');
        $dialog_nodes = $request->get('node','');
        if (empty($ws) || empty($dialog_nodes)) {
            return response_failed('请输入相应的参数');
        }
//        $data = [
//            "dialog_node" => "laohu",
//            "conditions" => "#laohu",
//            "output" => [
//                "generic" => [
//                    [
//                        "response_type" => "image",
//                        "values" => [
//                            [
//                                "text" => "Hi! How can I help you?"
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//            "title" => "Greeting",
//        ];
        $data = [
            "type" => "standard",
            "title" => "laohu",
            "output" => [
                "generic" => [
                    [
                        "title"=> "laohu",
                        "source"=> "https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1535444990&di=5ec03aa8bf59de246b385f911826682a&src=http://pic151.nipic.com/file/20180102/24969966_073424759037_2.jpg",
                        "description"=> "laohu",
                        "response_type"=> "image"
                    ]
                ]
            ],
//                "metadata" => [],
            "conditions" => "#laohu",
            "dialog_node" =>"Welcome11"
        ];
        $url = $this->pubAddr(['workspaces' => $ws,'dialog_nodes' => $dialog_nodes]);
        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));
    }

    /**
     * 删除dialog_nodes
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delDialog(Request $request)
    {
        $ws = $request->get('ws','');
        $dialog_nodes = $request->get('node','');
        if (empty($ws) || empty($dialog_nodes)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'dialog_nodes' => $dialog_nodes]);
        $return = $this->doDel($url);
        return response_success(json_decode($return,true));
    }



    ////////---------日志部分---------///

    /**
     * 获取所有日志---暂时无用
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllLogs()
    {
        $url = $this->pubAddr(['logs' => ''],[
            'filter' =>'(language::en,request.context.metadata.deployment::testDeployment)',
        ]);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    /**
     * 获取单个工作区的日志----可用
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneLogs(Request $request)
    {
        $ws = $request->get('ws','');
        if (empty($ws)) {
            return response_failed('请输入相应的参数');
        }
        $url = $this->pubAddr(['workspaces' => $ws,'logs' => '']);
        $return = $this->doGet($url);
        return response_success(json_decode($return,true));
    }

    ////////////--------相关信息的反馈-------////////////////////

    /**
     * 获取工作区的训练状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus(Request $request)
    {
        $ws = $request->get('ws','af05911e-0e36-4243-a39a-2d693374ab60');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/status?version=2018-07-10";
        $request = $this->doGet($url);
        return response_success(json_decode($request,true));
    }

    /**
     * 根据用户输入响应信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(Request $request)
    {
        $ws = $request->get('ws','af05911e-0e36-4243-a39a-2d693374ab60');
        $text = $request->get('text','laohu1');
        $url = "https://gateway.watsonplatform.net/assistant/api/v1/workspaces/{$ws}/message?version=2018-07-10";

        $data = [
            'input' => [
                'text' => $text,
            ],
            'alternate_intents' => true,
        ];

        $return = $this->doPost($url,json_encode($data));
        return response_success(json_decode($return,true));

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
    private function doPost($url,$post_data)
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
    private function doDel($url)
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
