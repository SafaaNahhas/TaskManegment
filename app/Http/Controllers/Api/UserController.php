<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService The service that handles user operations.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of all users.
        */
    public function index()
    {
        // استرجاع المستخدمين بما في ذلك المحذوفين مع المهام المرتبطة بهم
        $users = User::with('tasks')->withTrashed()->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'deleted_at' => $user->deleted_at, // تاريخ الحذف إذا كان محذوفاً
                'task_count' => $user->tasks->count(), // عدد المهام الموكلة للمستخدم
                'tasks' => $user->tasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title, // عنوان المهمة
                        'description' => $task->description, // وصف المهمة
                        'priority' => $task->priority, // أولوية المهمة
                        'due_date' => $task->due_date, // تاريخ انتهاء المهمة
                        'status' => $task->status, // حالة المهمة
                    ];
                })
            ];
        });

        return response()->json($users);
    }


    /**
     * Display the specified user by ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the user data.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */

    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        // تحقق من أن المستخدم الحالي هو نفسه المستخدم الذي يتم استرجاعه أو أنه Admin
        if ($user->id !== Auth::id() && !Auth::user()->hasRole('Admin')) {
            return ApiResponseService::error('Unauthorized', 403);
        }

        if (!$user) {
            return ApiResponseService::error('User not found', 404);
        }

        // جلب المهام الخاصة بالمستخدم
        $tasks = $user->tasks;

        return response()->json([
            'user' => $user,
            // // أضف المهام ضمن كائن المستخدم
            // 'tasks' => $tasks,
        ]);
    }


    /**
     * Store a newly created user in the database.
     *
     * @param StoreUserRequest $request The request instance containing user data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created user data.
     * @throws \Illuminate\Validation\ValidationException If the provided data does not meet the validation rules.
     */
    public function store(StoreUserRequest $request)
    {
        // تحقق من أن المستخدم الحالي هو Admin قبل السماح بإنشاء مستخدم جديد
        if (!Auth::user()->hasRole('Admin')) {
            return ApiResponseService::error('Unauthorized', 403);
        }

        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    /**
     * Update the specified user by ID.
     *
     * @param UpdateUserRequest $request The request instance containing updated user data.
     * @param int $id The ID of the user to update.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated user data.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getUserById($id);

        // تحقق من أن المستخدم الحالي هو نفسه المستخدم الذي يتم تحديثه أو أنه Admin
        if ($user->id !== Auth::id() && !Auth::user()->hasRole('Admin')) {
            return ApiResponseService::error('Unauthorized', 403);
        }

        $updatedUser = $this->userService->updateUser($id, $request->validated());

        if (!$updatedUser) {
            return ApiResponseService::error('User not found', 404);
        }

        return response()->json($updatedUser);
    }

    /**
     * Remove the specified user by ID from the database.
     *
     * @param int $id The ID of the user to delete.
     * @return \Illuminate\Http\JsonResponse A JSON response confirming the deletion.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */
    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);

        if ($user->id !== Auth::id() && !Auth::user()->hasRole('Admin')) {
            return ApiResponseService::error('Unauthorized', 403);
        }

        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * Update the role of a user.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = $this->userService->getUserById($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newRole = $request->input('role');
        $user->syncRoles($newRole);

        return response()->json(['message' => 'User role updated successfully'], 200);
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (!Auth::user()->hasRole('Admin')) {
            return ApiResponseService::error('Unauthorized', 403);
        }

        $user->restore();
        return response()->json(['message' => 'User restored successfully'], 200);
    }


    /**
     * Force delete a user and reassign tasks.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleteUser(Request $request, $id)
    {
        $newUserId = $request->input('new_user_id');
        $user = $this->userService->getUserById($id);
        $newUser = $this->userService->getUserById($newUserId);

        if (!$user || !$newUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $this->userService->reassignTasksAndDelete($user, $newUser);

        return response()->json(['message' => 'User permanently deleted and tasks reassigned'], 200);
    }
}
