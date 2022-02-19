<?php 

namespace App\Controllers; 

class Profile extends BaseController 
{
    //property to store the user object
    private $user;

    public function __construct() {
        $this->user = service('auth')->getCurrentUser();
    }
    //Method to render the current view
    public function show()
    {
        //Get current user using auth service and pass that 
        //through the view 
        $user = service('auth')->getCurrentUser();

        return view('Profile/show', [ 
            'user' => $this->user
        ]);
    }

    public function edit()
    {
        //add session object to variable
        $session = session();

        //check to see if the value we just added to the session exists

        if (! $session->has('can_edit_profile_until')) {
            return redirect()->to("/profile/authenticate");
        }
    //compare the session value to the current time
    //if it's less the 5 mins have expired
        if ($session->get('can_edit_profile_until') < time()) {
            return redirect()->to("/profile/authenticate");
        }
        return view('Profile/edit', [ 
            'user' => $this->user
        ]);
    }
//Using existing validation rules from the user Model
    public function update()
    {
        $this->user->fill($this->request->getPost());
        
        if ( ! $this->user->hasChanged()) {
            
            return redirect()->back()
                             ->with('warning', 'Nothing to update')
                             ->withInput();
        }
        
        //model object to save the User
        $model = new \App\Models\UserModel;
        
        if ($model->save($this->user)) {
            //if the model saves successfully 
            //we'll remove the value from the session
            session()->remove('can_edit_profile_until');
            return redirect()->to("/profile/show")
                             ->with('info', 'Details updated successfully');
        } else {
            
            return redirect()->back()
                             ->with('errors', $model->errors())
                             ->with('warning', 'Invalid data')
                             ->withInput();
        }
        
    }

    public function editPassword()
    {
        return view('Profile/edit_password');
    }

    public function updatePassword()
    {
        if (! $this->user->verifyPassword($this->request->getPost('current_password'))) {
            return redirect()->back()
                             ->with('warning', 'Invalid current password');
        } 

        $this->user->fill($this->request->getPost()); 

        $model = new \App\Models\UserModel;

        if ($model->save($this->user)) {
            return redirect()->to("/profile/show")
                             ->with('info', 'Password updated successfully');
        } else { 
            return redirect()->back()
                             ->with('errors', $model->errors())
                             ->with('warning', 'Invalid data');
        }
    }

    //Method to ask the user's password before saving 
    //any changes to their profile
    //To display a page for supplying the password
    public function authenticate()
    {
        return view('Profile/authenticate');
    }
    //We want the user to authenticate if they want 
    //To edit their profile
    public function processAuthentication()
    {
        //Using the password verify method from the user entity class

        if ($this->user->verifyPassword($this->request->getPost('password'))) {
            //To temporarily remember this authentication
            //we'll store a value in the session
            //This will allow the user to able to edit the 
            //profile for the next 5 minutes
            //To which they have to authenticate again
            session()->set('can_edit_profile_until', time() + 300);
            
            //using this to protect the page
            return redirect()->to('/profile/edit');

        } else {

            return redirect()->back()
                             ->with('warning', 'Invalid password');
        }
        
    }
    #lecture 118
    public function image()
    {
        if ($this->user->profile_image) {
            $path = WRITEPATH . 'uploads/profile_images/' . $this->user->profile_image;
            $finfo = new \finfo(FILEINFO_MIME);

            $type = $finfo->file($path);

            header("Content-Type: $type");
            header("Content-Length: " . filesize($path));

            readfile($path);
            exit;
        }
    }

}
