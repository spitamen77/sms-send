<?php
/**
 * Created by PhpStorm.
 * PHP version 7.4
 *
 * @category PhpStorm
 * @package  Helpers.php
 * @author   Abdujalilov Dilshod <ax5165@gmail.com>
 * @license  https://www.php.net PHP License
 * @link     https://github.com/spitamen77
 * @date     2023.08.30 16:41
 */

namespace helpers;

class Helpers
{
    const WAITING = 'Waiting';     // СМС в ожидании отправления оператору
    const TRANSMTD = 'TRANSMTD';   // СМС передан сотовому оператору, но не получен статус
    const DELIVRD = 'DELIVRD';     // Доставлено
    const UNDELIV = 'UNDELIV';     // Недоставлено
    const EXPIRED = 'EXPIRED';     // Срок жизни смс истек
    const REJECTD = 'REJECTD';     // Отклонено
    const DELETED = 'DELETED';     // Ошибка при отправке запроса
}