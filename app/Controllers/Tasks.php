<?php

namespace App\Controllers;

use \App\Entities\Task;

class Tasks extends BaseController
{
    private $model;
    //Property to store the current user object
	private $current_user;
	public function __construct()
	{
        $this->model = new \App\Models\TaskModel;
        $this->current_user = service('auth')->getCurrentUser();
	}
	
	public function index()
	{
        //First get user id now that we have added the FK col in user table

        // $auth = service('auth');
        // $user = $auth->getCurrentUser(); 
        //All this above has been replaced with a property called get current_user
        $data = $this->model->paginateTasksByUserId($this->current_user->id);
		
		return view("Tasks/index", [ 
            'tasks' => $data,
            'pager' => $this->model->pager 
        ]);
	}
	
	public function show($id)
    {
        $task = $this->getTaskOr404($id);
		return view('Tasks/show', [
            'task' => $task
        ]);
	}
	
	public function new()
	{
        $task = new Task;
		
		return view('Tasks/new', [
		    'task' => $task
        ]);
	}
	
	public function create()
	{
        $task = new Task($this->request->getPost());
		// $user = service('auth')->getCurrentUser();

        $task->user_id = $this->current_user->id;

		if ($this->model->insert($task)) {

			return redirect()->to("/tasks/show/{$this->model->insertID}")
							 ->with('info', 'Task created successfully');
		
        } else {

			return redirect()->back()
							 ->with('errors', $this->model->errors())
							 ->with('warning', 'Invalid data')
							 ->withInput();
		}
	}
	
	public function edit($id)
	{
        $task = $this->getTaskOr404($id);

		return view('Tasks/edit', [
            'task' => $task
        ]);
	}
	
    public function update($id)
	{
        $task = $this->getTaskOr404($id);

        //In the case of someone submitting a different user_id
        $post = $this->request->getPost(); 
        unset($post['user_id']);

		$task->fill($post);
		
		if ( ! $task->hasChanged()) {
			
            return redirect()->back()
                             ->with('warning', 'Nothing to update')
                             ->withInput();
		}
		
        if ($this->model->save($task)) {
				
	        return redirect()->to("/tasks/show/$id")
	                         ->with('info', 'Task updated successfully');
							 
		} else {
			
            return redirect()->back()
                             ->with('errors', $this->model->errors())
                             ->with('warning', 'Invalid data')
							 ->withInput();
			
		}
	}

    public function delete($id)
    { 
        $task = $this->getTaskOr404($id);

        if ($this->request->getMethod() == 'post') {
            $this->model->delete($id);

            return redirect()->to('/tasks') 
                             ->with('info', 'Task deleted');
        }
         
        return view('Tasks/delete', [ 
            'task' => $task
        ]);
    }


    private function getTaskOr404($id)
    { 
       // $user = service('auth')->getCurrentUser();

        // $task = $this->model->find($id);

        // //Condition to check if the current user has task or not
        // if ($task !== null && ($task->user_id !== $user->id)) {
            
        //     $task = null;

        // }
        $task = $this->model->getTaskByUserId($id, $this->current_user->id);
        
        if ($task == null) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task with id $id not found");
        }
        return $task;
    }
}

#region comments
        //Original php way
        /* 
            var_dump($data)
            exit;
        */
        //Codeigniter way to quickly print the values of the 
        //data variable to see what it contains
        //dd($data);
        #endregion
        #region comments
        /* 
        //array called data containing an associative array
        $data = [
            ['id' => 1, 'description' => 'First task'], 
            ['id' => 2, 'description' => 'Second task']
        ];
        ['tasks' => $data] --> this is associative array 
            with a key for each element will become a variable 
            in the view an its value 
            will the value of the array element  
            
            So in the view we'll have a variable called tasks 
            that will contain this array.   
        */
        #endregion
