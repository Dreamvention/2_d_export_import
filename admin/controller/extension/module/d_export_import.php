<?php
/*
*  location: admin/controller
*/

class ControllerExtensionModuleDExportImport extends Controller {

    private $codename = 'd_export_import';
    private $route = 'extension/module/d_export_import';
    private $config_file = 'd_export_import';
    private $extension = array();
    private $store_id = 0;
    private $error = array();
    
    
    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model($this->route);
        $this->load->language($this->route);
        
        
        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/'.$this->codename.'.json'), true);
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
        
    }
    
    public function required(){

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_not_found'] = $this->language->get('text_not_found');
        $data['breadcrumbs'] = array();
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->request->get['extension'] = $this->codename;
        if(VERSION >= '2.3.0.0'){
            $this->load->controller('extension/extension/module/uninstall');
        }else{
            $this->load->controller('extension/module/uninstall');
        }
        $this->response->setOutput($this->load->view('error/not_found.tpl', $data));
    }
    
    
    public function index(){
        if(!$this->d_shopunity){
            $this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
        }
        
        $this->load->model('d_shopunity/mbooth');
        $this->model_d_shopunity_mbooth->validateDependencies($this->codename);
        
        $this->load->controller('extension/'.$this->codename.'/excel');
        
    }
    
    public function install() {
        if($this->d_shopunity){
            $this->load->model('d_shopunity/mbooth');
            $this->model_d_shopunity_mbooth->installDependencies($this->codename);
        }

        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename);
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'_module');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/excel');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'access', 'extension/'.$this->codename.'/setting');

        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'_module');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/excel');
        $this->model_user_user_group->addPermission($this->{'model_extension_module_'.$this->codename}->getGroupId(), 'modify', 'extension/'.$this->codename.'/setting');


    }
}
?>