<?php

namespace App\Controllers;
use App\Models\Todolist_Model;

class TodolistController extends BaseController
{
    public function index()
    {
        return view('todolist');
    }

    public function get_list(){
        $model = new Todolist_Model();
        $data = $model->findAll();
        return json_encode($data);
    }

    public function add(){
        $model = new Todolist_Model();
        $data = array(
            'list'        => $this->request->getPost('list')
        );
        $save=$model->insert($data);
        return json_encode(array(
            "status"=>"OK",
            "message" => "Success Add Data"
        ));
    }

    public function update(){
        $model = new Todolist_Model();

        $id = $this->request->getPost('id');
        $data = array(
            'status' => $this->request->getPost('status') == "true" ? 1 :0
        );
        $save=$model->update($id,$data);
        return json_encode(array(
            "status"=>"OK",
            "message" => "Success Update Data",
            "data" =>$data
        ));
    }

    public function checkall(){
        $model = new Todolist_Model();
        $status = $this->request->getPost('status');
        $where = $status == 1 ? 0 : 1;
        
        $model->where('status',$where)->set(['status' => $status])->update();
        return json_encode(array(
            "status"=>"OK",
            "message" => "Success Update All Data",
        ));
    }

    public function deleteall(){
        $model = new Todolist_Model();

        $deleteAll=$model->where('status',1)->delete();
        return json_encode(array(
            "status"=>"OK",
            "message" => "Success Delete All Data",
            "Data" =>$deleteAll
        ));
    }


    public function delete(){
        $model = new Todolist_Model();

        $id = $this->request->getPost('id');
        
        $delete=$model->delete($id);
        return json_encode(array(
            "status"=>"OK",
            "message" => "Success Delete Data",
        ));
    }
}
