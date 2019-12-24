<?php
class ControllerExtensionModuleHoveroverImage extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/module/hoveroverimage');

		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_hoveroverimage', $this->request->post);

			$this->load->model('setting/event');
			$this->model_setting_event->deleteEventByCode('webocreation_hoveroverimage');
			$this->model_setting_event->addEvent('webocreation_hoveroverimage', 'catalog/controller/account/logout/after', 'extension/module/amazon_login/logout');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/hoveroverimage', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/hoveroverimage', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];


		if (isset($this->request->post['module_hoveroverimage_status'])) {
			$data['module_hoveroverimage_status'] = $this->request->post['module_hoveroverimage_status'];
		} else {
			$data['module_hoveroverimage_status'] = $this->config->get('module_hoveroverimage_status');
		}
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/hoveroverimage', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/hoveroverimage')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('hoveroverimage');
		$this->model_setting_event->addEvent('hoveroverimage', 'catalog/controller/account/logout/after', 'extension/module/amazon_login/logout');
		$this->load->model('extension/module/hoveroverimage');
		$this->model_extension_module_hoveroverimage->installTableBackImage();
		
	}

	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('hoveroverimage');
		$this->load->model('extension/module/hoveroverimage');
		$this->model_extension_module_hoveroverimage->dropTableBackImage();
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

}