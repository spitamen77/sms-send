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

class EskizSms
{
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->apiUrl = Yii::$app->params['apiUrl'];
        $this->token = $this->getAccessToken(Yii::$app->params['sms_email'], Yii::$app->params['sms_password']);
    }


    /**
     * Метод для отправки одиночного SMS
     * @param string $to
     * @param string $message
     * @param $from
     * @param string $callbackUrl
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSingleSms(string $to, string $message, $from, string $callbackUrl = '')
    {
        $client = new Client();

        $response = $client->post("{$this->apiUrl}/message/sms/send", [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'form_params' => [
                'mobile_phone' => $to,
                'message' => $message,
                'from' => $from,
                'callback_url' => $callbackUrl,
            ],
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Метод для отправки массовых SMS
     * @param array $phones
     * @param string $message
     * @param $from
     * @param int $dispatchId
     * @return string
     * @throws GuzzleException
     */
    public function sendBatchSms(array $phones, string $message, $from, int $dispatchId)
    {
        $client = new Client();

        $data = [
            'messages' => $phones,
            'from' => $from,
            'dispatch_id' => $dispatchId,
        ];

        $response = $client->post("{$this->apiUrl}/message/sms/send-batch", [
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
            ],
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    public function getAccessToken(string $email, string $password): string
    {
        $client = new Client();

        $response = $client->post("{$this->apiUrl}/auth/login", [
            'form_params' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['token'])) {
            return $data['token'];
        }

        return 'false';
    }


}