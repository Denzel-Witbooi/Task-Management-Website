<?php 

namespace App\Models; 

use App\Libraries\Token;

class UserModel extends \CodeIgniter\Model
{ 
    protected $table = 'user';

    //Protects us from a malicious user who could manipulate the
    //form data when signing up. 
    //And signup as an admin
    protected $allowedFields = ['name', 'email', 'password', 'activation_hash', 'reset_hash', 
                                'reset_expires_at']; 
    
    protected $returnType = 'App\Entities\User';

    protected $useTimestamps = true;

    protected $validationRules = [ 
        'name' => 'required', 
        'email' => 'required|valid_email|is_unique[user.email]', 
        'password' => 'required|min_length[6]', 
        'password_confirmation' => 'required|matches[password]'
    ];

    protected $validationMessages = [ 
        'email' => [ 
            'is_unique' => 'That email address is taken'
        ], 
        'password_confirmation' => [ 
            'required' => 'Please confirm the password', 
            'matches' => 'Please enter the same password again'
        ],
    ];

    protected $beforeInsert = ['hashPassword']; 

    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    { 
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password']);
            //To prevent the error below:
            //Unknown column 'password_confirmation' in 'field list' 
            //When inserting a new user as an admin and deactivating the 
            //allowedFields feature when inserting user data
            unset($data['data']['password_confirmation']);
        }

        return $data;
    } 

    public function findByEmail($email)
    { 
        return $this->where('email', $email)
                      ->first();
    }

    //Method to disable password validation temporalily to edit user details
    public function disablePasswordValidation()
    { 
        //Each rule is identified by the array "key" 
        //which is also the name of the property it's validating
        unset($this->validationRules['password']);
        unset($this->validationRules['password_confirmation']);
    }

    public function activateByToken($token)
    { 
        $token = new Token($token);
        $token_hash = $token->getHash();

        $user = $this->where('activation_hash', $token_hash)
                     ->first();

        #as the $user - variable contains an entity class 
        #created an activate method in User entity
        if ($user !== null) {
            //called method on the user object
            $user->activate();
            //this will update the user record setting the is 
            //activate column to true
            //and the activation hash column to null
            $this->protect(false)->save($user);
            //before we call the save method 
            //We'll temporarily turn the allowedfield property off 
            //This is safe as we are not mass assigning properties from a form in here
            //We setting them directly in the activate method.

            // --------------------------
            //Reason for this is in the allowedFields property save 
            //method will only update the columns in the allowFields list
            //the is_active column is not on this list 
            //and we don't want to add it as someone could set this to true 
            //if they manipulate the form when creating an account
        }
    }

    public function getUserForPasswordReset($token)
    {
        $token = new Token($token);

        $token_hash = $token->getHash(); 

        $user = $this->where('reset_hash', $token_hash) 
             ->first();

        if ($user) {
            if ($user->reset_expires_at < date('Y-m-d H:i:s')) {
                
                $user = null;

            }
        }

        return $user;
    }
    
}