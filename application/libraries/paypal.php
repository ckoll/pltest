<?

class Paypal {

    protected $_errors = array();
    protected $_credentials;

    public function __construct() {
        $CI = get_instance();

//        $paypal_db = $CI->db->get('paypal')->row();
//        $this->_credentials = array(
//            'USER' => $paypal_db->user,
//            'PWD' => $paypal_db->pass,
//            'SIGNATURE' => $paypal_db->signature,
//        );
        $this->_credentials = array(
            'USER' => 'it-web_1341392802_biz_api1.yandex.ru',
            'PWD' => '1341392826',
            'SIGNATURE' => 'Av5uDzFCJVkOO59EJ2pnWatO2fbnAXAkhqxjbnolPDPmw5h9SqVYnGJm',
        );
    }

    /**
     * Указываем, куда будет отправляться запрос
     * Реальные условия - https://api-3t.paypal.com/nvp
     * Песочница - https://api-3t.sandbox.paypal.com/nvp
     * @var string
     */
    protected $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';
    protected $_version = '74.0';

    /**
     * Сформировываем запрос
     *
     * @param string $method Данные о вызываемом методе перевода
     * @param array $params Дополнительные параметры
     * @return array / boolean Response array / boolean false on failure
     */
    public function request($method, $params = array()) {
        $this->_errors = array();
        if (empty($method)) { // Проверяем, указан ли способ платежа
            $this->_errors = array('Не указан метод перевода средств');
            return false;
        }

        $requestParams = array(
            'METHOD' => $method,
            'VERSION' => $this->_version
                ) + $this->_credentials;

        $request = http_build_query($requestParams + $params);

        $curlOptions = array(
            CURLOPT_URL => $this->_endPoint,
            CURLOPT_VERBOSE => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => FILES.'mcacert.pem', // Файл сертификата
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);

        // Проверяем, нету ли ошибок в инициализации cURL
        if (curl_errno($ch)) {
            $this->_errors = curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            $responseArray = array();
            parse_str($response, $responseArray); // Разбиваем данные, полученные от NVP в массив
            return $responseArray;
        }
    }

    public function err() {
        return $this->_errors;
    }

}