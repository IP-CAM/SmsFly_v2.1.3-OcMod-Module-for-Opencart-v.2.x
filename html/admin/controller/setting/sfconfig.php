<?php
class ControllerSettingSfconfig extends Controller {
    private $temp_arr_statuses = [];
    private $fly;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model('setting/setting');

        require_once (DIR_SYSTEM . 'library/smsflyc.php');
        $this->fly = new SmsFlyC($this->config->get('sfconfig_gate_username'),$this->config->get('sfconfig_gate_password'), $this->config->get('sfconfig_from'));
    }

    public function index() {
        $this->load->language('extension/module/smsfly');
        $this->load->language('setting/setting');
        $this->load->model('localisation/order_status');


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSetting('sfconfig', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/smsfly', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->document->setTitle($this->language->get('sfentry_nastroyki'));

        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();

        array_map(function ($status) { $this->temp_arr_statuses['SID_'.$status['order_status_id']] = $status['name'];}, $order_statuses);

        $data_options = [
            'action'            => $this->url->link('extension/module/smsfly', 'user_token=' . $this->session->data['user_token'], true),
            'cancel'            => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'header'            => $this->load->controller('common/header'),
            'column_left'       => $this->load->controller('common/column_left'),
            'footer'            => $this->load->controller('common/footer'),
            'sfconfig_balance'  => $this->fly->balance, //Balance
            'order_statuses'    => $this->temp_arr_statuses, //список статусов
            'names'             => $this->fly->names,
            'breadcrumbs'       => [
                ['text' => $this->language->get('text_home'), 'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)],
                ['text' => $this->language->get('sftext_setting'), 'href' => $this->url->link('extension/module/smsfly', 'user_token=' . $this->session->data['user_token'], true)],
            ],
            'success'           => isset($this->session->data['success']) ? $this->session->data['success'] : '',
            'flyauth'           => $this->fly->auth
        ];
        $data_lang = [
            'sfbutton_save'                 => $this->language->get('button_save'),
            'sfbutton_cancel'               => $this->language->get('button_cancel'),
            'sfbutton_save_template'        => $this->language->get('sfbutton_save_template'),
            'sftext_setting'                => $this->language->get('sftext_setting'),
            'sftext_edit'                   => $this->language->get('sftext_edit'),
            'sftext_ne_vibrano'             => $this->language->get('sftext_ne_vibrano'),
            'text_yes'                      => $this->language->get('text_yes'),
            'text_no'                       => $this->language->get('text_no'),
            'sftext_nagim'                  => $this->language->get('sftext_nagim'),
            'sfentry_gate_username'         => $this->language->get('sfentry_gate_username'),
            'sfentry_gate_password'         => $this->language->get('sfentry_gate_password'),
            'sfentry_from'                  => $this->language->get('sfentry_from'),
            'sfhelp_from'                   => $this->language->get('sfhelp_from'),
            'sfentry_balance'               => $this->language->get('sfentry_balance'),
            'sfentry_counter_ok'            => $this->language->get('sfentry_counter_ok'),
            'sfhelp_counter_ok'             => $this->language->get('sfhelp_counter_ok'),
            'sfentry_counter_err'           => $this->language->get('sfentry_counter_err'),
            'sfhelp_counter_err'            => $this->language->get('sfhelp_counter_err'),
            'sfentry_translit'              => $this->language->get('sfentry_translit'),
            'sfhelp_translit'               => $this->language->get('sfhelp_translit'),
            'sfentry_copy_to_comment'       => $this->language->get('sfentry_copy_to_comment'),
            'sfhelp_copy_to_comment'        => $this->language->get('sfhelp_copy_to_comment'),
            'sfentry_alert_manager'         => $this->language->get('sfentry_alert_manager'),
            'sfentry_to_manager'            => $this->language->get('sfentry_to_manager'),
            'sfhelp_to_manager'             => $this->language->get('sfhelp_to_manager'),
            'sfentry_status_check_manager'  => $this->language->get('sfentry_status_check_manager'),
            'sfhelp_status_check_manager'   => $this->language->get('sfhelp_status_check_manager'),
            'sfentry_order_status_manager'  => $this->language->get('sfentry_order_status_manager'),
            //'sfhelp_order_status_manager' => $this->language->get('sfhelp_order_status_manager'),
            'sfentry_template_manager'      => $this->language->get('sfentry_template_manager'),
            'sfhelp_template_manager'       => $this->language->get('sfhelp_template_manager'),
            'sfentry_alert_client'          => $this->language->get('sfentry_alert_client'),
            'sfentry_status_check_client'   => $this->language->get('sfentry_status_check_client'),
            'sfhelp_status_check_client'    => $this->language->get('sfhelp_status_check_client'),
            'sfentry_order_status_client'   => $this->language->get('sfentry_order_status_client'),
            //'sfhelp_order_status_client' => $this->language->get('sfhelp_order_status_client'),
            'sfhelp_template_client'        => $this->language->get('sfhelp_template_client'),
            'sfentry_template_client'       => $this->language->get('sfentry_template_client'),
        ];
        $data_post = [
            'sfconfig_gate_username'    => isset($this->request->post['sfconfig_gate_username']) ? $this->request->post['sfconfig_gate_username'] : $this->config->get('sfconfig_gate_username'), //Login
            'sfconfig_gate_password'    => isset($this->request->post['sfconfig_gate_password']) ? $this->request->post['sfconfig_gate_password'] : $this->config->get('sfconfig_gate_password'), //Password
            'sfconfig_from'             => htmlspecialchars_decode(isset($this->request->post['sfconfig_from']) ? $this->request->post['sfconfig_from'] : $this->config->get('sfconfig_from')), //Alfaname
            'sfconfig_counter_ok'       => isset($this->request->post['sfconfig_counter_ok']) ? $this->request->post['sfconfig_counter_ok'] : $this->config->get('sfconfig_counter_ok'), //SMS counter
            'sfconfig_counter_err'      => isset($this->request->post['sfconfig_counter_err']) ? $this->request->post['sfconfig_counter_err'] : $this->config->get('sfconfig_counter_err'), //Error counter
            'sfconfig_translit'         => isset($this->request->post['sfconfig_translit']) ? $this->request->post['sfconfig_translit'] : $this->config->get('sfconfig_translit'), //Translit
            'sfconfig_copy_to_comment'  => isset($this->request->post['sfconfig_copy_to_comment']) ? $this->request->post['sfconfig_copy_to_comment'] : $this->config->get('sfconfig_copy_to_comment'), //Copy to comment
            'sfconfig_alert_manager'    => isset($this->request->post['sfconfig_alert_manager']) ? $this->request->post['sfconfig_alert_manager'] : $this->config->get('sfconfig_alert_manager'), //SMS manager
            'sfconfig_to_manager'       => isset($this->request->post['sfconfig_to_manager']) ? $this->request->post['sfconfig_to_manager'] : $this->config->get('sfconfig_to_manager'), //Phone mamager
            'sfconfig_alert_client'     => isset($this->request->post['sfconfig_alert_client']) ? $this->request->post['sfconfig_alert_client'] : $this->config->get('sfconfig_alert_client'), //SMS client
            'sfconfig_status_check_manager' => isset($this->request->post['sfconfig_status_check_manager']) ? $this->request->post['sfconfig_status_check_manager'] : (array)$this->config->get('sfconfig_status_check_manager'), //Manager statuses
            'sfconfig_templates_manager'    => isset($this->request->post['sfconfig_templates_manager']) ? $this->request->post['sfconfig_templates_manager'] : array_merge( $this->temp_arr_statuses, (array)$this->config->get('sfconfig_templates_manager') ), //Templates manager
            'sfconfig_status_check_client'  => isset($this->request->post['sfconfig_status_check_client']) ? $this->request->post['sfconfig_status_check_client'] : (array)$this->config->get('sfconfig_status_check_client'), //Client statuses
            'sfconfig_templates_client'     => isset($this->request->post['sfconfig_templates_client']) ? $this->request->post['sfconfig_templates_client'] : array_merge($this->temp_arr_statuses, (array)$this->config->get('sfconfig_templates_client')), //Templates client

        ];

        unset($this->session->data['success']);
        $this->response->setOutput($this->load->view('extension/module/smsfly', array_merge($data_options, $data_lang, $data_post)));
    }

    public function getBalance() {
        echo $this->fly->balance;
        if ( (float)$this->fly->balance > 0 ) {
            $this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_balance', $this->fly->balance);
        }
        exit;
    }

    public function sendSMS() {
        $data = json_decode(file_get_contents('php://input'),true);

        if ( isset($data['to_phone']) && isset($data['text']) ) {
            if ( $this->config->get('sfconfig_translit') == 1 ) $data['text'] = SMSflyC::translit($data['text']);
            $response = $this->fly->send($data['to_phone'], $data['text']);
            echo json_encode($response,JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode('Wrong data');
        };

        if ( isset($response['success']) && $response['success'] ) {
            $this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_counter_ok', 1 + $this->config->get('sfconfig_counter_ok'));
        } else {
            $this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_counter_err', 1 + $this->config->get('sfconfig_counter_err'));
        }
        exit;
    }
}