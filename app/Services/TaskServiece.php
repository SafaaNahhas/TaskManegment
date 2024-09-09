<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskServiece
{
    /**
     * Get all tasks.
     *
     * @param array $filters Optional filters like priority and status.
     * @param int|null $userId Optional user ID to filter tasks.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTasks($filters = [], $userId = null)
    {
    try {
        $tasksQuery = Task::query();

        if (Auth::user()->hasRole('Admin')) {
        } else {
            if ($userId) {
                $tasksQuery->where('created_by', $userId);
            }
        }

        if (!empty($filters['priority'])) {
            $tasksQuery->where('priority', $filters['priority']);
        }

        if (!empty($filters['status'])) {
            $tasksQuery->where('status', $filters['status']);
        }

        return $tasksQuery->get();
    } catch (Exception $e) {
        Log::error('Error retrieving tasks: ' . $e->getMessage());
        throw new Exception('Failed to retrieve tasks');
    }
}

    /**
     * Store a new task.
     *
     * @param array $data The task data.
     * @return Task
     */
    public function createTask($data)
    {
        try {
            $task = Task::create($data);
            return $task;
        } catch (Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            throw new Exception('Failed to create task');
        }
    }

    /**
     * Update a task.
     *
     * @param int $id Task ID.
     * @param array $data Task data.
     * @return Task
     */
    public function updateTask($id, $data)
    {
        try {

            $task = Task::find($id);
            if (!$task) {
                throw new Exception('Task not found');
            }

            if (Auth::user()->id !== $task->created_by && !Auth::user()->hasRole('Admin')) {
                throw new Exception('Unauthorized');

            }
            $task->update($data);
            return $task;
        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            throw new Exception('Failed to update task');
        }
    }

    /**
     * Delete a task.
     *
     * @param int $id Task ID.
     * @return void
     */
    public function deleteTask($id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                throw new Exception('Task not found');
            }
            if (Auth::user()->id !== $task->created_by && !Auth::user()->hasRole('Admin')) {
                throw new Exception('Unauthorized');
            }
            $task->delete();
        } catch (Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            throw new Exception('Failed to delete task');
        }
    }

    /**
     * Restore a soft deleted task.
     *
     * @param int $id Task ID.
     * @return void
     */
    public function restoreTask($id)
    {
        try {
            $task = Task::onlyTrashed()->find($id);
            if (!$task) {
                throw new Exception('Task not found');
            }
            if (Auth::user()->id !== $task->created_by && !Auth::user()->hasRole('Admin')) {
                throw new Exception('Unauthorized');
            }
            $task->restore();
        } catch (Exception $e) {
            Log::error('Error restoring task: ' . $e->getMessage());
            throw new Exception('Failed to restore task');
        }
    }

    /**
     * Permanently delete a soft deleted task.
     *
     * @param int $id Task ID.
     * @return void
     */
    public function forceDeleteTask($id)
    {
        try {
            $task = Task::onlyTrashed()->find($id);
            if (!$task) {
                throw new Exception('Task not found');
            }
            if (Auth::user()->id !== $task->created_by && !Auth::user()->hasRole('Admin')) {
                throw new Exception('Unauthorized');
            }
            $task->forceDelete();
        } catch (Exception $e) {
            Log::error('Error permanently deleting task: ' . $e->getMessage());
            throw new Exception('Failed to permanently delete task');
        }
    }
    public function getTaskById($id)
    {
        try {
            return Task::findOrFail($id);
        } catch (Exception $e) {
            Log::error('Error fetching task: ' . $e->getMessage());
            throw new Exception('Failed to fetch task');
        }
    }
    /**
 * Update task status by ID.
 *
 * @param int $id Task ID.
 * @param string $status New task status.
 * @return Task
 * @throws Exception If task not found or unauthorized.
 */
    public function updateTaskStatus($id, $status)
{
    try {
        $task = Task::find($id);
        if (!$task) {
            throw new Exception('Task not found');
        }
        if (Auth::user()->id !== $task->assigned_to && !Auth::user()->hasRole('Admin')) {
            throw new Exception('Unauthorized');
        }

        $task->status = $status;
        $task->save();

        return $task;
    } catch (Exception $e) {
        Log::error('Error updating task status: ' . $e->getMessage());
        throw new Exception('Failed to update task status');
    }
}

}
