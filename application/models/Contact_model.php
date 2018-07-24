<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact_model extends CI_Model{
	public $model_table       = "sms_lists";
	public $list_data_columns = array();
	public $list_data_select  = array();
	public $insert_fields     = array();
	public $update_fields     = array();
	public $primary_key       = '';
	
	
	function __construct(){
        parent::__construct();
		
		$this->primary_key       = 'id';
		
		/*
		* Database Column
		* database column name => list page column name
		*/
		$this->list_data_columns = array(
			'id'         => 'ID',
			'uid'   => 'User',
			'list_name'   => 'List Name',
			'list_type'   => 'List Type',
			'list_details'       => 'List Detail',
			'contacts' => 'Subscribers',
			'status'     => 'Status',
			'c_date'     => 'Created Date',
		);
		
		/*
		* List data select fields for list data
		* array
		*/
		$this->list_data_select = array_keys($this->list_data_columns);
		
		//Inser fields
		$this->insert_fields = array('uid', 'list_name', 'list_type', 'list_details');
		
		//Update fields
		$this->update_fields = array('list_name', 'list_type', 'list_details', 'status');
    }
	
	/*
	* Name : list_data
	* Functionality : Used to list from database
	* returns array
	*/
	public function list_data($offset, $where_arr = array()){
		$where = $this->list_data_where($where_arr);
		if(!empty($where)){
			$this->general_model->where = $where;
		}
		$this->general_model->fields = $this->list_data_select;
		$this->general_model->table  = $this->model_table;
		$this->general_model->offset = $offset;
		$this->general_model->order_by = 'id DESC';
		$get = $this->general_model->fetch_results();
		
		//START - export data//
		
		//END - export data//
		
		$links = $this->general_model->pagination_list_data($get['total']);
		$get['links'] = $links;
		return $get;
	}
	
	/*
	* Name  : list_data_where
	*/
	public function list_data_where($where_arr = array()){
		$where = '';
		if(!empty($_POST['keyword'])){
			$keyword = trim($_POST['keyword']);
			if(preg_match('/^([0-9]{10})$/',$keyword,$match)){
				$where['phone'] = $match[1];
			}else{
				$where['email'] = $keyword;
			}
		}
		
		if(!empty($_POST['list_name'])){
			$where['list_name'] = trim($this->input->post('list_name'));
		}
		
		if(!empty($_POST['list_type'])){
			$where['list_type'] = trim($this->input->post('list_type'));
		}
		
		if(!empty($_POST['status'])){
			$where['status'] = (trim($_POST['status'])==2) ? '0' : trim($this->input->post('status'));
		}
		
		if(!empty($_POST['uid'])){
			$where['uid'] = trim($this->input->post('uid'));
		}
		if(!in_array($this->session->userdata('uid'),$GLOBALS['SUPER_USER_TYPE'])){$where['uid'] = $this->session->userdata('uid');}
		
		if(!empty($where_arr) && is_array($where_arr)){
			foreach($where_arr as $key => $value){
				if(!empty($value))
					$where[$key] = $value;
			}
		}
		
		return $where;
	}
	
	/*
	* Name : add_data
	* Functionality : Used to add in database
	* $data = data associative array
	*/
	public function add_data(){
		//Only post data will be accepted
		if(empty($_POST)){ return false; }
		
		$data = $this->filter_post_data();
		$dup = $this->_check_duplicate();
		
		$list_details = NULL;
		if($this->input->post('list_type') == 2){
			$list_details = array(
					'from_email' => addslashes(trim($_POST['from_email'])),
					'from_name' => addslashes(trim($_POST['from_name'])),
					'subject' => addslashes(trim($_POST['subject']))
			);
			$list_details = json_encode($list_details);
		}
		
		$insert_fields = $this->insert_fields;
		$db_data = '';
		foreach($this->insert_fields as $value){
			if($value == 'list_details')
				$db_data['list_details'] = $list_details;
			elseif(isset($data[$value]))
				$db_data[$value] = trim($this->input->post($value));
		}
		$db_data['c_date'] = date('Y-m-d H:i:s');
		
		$this->db->db_debug = FALSE; // Added....
		$ins = $this->db->insert($this->model_table, $db_data);
		$last_id = $this->db->insert_id();
		$this->db->db_debug = TRUE; // Added....
		return $last_id;
	}
	
	/*
	* Name : update_data
	* Functionality : Used to update in database
	* $data = data associative array
	*/
	public function update_data($id){
		//Only post data will be accepted
		if(empty($_POST)){ return false; }
		
		$data = $this->filter_post_data();
		$this->_check_duplicate($id);
		
		$list_details = NULL;
		if($this->input->post('list_type') == 2){
			$list_details = array(
					'from_email' => addslashes(trim($_POST['from_email'])),
					'from_name' => addslashes(trim($_POST['from_name'])),
					'subject' => addslashes(trim($_POST['subject']))
			);
			$list_details = json_encode($list_details);
		}
		
		$update_fields = $this->update_fields;
		$db_data = '';
		foreach($update_fields as $value){
			if($value == 'list_details')
				$db_data['list_details'] = $list_details;
			elseif(isset($data[$value]))
				$db_data[$value] = $data[$value];
		}
		
		$this->db->db_debug = FALSE; // Added....
		$this->db->where($this->primary_key, $id);
		$ins = $this->db->update($this->model_table,$db_data);
		$this->db->db_debug = TRUE; // Added....
		return $ins;
	}
	
	/*
	* Name : filter_post_data
	* Functionality : used to filter POST data
	*/
	public function filter_post_data(){
		$_POST['uid'] = $this->session->userdata('uid');
		$data = $_POST;
		return $data;
	}
	
	/*
	* Name : _check_duplicate
	* Functionality : used to check duplicate entry
	*/
	public function _check_duplicate($id=''){
		$where = array(
			'list_name'=> addslashes(trim($_POST['list_name']))
		);
		
		if($id > 0){$this->db->where(array('id !='=> $id));}
		$this->db->where($where);
		$res = $this->db->get($this->model_table);
		$num = $res->num_rows();
		
		if($num>0){
			$this->session->set_flashdata('error',$GLOBALS['ALERT_MESSAGES']['list_dup_error']);
			redirect($this->controller_url.CURR_METHOD."/".($id?'/'.encrypt_decrypt('encrypt',$id):''));exit;
		}
	}
	
	/*
	* Name : get_single_data
	* Functionality : used to Get single data
	*/
	public function get_single_data($id, $table_name=''){
		$table = (!empty($table_name)) ? $table_name : $this->model_table;
		$where = array($this->primary_key=> $id);
		$this->db->where($where);
		$res = $this->db->get($table);
		$result = $res->row();
		$num = $res->num_rows();
		if($num < 1){ $result = ''; }
		return $result;
	}
	
	/*
	* Name : form_validate
	* Functionality : used to validate form
	*/
	public function form_validate($type = ''){
		if(isset($_POST) && !empty($_POST)){
			//This method will have the credentials validation
			$this->load->library('form_validation');
			
			isset($_POST['list_type']) ? $this->form_validation->set_rules('list_type', 'List Type', 'required') : '';
			isset($_POST['list_name']) ? $this->form_validation->set_rules('list_name', 'List Name', 'required') : '';
			
			isset($_POST['from_email']) ? $this->form_validation->set_rules('from_email', 'From Email', 'required|valid_email') : '';
			isset($_POST['from_name']) ? $this->form_validation->set_rules('from_name', 'From Name', 'required') : '';
			isset($_POST['subject']) ? $this->form_validation->set_rules('subject', 'Email Subject', 'required') : '';
			
			if($this->form_validation->run() == FALSE) {
				//Field validation failed.  User redirected to login page
				return false;
			}
			return true;
		}
	}
	
	/*
	* Name : delete data
	*/
	public function delete_data($id){
		if(is_array($id)){
			$ids = array();
			foreach($id as $k){
				$ids[] = encrypt_decrypt('decrypt',$k);
			}
			$this->db->where_in($this->primary_key, $ids);
			$ins = $this->db->delete($this->model_table);
		}else{
			$this->db->where($this->primary_key,$id);
			$ins = $this->db->delete($this->model_table);
		}
		
		if($ins>0)
			return true;
		else
			return false;
	}

	/*
	 * Check list used in campaign or not
	 */
	public function check_campaign_lists(){

		$result = array();
		$uid = $this->session->userdata('uid');

		$this->db->where('uid',$uid);
		$this->db->select('list_id');
		$res = $this->db->get('sms_campaigns');

		if($res->num_rows()>0){
			foreach($res->result() as $a){
				$result[] = $a->list_id;
			}
		}
		return $result;
	}
	
}