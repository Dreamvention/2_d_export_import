<?php
/*
*  location: admin/controller
*/

class ControllerExtensionDExportImportSetting extends Controller {

    private $codename = 'd_export_import';
    private $route = 'extension/d_export_import/setting';
    private $error = array();
    
    private $extension = array();


    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('extension/module/'.$this->codename);
        $this->load->language($this->route);
        $this->load->language('extension/module/'.$this->codename);


        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'.json'), true);
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;

    }

    public function index(){
        if(!$this->d_shopunity){
            $this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
        }

        $this->load->model('d_shopunity/mbooth');
        $this->model_d_shopunity_mbooth->validateDependencies($this->codename);
        
        $this->load->model('setting/setting');
        $this->load->model('extension/module');
        $this->load->model('d_shopunity/setting');

                    //save post
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link($this->route, 'token=' . $this->session->data['token'].'&type=module', 'SSL'));

        }

        // styles and scripts
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
        
        $this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
        $this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');

        // Add more styles, links or scripts to the project is necessary
        $url_params = array();
        $url = '';

        if(isset($this->response->get['store_id'])){
            $url_params['store_id'] = $this->store_id;
        }

        $url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->session->data['token'];
        $data['d_shopunity'] = $this->d_shopunity;
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_limit_step'] = $this->language->get('entry_limit_step');
        $data['entry_truncate_table'] = $this->language->get('entry_truncate_table');

        $data['help_limit'] = $this->language->get('help_limit');
        $data['help_limit_step'] = $this->language->get('help_limit_step');
        $data['help_truncate_table'] = $this->language->get('help_truncate_table');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
        $data['action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        if(VERSION>='2.3.0.0'){
            $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL');
        }
        else{
            $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        }

        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }

        if (isset($this->request->post[$this->codename.'_setting'])) {
            $data[$this->codename.'_setting'] = $this->request->post[$this->codename.'_setting'];
        } else {
            $data[$this->codename.'_setting'] = $this->config->get($this->codename.'_setting');
        }

        if (isset($this->request->post[$this->codename.'_setting'])) {
            $data['setting'] = $this->request->post[$this->codename.'_setting'];
        } else {
            $data['setting'] = $this->model_d_shopunity_setting->getSetting($this->codename);
        }

        //get store
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_d_shopunity_setting->getStores();

        //get setting
        $data['setting'] = $this->model_d_shopunity_setting->getSetting($this->codename);

        $this->load->model('setting/store');


        // Breadcrumbs
        $data['breadcrumbs'] = array(); 
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
            );
        if(VERSION>='2.3.0.0'){
            $data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_module'),
                'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL')
                );
        }
        else{
            $data['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_module'),
                'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
                );
        }
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL')
            );

        foreach($this->error as $key => $error){
            $data['error'][$key] = $error;
        }

        if(isset($this->session->data['success'])){
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['tabs'] = $this->{'model_extension_module_'.$this->codename}->getTabs('setting');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->route.'.tpl', $data));
    }

    private function validate($permission = 'modify') {

        if (isset($this->request->post['config'])) {
            return false;
        }
        
        $this->language->load($this->route);
        
        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if(empty($this->request->post['limit_step'])){
            $this->error['limit_step'] = $this->language->get('error_limit_step');
        }
        
        return true;
    }
}