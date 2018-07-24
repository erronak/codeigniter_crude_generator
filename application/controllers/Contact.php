<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Contact extends CI_Controller {
	public $view_dir       = '';
	public $curr_admin_url = '';
	public $model          = '';
	public $dir_name = '';
	public $tpl_dir_name   = '';
	public $controller_url = '';
	public $model_table       = "sms_lists";
	public $model_user_table       = "sms_users";
	public $model_user_meta_table = 'sms_user_meta';
	public $model_subscribers_table       = "sms_subscribers";
	
	
	function __construct(){
		parent::__construct();
        
		//Check Login & Authentication before give access
		check_login(); 
		check_authentication();
		
		//Skip this methods
		$skip_methods = array('delete', 'emptyList');
		$this->dir_name = "";
		$this->tpl_dir_name   = $this->dir_name."/template/";
		check_model_view_exists($skip_methods,$this->dir_name);
		
		$this->view_dir = $this->dir_name."/".CURR_CLASS;
		$this->controller_url = base_url().(($this->dir_name) ? $this->dir_name."/" : '').CURR_CLASS."/";
		
		$this->model = CURR_CLASS.'_model';
		$this->load->model($this->dir_name."/".$this->model);
	}
	
	/*
	* Name : Index
	* Functionality : Used to list
	* $offset = page offset
	*/
	public function index($offset=''){
		$data['page_title']    = 'Lists | '.SITE_TITLE;
		$data['section_title'] = 'My Lists';
		$data['breadcrumb']    = 'My Lists';
		
		$data['form_action'] = $this->controller_url.CURR_METHOD;
		$data['list_name'] = trim($this->input->post('list_name'));
		$data['list_type'] = trim($this->input->post('list_type'));
		$data['status'] = trim($this->input->post('status'));
		
		$data['list_page_js']  = 1;
		$data['offset'] = $offset;
		$data['limit'] = ($this->input->post('limit')) ? $this->input->post('limit') : PER_PAGE_LIMIT;
		
		$data['USERS'] = $this->general_model->getKeyValuePair($this->model_user_meta_table, 'uid', array('meta_value'), array('meta_key'=>'name'));
		
		//Number of records to show
		// PER_PAGE_LIMIT  : 20
		
		//$this->user_model->current_method = base_url().CURR_CLASS."/".CURR_METHOD;
		$get_prospect = $this->{$this->model}->list_data($offset);
		$data['result'] = $get_prospect;

		//used groups
		$data['used_campaign_lists'] = $this->{$this->model}->check_campaign_lists();

		//$this->template->load('default','prospect/prospectlist',$data);
		$data['is_add_form_on_model']  = 1;
		$this->template->load($this->tpl_dir_name.'tpl_default',$this->view_dir.'/'.CURR_METHOD, $data);
		//$this->output->enable_profiler(TRUE);
	}
	
	/*
	* Name : add
	* Functionality : Used to add or update buyer prospect
	* $id = page id encrypted
	*/
	public function add($id=''){
		$data['page_title']    = 'Lists | '.SITE_TITLE;
		$data['section_title'] = 'Create List';
		$data['breadcrumb']    = 'Create List';
		
		 // CONSTANT TO INCLUDE JS FILES
		$GLOBALS['JS']['FORM_VALIDATION'] = 1;
		$GLOBALS['JS']['TEXT_COUNTER'] = 1;
		
		$id = encrypt_decrypt('decrypt',$id);
		if(!empty($id)){
			$id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			$get_single_data = $this->{$this->model}->get_single_data($id);
			if(empty($get_single_data)){
				$this->session->set_flashdata('error', $GLOBALS['ALERT_MESSAGES']['no_record']);
				redirect($this->controller_url.CURR_METHOD);exit;
			}
			$data['result']  = (array)$get_single_data;
		}
		
		$check_data_valid = $this->{$this->model}->form_validate();
		if($check_data_valid){
			if(!empty($id)){
				$ins = $this->{$this->model}->update_data($id);
				if($ins>0)
					$this->session->set_flashdata('success',$GLOBALS['ALERT_MESSAGES']['list_update_success']);
				else
					$this->session->set_flashdata('error',$GLOBALS['ALERT_MESSAGES']['list_update_error']);
			}else{
				$list_id = $this->{$this->model}->add_data();
				if($list_id>0)
					$this->session->set_flashdata('success',$GLOBALS['ALERT_MESSAGES']['list_add_success']);
				else
					$this->session->set_flashdata('error',$GLOBALS['ALERT_MESSAGES']['list_add_error']);
			}
			redirect($this->controller_url.'index/');exit;
		}
		
		$this->template->load($this->tpl_dir_name.'tpl_default',$this->view_dir.'/'.CURR_METHOD,$data);
	}
	
	/*
	* Name : Delete
	* Functionality : used to delete
	* $id = page id encrypted
	*/
	public function delete($id=''){
		if($id == 'multi'){
			$ids = $this->input->post('ids');
			$delete = $this->{$this->model}->delete_data($ids);
		}else{
			$id = encrypt_decrypt('decrypt',$id);
			$get_single_data = $this->{$this->model}->get_single_data($id);
			if(empty($get_single_data)){
				$this->session->set_flashdata('error', $GLOBALS['ALERT_MESSAGES']['no_record']);
				redirect($this->controller_url.'index/');exit;
			}
			$delete = $this->{$this->model}->delete_data($id);
		}
		
		if(!empty($delete)){
			$this->session->set_flashdata('success',$GLOBALS['ALERT_MESSAGES']['list_delete_success']);
		}else{
			$this->session->set_flashdata('error',$GLOBALS['ALERT_MESSAGES']['list_delete_error']);
		}
		redirect($this->controller_url.'index/');exit;
	}

	/*
	* Name : Delete
	* Functionality : used to delete
	* $id = page id encrypted
	*/
	public function emptyList($id=''){
		$id = encrypt_decrypt('decrypt',$id);
		$this->db->query("DELETE from ".$this->model_subscribers_table." where list_id='".trim($id)."' ");
		$this->general_model->custom_update_query("UPDATE ".$this->model_table." set contacts = '0' where id='".trim($id)."' ");
		$this->db->query("OPTIMIZE TABLE `".$this->model_subscribers_table."`");
		$this->session->set_flashdata('success',$GLOBALS['ALERT_MESSAGES']['list_empty_success']);
		redirect($this->controller_url.'index/');exit;
	}
	
	/*
	* Name : View
	* Functionality : used to view single List
	* $id = page id encrypted
	*/
	public function view($id){
		$data['page_title']    = "List | ".SITE_TITLE;
		$data['section_title'] = "View List Detail";
		$data['breadcrumb']    = "List Detail";
		$data['is_view_page']  = 1;
		
		$id = encrypt_decrypt('decrypt',$id);
		$get_single_data = $this->{$this->model}->get_single_data($id);
		if(empty($get_single_data)){
			$this->session->set_flashdata('error', $GLOBALS['ALERT_MESSAGES']['no_record']);
			redirect($this->controller_url);exit;
		}
		$this->load->view($this->view_dir.'/add', $data);
	}

	/*
	* Name : get_form
	* Functionality : used to get form for list
	* $id = page id encrypted
	*/
	public function get_form($list_id){
		$data['page_title']    = "List Form | ".SITE_TITLE;
		$data['section_title'] = "Get List Form";
		$data['breadcrumb']    = "List Form";
		$data['is_view_page']  = 1;

		$uid = $this->session->userdata('uid');
		$data['LIST_ID']       = $list_id;
		$data['CLIENT_ID']     = encrypt_decrypt('encrypt',$uid);
		$data['CLIENT_SECRET'] = $this->session->userdata('auth_key');

		$this->template->load($this->tpl_dir_name.'tpl_default',$this->view_dir.'/'.CURR_METHOD,$data);
	}
}