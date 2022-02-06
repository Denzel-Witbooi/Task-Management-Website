<?php 

#Libraries Folder - 
# For any classes that we want to add
#That Models, Controllers etc. 

namespace App\Libraries;  
#All the code that deals with the session
class Authentication 
{ 
    private $user;

    public function login($email, $password)
    { 
        $model = new \App\Models\UserModel;
        
        $user = $model->findByEmail($email);
                      
        if ($user === null) {
            return false;
        }

        #password_verify($password, $this->password_hash) placed in the User Entity
        #to make code more robust and clear
        #this the method we would to call on the user object 
        #hence placing it on the User entity class 

        if (! $user->verifyPassword($password)) {
            return false;
        } 

        if( ! $user->is_active){ 
            return false;
        }

        $session = session();
        $session->regenerate();
        $session->set('user_id', $user->id);

        return true;
    }

    public function logout()
    { 
        session()->destroy(); 
    }

    public function getCurrentuser()
    { 
        //calling the isLoggedIn method here so we don't repeat ourselves
        if(! session()->has('user_id')){ 
            return null;
        }
        // So the first time we call this method 
        // The user property will be null. 
        // So we'll query the database and assign the user object
        // to the property.
        // The second the method is called the user property won't be null 
        // so we avoid querying the database for 2nd time with the same query. 

      if ($this->user == null) {
        $model =  new \App\Models\UserModel; 

        //Instead of assigning the model to a property "$this->user"
        //we assign it to a variable "user"
        $user = $model->find(session()->get('user_id')); 
        if ($user && $user->is_active) {
            $this->user = $user;
        }
      }

      return $this->user;
    }

    public function isLoggedIn()
    { 
        //checks if user_id is in the session
        //    return session()->has('user_id');

        //Check the current user model is returning null 
        return $this->getCurrentuser() !== null;

    }
}