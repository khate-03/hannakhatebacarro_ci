<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GenderModel;


class UserController extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function addUser()
    {
        $data = array();
        helper(['form']);

        // when submit button is clicked 
        if($this->request->getMethod() == "post"){
            $post = $this->request->getPost(["first_name", "middle_name", "last_name", "age", "gender_id", "email", "password"]);


            // Provide validation for every text field
            $rules = [
                'first_name' => ['label' => 'first name', 'rules' => 'required'],
                'middle_name' => ['label' => 'middle name', 'rules' => 'permit_empty'],
                'last_name' => ['label' => "last name", 'rules' => 'required'],
                'age' => ['label' => "age", 'rules' => 'required|numeric'],
                'gender_id' => ['label' => "gender_id", 'rules' => 'required'],
                'email' => ['label' => "email", 'rules' => 'required|valid_email|is_unique[users.email]'],
                'password' => ['label' => "password", 'rules' => 'required'],
                'confirm_password' => ['label' => "confirm_password", 'rules' => 'required_with[password]|matches[password]']

            ];

            // Checked if one of the fields has error. Otherwise, save to database
            if(! $this->validate($rules)){
                $data['validation'] = $this->validator;
            } 

            else{
            
                //Save user database
                $post['password'] = sha1($post['password']); //Encrypt password
    
                $UserModel = new UserModel();
                $UserModel->save($post);
    
                // $session = session();
                // $session->setFlashdata('success-add-user', 'User Successfully Saved!');
    
                
                //return redirect()->to('/user/add');
                return 'User Successfully Saved!';
            }
    
            
            
        }

        $GenderModel = new GenderModel();
        $data['genders'] = $GenderModel->findAll();


        return view('user/add', $data);
    }
}
