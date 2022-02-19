<?php 

namespace App\Controllers; 

class Profileimage extends BaseController
{ 
    public function edit()
    {
        return view('Profileimage/edit');
    }

    public function update()
    {
        //object representing the file
        $file = $this->request->getFile('image');
        
        if (! $file->isValid() ) {
            // note we have to prefix the runtime exception with "\" - back slash
            //otherwise php will look for the class inside the 
            // controller class

            $error_code = $file->getError();

            if ($error_code == UPLOAD_ERR_NO_FILE) {
                return redirect()->back() 
                                 ->with('warning', 'No file selected');
            }
            throw new \RuntimeException($file->getErrorString() . " ". $error_code);
        
        } 
        //When a user adds a file image we are:
        //restricting the size to mb
        $size = $file->getSizeByUnit('mb');

        //amount of mb's
        if ($size > 2 ) {
            return redirect()->back()
                             ->with('warning', 'File too large (max 2MB)');
        } 
        // goole mime types 
        $type = $file->getMimeType(); 
        //as well as image type
        if ( ! in_array($type, ['image/png', 'image/jpeg'])) {     
            return redirect()->back() 
                             ->with('warning', 'Invalid file format (PNG or JPEG only)');
        }

       $path = $file->store('profile_images'); 

       //Lecture 115 
       //Using the codeigniter global constant WRITEPATH to get the full path of the 
       //image. The uploads folder is the sub folder of the writable folder for which 
       //we use the WRITEPATH constant 
       //Before we print out the path we pre pend the WRITEPATH constant
       //and also the uploads folder
       $path = WRITEPATH . "uploads/" . $path;
//Resize and crop image from the codeigniter Image Manipulation class 
//Creating an instance of the Image Manipulation class  
//By making use of the service helper function
        service('image')
            ->withFile($path)
            ->fit(200, 200, 'center') //passing the dimensions of the file
            ->save($path);

       $user = service('auth')->getCurrentUser(); 

       //set the profile image prop of the user object
       $user->profile_image = $file->getName();
        //to save the user object 
        //we need an instance of the user model
       $model = new \App\Models\UserModel;

       //protect method is similar to adding the $profile_image to the array of allowed fields
       $model->protect(false)
             ->save($user);

        return redirect()->to("/profile/show")
                         ->with('info', 'Image uploaded successfully');
    }

    public function delete()
    {
        if ($this->request->getMethod() === 'post') {
            $user = service('auth')->getCurrentUser();

            $path = WRITEPATH . 'uploads/profile_images/' . $user->profile_image;

            //Lecture 119 - good practice to check if the path
            //contains a valid path

            if (is_file($path)) {
                unlink($path);
            }

            $user->profile_image = null; 

            $model = new \App\Models\UserModel;

            $model->protect(false) 
                  ->save($user);

            return redirect()->to('/profile/show')
                             ->with('info', 'Image deleted');
        }
        return view('Profileimage/delete');
    }
}