<?php 

namespace App\Controllers;

class Login extends BaseController
{ 
    public function new()
    { 
        return view('login/new');
    }

    public function create()
    { 
        $email = $this->request->getPost('email');
		$password = $this->request->getPost('password');
        
        #create object of authentication class

        #Service/s : 
        /* A type of factory that creates instances of a specified class. 
            e.g the session class
            Defined the App/Config/Services.php
        */  

        $auth = service('auth'); 
        //or
        //$auth = \Config\Services::auth();


        #code below made simpler and clearer 
        # email and password authentication is 
        # done in the <Libraries>/Authentication class 
        
        # Extra security now that prevents users from trying 
        # to guess the right password or email 
        # because all they'll know is that the login is invalid
        if ($auth->login($email, $password)){ 
            
            //null checker operator "??"
            //if session('redirect_url') is null the operator
            //redirects to the home page 
            $redirect_url = session('redirect_url') ?? '/';

            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
            ->with('info', 'Login successful');  
        }else { 
            return redirect()->back()
                             ->withInput()
                             ->with('warning', 'Invalid login');
        } 

        #region previous code to authenticate user login details
        // $model = new \App\Models\UserModel;
        
        // $user = $model->where('email', $email)
        //               ->first();
                      
        // if ($user === null) {
            
        //     return redirect()->back()
        //                      ->withInput()
        //                      ->with('warning', 'User not found');
                             
        // } else {
            
        //     if (password_verify($password, $user->password_hash)) {
                
        //         $session = session();
        //         $session->regenerate();
        //         $session->set('user_id', $user->id);
                
        //         return redirect()->to("/")
        //                          ->with('info', 'Login successful');
                
        //     } else {
                
        //         return redirect()->back()
        //                          ->withInput()
        //                          ->with('warning', 'Incorrect password');
        //     }
        // }

        #endregion
        
    }

    #logout method
    public function delete()
    { 
        //$auth = new \App\Libraries\Authentication; 
        //Instead of creating the object directly 
        // we use a service helper.
        //to make it even more simpler we can call the logout 
        //method directly on the return value
        //of the service helper

        service('auth')->logout();

        return redirect()->to('/login/showLogoutMessage');
    }

    public function showLogoutMessage()
    { 
        return redirect()->to('/')
                         ->with('info','Logout successful');
    }
}