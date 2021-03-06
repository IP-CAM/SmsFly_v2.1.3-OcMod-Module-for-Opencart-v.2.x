<?php echo $header ?><?php echo $column_left ?>
<!--?php echo $dump; ?-->
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" id="sfbutton-save" form="form-setting" data-toggle="tooltip" title="<?php echo $sfbutton_save ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel ?>" data-toggle="tooltip" title="<?php echo $sfbutton_cancel ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
			<h1><?php echo $sftext_setting ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $sftext_edit ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">

					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab">Общие</a></li>
						<li><a href="#tab-client" data-toggle="tab">Клиенту</a></li>
						<li><a href="#tab-manager" data-toggle="tab">Менеджеру</a></li>
						<!--<li><a href="#tab-info" data-toggle="tab">Другое</a></li>-->
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<fieldset>
								<legend>Учетные данные SMSfly</legend>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-sfconfig-gate-username"><?php echo $sfentry_gate_username ?></label>
									<div class="col-sm-10">
										<input type="text" name="sfconfig_gate_username" value="<?php echo $sfconfig_gate_username ?>" placeholder="<?php echo $sfentry_gate_username ?>" id="input-sfconfig-gate-username" class="form-control" />
										<?php if ( $flyauth == false ) { ?>
										<div class="text-danger">Ошибка авторизации на сервисе!</div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-config-sms-gate-password-sf"><?php echo $sfentry_gate_password ?></label>
									<div class="col-sm-10">
										<input required type="password" name="sfconfig_gate_password" value="<?php echo $sfconfig_gate_password ?>" placeholder="<?php echo $sfentry_gate_password ?>" id="input-sfconfig--gate-password" class="form-control" />
									</div>
								</div>
							</fieldset>

							<fieldset>
								<legend>Параметры отправки</legend>
								<div class="form-group required">
									<label class="col-sm-2 control-label" for="input-config-sms-from-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_from ?>"><?php echo $sfentry_from ?></span></label>
									<div class="col-sm-10">
										<!--<input required type="text" name="sfconfig_from" value="<?php echo $sfconfig_from ?>" placeholder="<?php echo $sfentry_from ?>" id="input-sfconfig-from" class="form-control" />-->
										<select id="input-sfconfig-from" class="form-control" name="sfconfig_from">
											<option value="InfoCentr"><?php echo $sftext_ne_vibrano ?></option>
											<?php foreach ($names as $name) { ?>
											<option value="<?php echo $name ?>" <?php if ($name == $sfconfig_from) { ?> selected <?php } ?>><?php echo $name ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-error-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_translit ?>"><?php echo $sfentry_translit ?></span></label>
									<div class="col-sm-10">
										<label class="radio-inline">
											<?php if ( $sfconfig_translit ) { ?>
											<input type="radio" name="sfconfig_translit" value="1" checked="checked" />
											<?php echo $text_yes ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_translit" value="1" />
											<?php echo $text_yes ?>
											<?php } ?>

										</label>
										<label class="radio-inline">
											<?php if ( !$sfconfig_translit ) { ?>
											<input type="radio" name="sfconfig_translit" value="0" checked="checked" />
											<?php echo $text_no ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_translit" value="0" />
											<?php echo $text_no ?>
											<?php } ?>

										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-error-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_copy_to_comment ?>"><?php echo $sfentry_copy_to_comment ?></span></label>
									<div class="col-sm-10">
										<label class="radio-inline">
											<?php if ( $sfconfig_copy_to_comment ) { ?>
											<input type="radio" name="sfconfig_copy_to_comment" value="1" checked="checked" />
											<?php echo $text_yes ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_copy_to_comment" value="1" />
											<?php echo $text_yes ?>
											<?php } ?>

										</label>
										<label class="radio-inline">
											<?php if ( !$sfconfig_copy_to_comment ) { ?>
											<input type="radio" name="sfconfig_copy_to_comment" value="0" checked="checked" />
											<?php echo $text_no ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_copy_to_comment" value="0" />
											<?php echo $text_no ?>
											<?php } ?>
										</label>
									</div>
								</div>
							</fieldset>

							<fieldset>
								<legend>Информация</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-success-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_counter_ok ?>"><?php echo $sfentry_counter_ok ?></span></label>
									<div class="col-sm-10">
										<input type="number" name="sfconfig_counter_ok" value="<?php echo $sfconfig_counter_ok ?>" placeholder="<?php echo $sfconfig_counter_ok ?>" id="input-config-sms-success-sf" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-error-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_counter_err ?>"><?php echo $sfentry_counter_err ?></span></label>
									<div class="col-sm-10">
										<input type="number" name="sfconfig_counter_err" value="<?php echo $sfconfig_counter_err ?>" placeholder="<?php echo $sfconfig_counter_err ?>" id="input-config-sms-error-sf" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-sms-balance-sf"><?php echo $sfentry_balance ?></label>
									<div class="col-sm-10">
										<input readonly type="text" name="sfconfig_balance" value="<?php echo $sfconfig_balance ?>" placeholder="<?php echo $sfentry_balance ?>" id="input-sfconfig-balance" class="form-control" />
									</div>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-client">
							<fieldset>
								<legend>Отправка смс клиенту</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $sfentry_alert_client ?></label>
									<div class="col-sm-10">
										<label class="radio-inline">
											<?php if ( $sfconfig_alert_client ) { ?>
											<input type="radio" name="sfconfig_alert_client" value="1" checked="checked" />
											<?php echo $text_yes ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_alert_client" value="1" />
											<?php echo $text_yes ?>
											<?php } ?>

										</label>
										<label class="radio-inline">
											<?php if ( !$sfconfig_alert_client ) { ?>
											<input type="radio" name="sfconfig_alert_client" value="0" checked="checked" />
											<?php echo $text_no ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_alert_client" value="0" />
											<?php echo $text_no ?>
											<?php } ?>

										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-complete-status"><span data-toggle="tooltip" title="<?php echo $sfhelp_status_check_client ?>"><?php echo $sfentry_status_check_client ?></span></label>
									<div class="col-sm-10">
										<div class="well well-sm" style="height: 150px; overflow: auto;">
											<?php foreach ( $order_statuses as $sid => $order_status ) { ?>
											<div class="checkbox">
												<label>
													<?php if ( in_array($sid, $sfconfig_status_check_client) ) { ?>
													<input type="checkbox" name="sfconfig_status_check_client[]" value="<?php echo $sid ?>" checked="checked" />
													<?php echo $order_status ?>
													<?php } else { ?>
													<input type="checkbox" name="sfconfig_status_check_client[]" value="<?php echo $sid ?>" />
													<?php echo $order_status ?>
													<?php } ?>
												</label>
											</div>
											<?php } ?>

										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>Настройка шаблонов текста смс</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-order-status"><?php echo $sfentry_order_status_client ?></span></label>
									<div class="col-sm-10">
										<select id="sf-order-status-client" class="form-control">
											<option><?php echo $sftext_ne_vibrano ?></option>
											<?php foreach ( $order_statuses as $sid => $order_status ) { ?>
											<option value="<?php echo $sid ?>"><?php echo $order_status ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-message-status-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_template_client ?>"><?php echo $sfentry_template_client ?></span></label>
									<div class="col-sm-10">
										<textarea rows="5" placeholder="<?php echo $sfentry_template_client ?>" id="textarea-template-client" class="form-control"></textarea>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-manager">
							<fieldset>
								<legend>Отправка смс менеджеру</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $sfentry_alert_manager ?></label>
									<div class="col-sm-10">
										<label class="radio-inline">
											<?php if ( $sfconfig_alert_manager ) { ?>
											<input type="radio" name="sfconfig_alert_manager" value="1" checked="checked" />
											<?php echo $text_yes ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_alert_manager" value="1" />
											<?php echo $text_yes ?>
											<?php } ?>

										</label>
										<label class="radio-inline">
											<?php if ( !$sfconfig_alert_manager ) { ?>
											<input type="radio" name="sfconfig_alert_manager" value="0" checked="checked" />
											<?php echo $text_no ?>
											<?php } else { ?>
											<input type="radio" name="sfconfig_alert_manager" value="0" />
											<?php echo $text_no ?>
											<?php } ?>

										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-config-sms-to-sf"><span data-toggle="tooltip" title="<?php echo $sfhelp_to_manager ?>"><?php echo $sfentry_to_manager ?></span></label>
									<div class="col-sm-10">
										<input type="text" name="sfconfig_to_manager" value="<?php echo $sfconfig_to_manager ?>" placeholder="<?php echo $sfentry_to_manager ?>" id="input-sfconfig-to-manager" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-complete-status"><span data-toggle="tooltip" title="<?php echo $sfhelp_status_check_manager ?>"><?php echo $sfentry_status_check_manager ?></span></label>
									<div class="col-sm-10">
										<div class="well well-sm" style="height: 150px; overflow: auto;">
											<?php foreach ( $order_statuses as $sid => $order_status ) { ?>
											<div class="checkbox">
												<label>
													<?php if ( in_array($sid, $sfconfig_status_check_manager) ) { ?>
													<input type="checkbox" name="sfconfig_status_check_manager[]" value="<?php echo $sid ?>" checked="checked" />
													<?php echo $order_status ?>
													<?php } else { ?>
													<input type="checkbox" name="sfconfig_status_check_manager[]" value="<?php echo $sid ?>" />
													<?php echo $order_status ?>
													<?php } ?>
												</label>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>Настройка шаблонов текста смс</legend>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-order-status"><?php echo $sfentry_order_status_manager ?></span></label>
									<div class="col-sm-10">
										<select id="sf-order-status-manager" class="form-control">
											<option><?php echo $sftext_ne_vibrano ?></option>
											<?php foreach ( $order_statuses as $sid => $order_status ) { ?>
											<option value="<?php echo $sid ?>"><?php echo $order_status ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="textarea-template-manager"><span data-toggle="tooltip" title="<?php echo $sfhelp_template_manager ?>"><?php echo $sfentry_template_manager ?></span></label>
									<div class="col-sm-10">
										<textarea rows="5" placeholder="<?php echo $sfentry_template_manager ?>" id="textarea-template-manager" class="form-control"></textarea>
									</div>
								</div>
							</fieldset>
						</div>
					</div>

					<?php foreach ( $sfconfig_templates_manager as $sfkey => $sfvalue ) { ?>
					<input type="checkbox" id="sfconfig_templates_manager<?php echo $sfkey ?>" name="sfconfig_templates_manager[<?php echo $sfkey?>]" value="<?php echo $sfvalue ?>" checked="checked" hidden/>
					<?php } ?>

					<?php foreach ( $sfconfig_templates_client as $sfkey => $sfvalue ) { ?>
					<input type="checkbox" id="sfconfig_templates_client<?php echo $sfkey ?>" name="sfconfig_templates_client[<?php echo $sfkey?>]" value="<?php echo $sfvalue ?>" checked="checked" hidden/>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo $footer ?>

<script>
	$('#textarea-template-client, #textarea-template-manager').on('keydown keyup change', function () {
		let type = (this.id === 'textarea-template-manager') ? 'manager' : 'client'
		let key = $('#sf-order-status-' + type + ' :selected').val(), text = $('#textarea-template-' + type).val()
		$('#sfconfig_templates_' + type + key).val(text)
	})

	$('#sf-order-status-client, #sf-order-status-manager').change(function () {
		let type = (this.id === 'sf-order-status-manager') ? 'manager' : 'client'
		let key = $('#sf-order-status-' + type + ' :selected').val(), text = $('#sfconfig_templates_' + type + key).val();
		$('#textarea-template-' + type).val(text)
	})
</script>