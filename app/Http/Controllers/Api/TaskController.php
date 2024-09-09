<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest\AssignTaskRequest;
use App\Http\Requests\TaskRequest\UpdateStatusRequest;
use App\Http\Requests\TaskRequest\UpdateTaskRequest;
use App\Http\Requests\TaskRequest\StoreTaskRequest;
use App\Services\TaskServiece;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiece $taskService)
    {
        $this->taskService = $taskService;
        $this->middleware('role:Admin|Manager')->only(['update', 'destroy', 'show', 'store', 'forceDelete', 'assign', 'restore','index']);
    }

    /**
     * Get all tasks with filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

            $filters = $request->only(['priority', 'status']);
            $tasks = $this->taskService->getAllTasks($filters, Auth::id());
            return response()->json($tasks);

    }

    /**
     * Store a new task.
     *
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {

            $taskData = $request->validated();
            $taskData['created_by'] = Auth::id();
            $task = $this->taskService->createTask($taskData);
            return response()->json($task, 201);

    }

    /**
     * Update a task.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, $id)
    {

            $task = $this->taskService->updateTask($id, $request->validated());
            return response()->json($task);

    }

    /**
     * Delete a task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

            $this->taskService->deleteTask($id);
            return response()->json(['message' => 'Task deleted successfully']);

    }

    /**
     * Restore a deleted task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {

            $this->taskService->restoreTask($id);
            return response()->json(['message' => 'Task restored successfully'],200);

    }

    /**
     * Permanently delete a task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($id)
    {
            $this->taskService->forceDeleteTask($id);
            return response()->json(['message' => 'Task permanently deleted']);
    }
    /**
     * Assign a task to a user.
     *
     * @param AssignTaskRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(AssignTaskRequest $request, $id)
    {
            $task = $this->taskService->updateTask($id, ['assigned_to' => $request->input('assigned_to')]);
            return response()->json($task);
    }

    /**
     * Update task status.
     *
     * @param  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UpdateStatusRequest $request, $id)
    {
            $task = $this->taskService->updateTaskStatus($id, $request->input('status'));
            return response()->json($task);

    }

    /**
     * Show a specific task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $task = $this->taskService->getTaskById($id);
            if (Auth::user()->hasRole('Manager') && Auth::user()->id !== $task->created_by) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

