<?php
class ControllerSettingSfconfig extends Controller {
	public function index() {
		$this->load->language('setting/smsflylang');
		$this->load->language('setting/setting');
		$this->load->model('setting/setting');
        $this->load->model('localisation/order_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('sfconfig', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            //$data['dump'] = var_dump($this->request->post); exit;

			$this->response->redirect($this->url->link('setting/sfconfig', 'token=' . $this->session->data['token'], true));
		}

		$this->document->setTitle($this->language->get('sfentry_nastroyki'));

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses(); //список статусов

		$data['sfbutton_save'] = $this->language->get('button_save');
		$data['sfbutton_cancel'] = $this->language->get('button_cancel');
		$data['sfbutton_save_template'] = $this->language->get('sfbutton_save_template');

		$data['sftext_setting'] = $this->language->get('sftext_setting');
		$data['sftext_edit'] = $this->language->get('sftext_edit');
        $data['sftext_ne_vibrano'] = $this->language->get('sftext_ne_vibrano');
   		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['sftext_nagim'] = $this->language->get('sftext_nagim');

		//Логин
        $data['sfentry_gate_username'] = $this->language->get('sfentry_gate_username');
        if (isset($this->request->post['sfconfig_gate_username'])) {
			$data['sfconfig_gate_username'] = $this->request->post['sfconfig_gate_username'];
		} else {
			$data['sfconfig_gate_username'] = $this->config->get('sfconfig_gate_username');
		}

		//Пароль
        $data['sfentry_gate_password'] = $this->language->get('sfentry_gate_password');
        if (isset($this->request->post['sfconfig_gate_password'])) {
			$data['sfconfig_gate_password'] = $this->request->post['sfconfig_gate_password'];
		} else {
			$data['sfconfig_gate_password'] = $this->config->get('sfconfig_gate_password');
		}

		//Альфаимя
        $data['sfentry_from'] = $this->language->get('sfentry_from');
        $data['sfhelp_from'] = $this->language->get('sfhelp_from');
        if (isset($this->request->post['sfconfig_from'])) {
			$data['sfconfig_from'] = $this->request->post['sfconfig_from'];
		} else {
			$data['sfconfig_from'] = $this->config->get('sfconfig_from');
		}

		//Баланс
        $data['sfentry_balance'] = $this->language->get('sfentry_balance');
        $data['sfconfig_balance'] = $this->sfGetBalance();

        //Счетчик сообщений
        $data['sfentry_counter_ok'] = $this->language->get('sfentry_counter_ok');
        $data['sfhelp_counter_ok'] = $this->language->get('sfhelp_counter_ok');
        if (isset($this->request->post['sfconfig_counter_ok'])) {
			$data['sfconfig_counter_ok'] = $this->request->post['sfconfig_counter_ok'];
		} else {
			$data['sfconfig_counter_ok'] = $this->config->get('sfconfig_counter_ok');
		}

		//Счетчик ошибок
        $data['sfentry_counter_err'] = $this->language->get('sfentry_counter_err');
        $data['sfhelp_counter_err'] = $this->language->get('sfhelp_counter_err');
   		if (isset($this->request->post['sfconfig_counter_err'])) {
			$data['sfconfig_counter_err'] = $this->request->post['sfconfig_counter_err'];
		} else {
			$data['sfconfig_counter_err'] = $this->config->get('sfconfig_counter_err');
		}

        //Транслит
        $data['sfentry_translit'] = $this->language->get('sfentry_translit');
        $data['sfhelp_translit'] = $this->language->get('sfhelp_translit');
        if (isset($this->request->post['sfconfig_translit'])) {
            $data['sfconfig_translit'] = $this->request->post['sfconfig_translit'];
        } else {
            $data['sfconfig_translit'] = $this->config->get('sfconfig_translit');
        }

        //Копировать в комментарий
        $data['sfentry_copy_to_comment'] = $this->language->get('sfentry_copy_to_comment');
        $data['sfhelp_copy_to_comment'] = $this->language->get('sfhelp_copy_to_comment');
        if (isset($this->request->post['sfconfig_copy_to_comment'])) {
            $data['sfconfig_copy_to_comment'] = $this->request->post['sfconfig_copy_to_comment'];
        } else {
            $data['sfconfig_copy_to_comment'] = $this->config->get('sfconfig_copy_to_comment');
        }

		//Смс менеджеру
        $data['sfentry_alert_manager'] = $this->language->get('sfentry_alert_manager');
   		if (isset($this->request->post['sfconfig_alert_manager'])) {
			$data['sfconfig_alert_manager'] = $this->request->post['sfconfig_alert_manager'];
		} else {
			$data['sfconfig_alert_manager'] = $this->config->get('sfconfig_alert_manager');
		}

        //Телефон менеджера
		$data['sfentry_to_manager'] = $this->language->get('sfentry_to_manager');
        $data['sfhelp_to_manager'] = $this->language->get('sfhelp_to_manager');
   		if (isset($this->request->post['sfconfig_to_manager'])) {
			$data['sfconfig_to_manager'] = $this->request->post['sfconfig_to_manager'];
		} else {
			$data['sfconfig_to_manager'] = $this->config->get('sfconfig_to_manager');
		}

		//Отправлять при статусах менеджера
        $data['sfentry_status_check_manager'] = $this->language->get('sfentry_status_check_manager');
        $data['sfhelp_status_check_manager'] = $this->language->get('sfhelp_status_check_manager');

        if (isset($this->request->post['sfconfig_status_check_manager'])) {
            $data['sfconfig_status_check_manager'] = $this->request->post['sfconfig_status_check_manager'];
        } elseif ($this->config->get('sfconfig_status_check_manager')) {
            $data['sfconfig_status_check_manager'] = $this->config->get('sfconfig_status_check_manager');
        } else {
            $data['sfconfig_status_check_manager'] = array();
        }

        //Выбор статуса
        $data['sfentry_order_status_manager'] = $this->language->get('sfentry_order_status_manager');
        //$data['sfhelp_order_status_manager'] = $this->language->get('sfhelp_order_status_manager');

        //Шаблоны менеджера
        $data['sfentry_template_manager'] = $this->language->get('sfentry_template_manager');
        $data['sfhelp_template_manager'] = $this->language->get('sfhelp_template_manager');

        if (isset($this->request->post['sfconfig_templates_manager'])) {
            $data['sfconfig_templates_manager'] = $this->request->post['sfconfig_templates_manager'];
        } elseif ($this->config->get('sfconfig_templates_manager')) {
            $data['sfconfig_templates_manager'] = $this->config->get('sfconfig_templates_manager');
        } else {
            $data['sfconfig_templates_manager'] = array_fill(0,30,'');
        }
        //

        //Смс клиенту
        $data['sfentry_alert_client'] = $this->language->get('sfentry_alert_client');
        if (isset($this->request->post['sfconfig_alert_client'])) {
            $data['sfconfig_alert_client'] = $this->request->post['sfconfig_alert_client'];
        } else {
            $data['sfconfig_alert_client'] = $this->config->get('sfconfig_alert_client');
        }

        //Отправлять при статусах клиента
        $data['sfentry_status_check_client'] = $this->language->get('sfentry_status_check_client');
        $data['sfhelp_status_check_client'] = $this->language->get('sfhelp_status_check_client');

        if (isset($this->request->post['sfconfig_status_check_client'])) {
            $data['sfconfig_status_check_client'] = $this->request->post['sfconfig_status_check_client'];
        } elseif ($this->config->get('sfconfig_status_check_client')) {
            $data['sfconfig_status_check_client'] = $this->config->get('sfconfig_status_check_client');
        } else {
            $data['sfconfig_status_check_client'] = array();
        }

        //Выбор статуса
        $data['sfentry_order_status_client'] = $this->language->get('sfentry_order_status_client');
        //$data['sfhelp_order_status_client'] = $this->language->get('sfhelp_order_status_client');

        //Шаблоны менеджера
        $data['sfentry_template_client'] = $this->language->get('sfentry_template_client');
        $data['sfhelp_template_client'] = $this->language->get('sfhelp_template_client');

        //Шаблоны клиента
        $data['sfentry_template_client'] = $this->language->get('sfentry_template_client');
        if (isset($this->request->post['sfconfig_templates_client'])) {
            $data['sfconfig_templates_client'] = $this->request->post['sfconfig_templates_client'];
        } elseif ($this->config->get('sfconfig_templates_client')) {
            $data['sfconfig_templates_client'] = $this->config->get('sfconfig_templates_client');
        } else {
            $data['sfconfig_templates_client'] = array_fill(0,30,'');
        }
        //

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('sftext_setting'),
			'href' => $this->url->link('setting/sfconfig', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('setting/sfconfig', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('setting/sfconfig.tpl', $data));
	}

	//SMS-Fly
	public function sfSMSSend() {
		if (isset($_POST['sfphone'])) {
			$phonedst = $_POST['sfphone'];
		} else {
			$phonedst = $this->config->get('sfconfig_to_manager');
		};

		if (isset($_POST['sftext'])) {
			$textmsg = $_POST['sftext'];
		} else {
			$textmsg = "Sms-Fly: Test message. Тестовое сообщение.";
		};

		require_once (DIR_SYSTEM . 'library/smsflyc.php');
		$options = array(
			'SMSFLY_PHONE'  => $phonedst,
			'SMSFLY_AI'     => $this->config->get('sfconfig_from'),
			'SMSFLY_LOGIN'  => $this->config->get('sfconfig_gate_username'),
			'SMSFLY_PASS'   => $this->config->get('sfconfig_gate_password'),
			'SMSFLY_TEXT'   => $textmsg
		);

		$sms = new SmsFlyC($options['SMSFLY_LOGIN'],$options['SMSFLY_PASS'],$options['SMSFLY_AI']);
		return $sms->sfSendSms($options);
	}

	public function asfSMSSend() {
		$objarr = array('apiresponse' => $this->sfSMSSend());
		echo json_encode($objarr);
	}

	public function sfGetBalance() {
		$login = $this->config->get('sfconfig_gate_username');
		$pass = $this->config->get('sfconfig_gate_password');
		require_once (DIR_SYSTEM . 'library/smsflyc.php');

		$sms = new SmsFlyC($login,$pass);
		$balance = $sms->sfBalance();
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_balance', $balance);
		return (float)$balance;
	}

	public function asfGetBalance() {
		echo $this->sfGetBalance();
	}

	public function sfCountSucces() {
		$this->load->model('setting/setting');
		$successsf = (int)$this->config->get('sfconfig_counter_ok') + 1;
		$this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_counter_ok', $successsf);
	}

	public function sfCountError() {
		$this->load->model('setting/setting');
		$errorsf = (int)$this->config->get('sfconfig_counter_err') + 1;
		$this->model_setting_setting->editSettingValue('sfconfig', 'sfconfig_counter_ok', $errorsf);
	}
}