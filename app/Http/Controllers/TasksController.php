<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::where('user_id', auth()->user()->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {

            $data = $request->validate(
                [
                'title' => 'required|string',
                'content' => 'required|string',
                'completed' => 'required|boolean'
            ]
        );
            $task = Task::create([
                'user_id' => auth()->user()->id,
                'title' => $request->title,
                'team_id' => $request->team_id,
                'content' => $request->content,
                'completed' => $request->completed,
            ]);

            return response($task, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if($task->user_id != auth()->user()->id){
            return response()->json("Unauthorised", 401);
        }


        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'completed' => 'required|boolean',
        ]);

        $task->update($data);

        return response($task, 200);
    }

    public function updateAll(UpdateTaskRequest $request)
    {
        $data = $request->validate([
            'completed' => 'required|boolean',
        ]);

        Task::where('user_id', auth()->user()->id)->update($data);

        return response('Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if($task->user_id != auth()->user()->id){
            return response()->json("Unauthorised", 401);
        }

        $task->delete();
        return response("Item deleted", 200);
    }

    public function destroyCompleted(StoreTaskRequest $request)
    {

        $tasksToDelete = $request->tasks;

        $userTaskIds = auth()->user()->tasks->map(function ($task){
            return $task->id;
        });

        $valid = collect($tasksToDelete)->every(function ($value, $key) use ($userTaskIds){
             return $userTaskIds->contains($value);
        });

        if(!$valid){
            return response()->json("Unauthorised", 401);
        }

        $request->validate([
            'tasks' => 'required|array',
        ]);

        Task::destroy($request->tasks);

        return response('Deleted', 200);
    }
}
