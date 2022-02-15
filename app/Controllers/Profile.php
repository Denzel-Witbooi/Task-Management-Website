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
        return view('Profile/edit', [ 
            'user' => $this->user
        ]);
    }
}
