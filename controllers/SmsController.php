<?php

namespace app\controllers;

use app\components\EskizSms;
use app\helpers\Helpers;
use app\models\Sms;
use app\models\SmsSearch;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['create', 'index'],
                    'rules' => [
                        [
                            'actions' => ['create','index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Sms models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SmsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sms model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException|GuzzleException if the model cannot be found
     */
    public function actionView($id)
    {
        $eskizSms = new EskizSms();
        $model = $this->findModel($id);

        $statusData = $eskizSms->getDispatchStatus($model->user_sms_id, Yii::$app->params['sms_dispatchId']);

        if ($statusData) {
            $model->status = $statusData['status'];
            $model->message_id = $statusData['message_id'];
            $model->status_date = $statusData['status_date'];
            $model->sms_count = $statusData['sms_count'];
            $model->save();
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Sms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws GuzzleException
     */
    public function actionCreate()
    {
        $model = new Sms();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $pieces = explode(",", str_replace(" ", '', $model->phone_number));

                $phones = [];

                foreach ($pieces as $piece) {
                    if (strlen($piece) != 12) {
                        continue;
                    }
                    $uniqId = uniqid();

                    $model2 = new Sms();
                    $model2->phone_number = $piece;
                    $model2->status = Helpers::WAITING;
                    $model2->message = $model->message;
                    $model2->user_sms_id = $uniqId;
                    $model2->sms_count = 1;
                    $model2->save(false);

                    $phones[] = [
                        'user_sms_id' => $uniqId, // Уникальный идентификатор для каждого сообщения
                        'to' => $piece,
                        'text' => $model->message,
                    ];;
                }

                $sms = new EskizSms();

                if ($sms->sendBatchSms($phones)) {
                    Yii::$app->session->setFlash('success', 'Успешно выполнено!');
                } else {
                    Yii::$app->session->setFlash('error', 'Рассылка не отправлена');
                }

                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCallback()
    {
        $callbackData = Yii::$app->request->post(); // Получаем данные POST обратного вызова

        if (isset($callbackData['phone_number']) && isset($callbackData['status'])) {
            $userPhone = $callbackData['user_sms_id'];
            $status = $callbackData['status'];
            $messageId = $callbackData['message_id'] ?? null; // Если данные message_id могут отсутствовать
            $statusDate = $callbackData['status_date'] ?? null; // Если данные status_date могут отсутствовать

            $smsModel = Sms::findOne(['phone_number' => $userPhone, 'status' => Helpers::WAITING]);

            if ($smsModel !== null) {
                $smsModel->message_id = $messageId;
                $smsModel->status = $status;
                $smsModel->status_date = $statusDate;
                $smsModel->save(false);
            }
        }

        // Возвращаем пустой ответ, чтобы подтвердить успешное получение данных
        Yii::$app->response->setStatusCode(200);
    }

    /**
     * Updates an existing Sms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        return $this->redirect(['index']);
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->message = Url::to(['sms/callback'], true);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Sms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sms::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
