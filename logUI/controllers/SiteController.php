<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Elastic;
use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\log\FileTarget;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Index界面展示
     * @return string
     */
    public function actionIndex() {
        $indices = Elastic::getIndex();

        foreach ($indices as $key => $value) {
            $index[] = $value['index'];
        }
        /***********************
         * 此处要求能够正常连接ES
         **********************/
        sort($index);
        return $this->render('elastic', [
            'index' => $index,
        ]);
    }

    /**
     * 搜索主Action
     */
    public function actionSearch() {
        set_time_limit(300);
        $param = Yii::$app->request->post();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $paramArray = json_decode($param['json'], true);
        if (array_key_exists("condition", $paramArray)) {
            $where = array();
            foreach ($paramArray['condition'] as $v) {
                $filter = trim($v['filter']);
                $field_name = trim($v['field']);
                $search_name = is_array($v['search']) ? $v['search'] : trim($v['search']);

                if ($filter == '条件' && $field_name == '字段' && $search_name == '') {
                    continue;//过滤掉前端给的默认数据
                }

                if (array_key_exists("filter", $v)) {
                    //精确查询
                    if ($filter == '=') {
                        $where['query']['bool']["must"][] = array(
                            "match" => array(
                                $field_name => $search_name,
                            )
                        );
                    }
                    //模糊查询
                    if ($filter == 'like') {
                        $where['query']['bool']["filter"][] = array(
                            "wildcard" => array(
                                $field_name => "*" . $search_name . "*",
                            )
                        );
                    }
                    //区间查询
                    if ($filter == 'between') {
                        $one_search = trim($v['search'][0]);
                        $start_time = $one_search ? $one_search : 0;
                        $two_search = trim($v['search'][1]);
                        $end_time = $two_search ? $two_search : date("Y-m-d H:i:s", time());

                        if ($start_time && $end_time) {
                            $where['query']['bool']["filter"][] = array(
                                "range" => array(
                                    $field_name => array(
                                        "gte" => $start_time,
                                        "lte" => $end_time,
                                    )
                                )
                            );
                        }
                    }
                    //条件语句
                    $FilterDsl = array('>', '<', '>=', '<=');
                    if (in_array($filter, $FilterDsl)) {
                        $filedName = $field_name;
                        $start = $search_name;
                        $whereLast = '';
                        switch ($filter) {
                            case '>=':
                                $whereLast = "gte";
                                break;
                            case '<=':
                                $whereLast = "lte";
                                break;
                            case '>':
                                $whereLast = "gt";
                                break;
                            case '<':
                                $whereLast = "lt";
                                break;
                        }
                        if ($whereLast) {
                            $where['query']['bool']["filter"][] = array(
                                "range" => array(
                                    $filedName => array(
                                        $whereLast => $start,
                                    )
                                )
                            );
                        }
                    }
                }
            }
        }else {
            $where = array();
        }
        if ($paramArray['size']) {
            $pageSize = $paramArray['size'];
            $where["size"] = $paramArray['size'];
        }
        if ($paramArray['page']) {
            $where["from"] = $paramArray['page'];
        }
        if (array_key_exists("order", $paramArray) && array_key_exists("sort", $paramArray)) {
            if ($paramArray['order'] && $paramArray['sort']) {
                $where["sort"] = array(
                    $paramArray['order'] => $paramArray['sort'],
                );
            }
        }
        //按照字段查询展示
        if (array_key_exists("field", $paramArray)) {
            $field_value = $paramArray['field'];
            $where['_source']['includes'] = $field_value;
        }
        if(array_key_exists("query", $where)){
            krsort($where['query']['bool']);
        }
        $start_time = time();
        $result = Elastic::elasticSearch($paramArray['index'], $where);
        $diff_time = time() - $start_time;

        //记录es查询超时的日志信息
        if($diff_time > 60){
            $filed_index = $paramArray['index'];
            $message = json_encode($where);
            $time = microtime(true);
            $log = new FileTarget();
            $log->logFile = __DIR__ . '/../resources/es_timeout.log';
            $log->messages[] = ["{$message}|{$diff_time}", 2, "{$filed_index}", $time];
            $log->export();//写入日志文件
        }
        if (count($result['hits']['hits']) > 0) {
            foreach ($result['hits']['hits'] as $k => $v) {
                ksort($v['_source']);
                $result['hits']['hits'][$k] = $v;
            }
        }
        $rowCount = $result['hits']['total'];
        $pageCount = ceil(($rowCount / $pageSize));

        $apiJson = [
            'total'     => $result['hits']['total'],
            'hits'      => $result['hits']['hits'],
            'pageCount' => $pageCount,
            'pageone'   => $paramArray['page']
        ];
        return $apiJson;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }
}
