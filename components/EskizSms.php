<?php
/**
 * Created by PhpStorm.
 * PHP version 7.4
 *
 * @category PhpStorm
 * @package  EskizSms.php
 * @author   Abdujalilov Dilshod <ax5165@gmail.com>
 * @license  https://www.php.net PHP License
 * @link     https://github.com/spitamen77
 * @date     2023.08.30 22:36
 */

namespace app\components;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use GuzzleHttp\Client;
use yii\helpers\Url;

class EskizSms
{
    private string $apiUrl;
    private string $token;
    private bool $smsEnabled;

    public function __construct()
    {
        $this->apiUrl = Yii::$app->params['apiUrl'];
        $token = $this->getAccessToken(Yii::$app->params['sms_email'], Yii::$app->params['sms_password']);

        if ($token) {
            $this->token = $token;
            $this->smsEnabled = true;
        } else {
            Yii::$app->session->setFlash('error', 'Eskiz - «Неправильный логин или пароль»');
            $this->smsEnabled = false; // Флаг, запрещающий отправку SMS
        }
    }


    /**
     * Метод для отправки одиночного SMS
     * @param string $to
     * @param string $message
     * @return string
     * @throws GuzzleException
     */
    public function sendSingleSms(string $to, string $message)
    {
        if (!$this->smsEnabled) {
            return false; // Пропуск отправки, так как токен не был успешно получен
        }

        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->post("{$this->apiUrl}/message/sms/send", [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'form_params' => [
                'mobile_phone' => $to,
                'message' => $message,
                'from' => Yii::$app->params['sms_from'],
                'callback_url' => Url::to(['sms/callback'], true),
            ],
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Метод для отправки массовых SMS
     * @param array $phones
     * @return string
     * @throws GuzzleException
     */
    public function sendBatchSms(array $phones)
    {
        if (!$this->smsEnabled) {
            return false; // Пропуск отправки, так как токен не был успешно получен
        }

        $client = new Client([
            'verify' => false,
        ]);

        $data = [
            'messages' => $phones,
            'from' => Yii::$app->params['sms_from'],
            'dispatch_id' => Yii::$app->params['sms_dispatchId'],
        ];

        $response = $client->post("{$this->apiUrl}/message/sms/send-batch", [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Статус рассылки
     * @param string $userId
     * @param int $dispatchId
     * @return mixed
     * @throws GuzzleException
     */
    public function getDispatchStatus(string $userId, int $dispatchId)
    {
        if (!$this->smsEnabled) {
            return false; // Пропуск отправки, так как токен не был успешно получен
        }

        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get("{$this->apiUrl}/message/sms/get-dispatch-status", [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'query' => [
                'user_id' => $userId,
                'dispatch_id' => $dispatchId,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Обработка и возврат данных статуса рассылки
        return $data;
    }

    /**
     * @param string $email
     * @param string $password
     * @return false|mixed|string
     */
    public function getAccessToken(string $email, string $password)
    {
        try {
            $client = new Client([
                'verify' => false, // Отключение проверки SSL-сертификата
            ]);

            $response = $client->post("{$this->apiUrl}/auth/login", [
                'form_params' => [
                    'email' => $email,
                    'password' => $password,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['token'])) {
                return $data['token'];
            } else {
                return false;
            }
        } catch (GuzzleException $e) {
            // Обработка ошибки Guzzle
            echo $e->getMessage();
        }
        return false;
    }


}