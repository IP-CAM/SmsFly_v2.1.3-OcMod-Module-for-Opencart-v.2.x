<?php
$_['tab_smsfly']                           = 'SMS-Fly';

//setting/sfconfig
$_['sfentry_to_manager']                   = 'Номер телефона менеджера';
$_['sfentry_status_check_manager']         = 'Отправлять при статусах';
$_['sfentry_status_check_client']          = 'Отправлять при статусах';
$_['sfentry_from']                         = 'Альфаимя';
$_['sfentry_template_manager']             = 'Текст шаблона СМС для статуса выше';
$_['sfentry_template_client']              = 'Текст шаблона СМС для статуса выше';
$_['sfentry_gate_username']                = 'Логин на сервисе';
$_['sfentry_gate_password']                = 'Пароль на сервисе';
$_['sfentry_alert_manager']                = 'Отправлять СМС менеджеру';
$_['sfentry_alert_client']                 = 'Отправлять СМС клиенту';
$_['sfentry_order_status_manager']         = 'Шаблон для статуса заказа';
$_['sfentry_order_status_client']          = 'Шаблон для статуса заказа';
$_['sfentry_balance']                      = 'Баланс (грн.)';
$_['sfentry_counter_ok']                   = 'Счетчик сообщений';
$_['sfentry_counter_err']                  = 'Счетчик ошибок';
$_['sfentry_translit']                     = 'Использовать транслитерацию';
$_['sfentry_copy_to_comment']              = 'Текст СМС в комментарий';

$_['sfhelp_to_manager']                    = 'Можно несколько, указывать через запятую, в международном формате 380YYXXXXXXX';
$_['sfhelp_from']                          = 'Не более 11 символов';
$_['sfhelp_status_check_manager']          = 'Пометьте статусы при наступлении которых будут отправлены СМС менеджеру';
$_['sfhelp_status_check_client']           = 'Пометьте статусы при наступлении которых будут отправлены СМС клиенту';
$_['sfhelp_template_manager']              = 'Можно использовать теги:<br/>{NAME} - имя клиента<br/>{SONAME} - фамилия клиента<br/>{ID} - номер заказа<br/>{DATE} - дата заказа<br/>{TIME} - время заказа<br/>{SUM} - сумма заказа<br/>{PHONE} - телефон клиента<br/>{STATUS} - новый статус заказа<br/>{COMMENT} - комментарий при смене статуса';
$_['sfhelp_template_client']               = 'Можно использовать теги:<br/>{NAME} - имя клиента<br/>{SONAME} - фамилия клиента<br/>{ID} - номер заказа<br/>{DATE} - дата заказа<br/>{TIME} - время заказа<br/>{SUM} - сумма заказа<br/>{PHONE} - телефон клиента<br/>{STATUS} - новый статус заказа<br/>{COMMENT} - комментарий из заказа';
$_['sfhelp_counter_ok']                    = 'Количество успешных попыток отправки, можно обнулять указав 0';
$_['sfhelp_counter_err']                   = 'Количество ошибок при отправке, можно обнулять указав 0';
$_['sfhelp_translit']                      = 'Перед отправкой конвертировать текст смс из кирилицы в латиницу';
$_['sfhelp_copy_to_comment']               = 'Текст комментария в истории заказов заменить на текст отправленной СМС';

//common/header
$_['sfentry_otpravitsms']                  = "Отправить СМС";
$_['sfentry_nomerpoluchat']                = "Номер получателя";
$_['sfentry_tekstsoobshenia']              = "Текст сообщения не может быть пустым";
$_['sfentry_soobshenie']                   = "Сообщение";
$_['sfentry_statistika']                   = "Статистика";
$_['sfentry_oshibok']                      = "Ошибок";
$_['sfentry_otpravleno']                   = "Отправлено";
$_['sfentry_otpravit']                     = "Отправить";
$_['sfentry_nastroyki']                    = "Настройки";
$_['sfentry_oshibka']                      = "Ошибка";
$_['sfentry_nagmite']                      = "Нажмите для обновления";

//
$_['sftext_setting']                       = "SMS-Fly Настройка";
$_['sftext_edit']                          = "Конфигурация SMS-Fly шлюза";
$_['sftext_ne_vibrano']                    = '--- Не выбрано ---';
$_['sftext_nagim']                         = 'После ввода каждого шаблона нажимайте эту кнопку';

$_['sfbutton_save_template']               = "Сохранить шаблон";





