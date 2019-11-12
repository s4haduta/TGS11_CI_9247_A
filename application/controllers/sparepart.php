<?php
    use Restserver \Libraries\REST_Controller ;
    Class sparepart extends REST_Controller{

    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('sparepartModel');
        $this->load->library('form_validation');
        }

        public function index_get(){
            return $this->returnData($this->db->get('spareparts')->result(), false);
        }

        public function index_post($id = null){
            $validation = $this->form_validation;
            $rule = $this->sparepartModel->rules();
            if($id == null){
            array_push($rule,[
            'field' => 'name',
            'label' => 'name',
            'rules' => 'required'
            ]
            );
        }
            else{
            array_push($rule,
            [
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|valid_email'
            ]
            );
            }
            $validation->set_rules($rule);

            if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
            }
                $sparepart = new sparepartData();
                $sparepart->name = $this->post('name');
                $sparepart->merk = $this->post('merk');
                $sparepart->brand = $this->post('brand');
                $sparepart->amount = $this->post('amount');
                $sparepart->created_at = $this->post('created_at');
                if($id == null){
                $response = $this->sparepartModel->store($sparepart);
        }else{
                $response = $this->sparepartModel->update($sparepart,$id);
            }
            return $this->returnData($response['msg'], $response['error']);
        }

        public function index_delete($id = null){
            if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
            }
            $response = $this->sparepartModel->destroy($id);
            return $this->returnData($response['msg'], $response['error']);
        }
        public function returnData($msg,$error){
            $response['error']=$error;
            $response['message']=$msg;
            return $this->response($response);
        }
       }
        Class sparepartData{
        public $name;
        public $merk;
        public $brand;
        public $amount;
        public $created_at;
       }
       