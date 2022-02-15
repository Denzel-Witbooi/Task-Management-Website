<?php 

namespace App\Controllers; 

class Password extends BaseController
{ 
    public function forgot()
    {
        return view('Password/forgot');
    }

    #method to find matching user email 
    #to gen reset token 
    public function processForgot()
    {
        $model = new \App\Models\UserModel;

        //This will return a user entity model or null if the 
        // user is not found
        $user = $model->findByEmail($this->request->getPost('email'));

        if ($user && $user->is_active) {

            $user->startPasswordReset(); 
            $model->save($user);
           
            $this->sendResetEmail($user);

            //Redirecting like this after after 
            //handling a post method
            //avoids the form data from being posted again
            //if the page is refreshed.
            return redirect()->to("/password/resetsent");
        }else{ 

            return redirect()->back() 
                             ->with('warning', 'No active user found with that email address')
                             ->withInput();

        }
    }

    public function resetSent()
    {
        return view('Password/reset_sent');
    }

    //lecture 102
    //method to reset the password
    public function reset($token)
    {
        $model = new \App\Models\UserModel;

        $user = $model->getUserForPasswordReset($token);

        if ($user) {
           
            return view('Password/reset', [ 
                'token' => $token
            ]);
            //else if the user isn't found or the
            //token has expired
        } else { 

            return redirect()->to('/password/forgot')
                             ->with('warning', 'Link invalid or has expired. Please try again');

        }

    }

    public function resetSuccess()
    {
        return view('Password/reset_success');
    }

    public function processReset($token)
    {
        $model = new \App\Models\UserModel;

        $user = $model->getUserForPasswordReset($token);

        if ($user) {
            
            $user->fill($this->request->getPost());
            // if model save returns true
            if($model->save($user)) { 
                
                $user->completePasswordReset();

                //save the user again using the model
                $model->save($user);

                return redirect()->to('/password/resetsuccess');

            } else { 

                return redirect()->back()
                                 ->with('errors', $model->errors())
                                 ->with('warning', 'Invalid data');
            }
        } else {
            
            return redirect()->to('/password/forgot')
                             ->with('warning', 'Link invalid or has expired. Please try again');

        }
        
    }

    private function sendResetEmail($user)
    { 
        $email = service('email');

        $email->setTo($user->email);
        
        $email->setSubject('Password reset');
        //when we render the view we'll pass the token value
        //as well
        $message = view('Password/reset_email', [ 
            'token' => $user->reset_token
        ]);

        $email->setMessage($message);

        $email->send();

    }
}