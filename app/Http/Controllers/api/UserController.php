<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Http\Requests\ResetUserPassword;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\InvalidParameterException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService, User $user)
    {
        $this->userService = $userService;
        $this->user = $user;
    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = $this->user->all();
        return formatResponse(false,(["users"=>$user]));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\StoreUser  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUser $request)
    {
        $data = $request->validated();
        $data["password"] = bcrypt($data["password"]);

        $this->user->create($data);

        return formatResponse(false,(["user"=>["User successfully created"]]));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->userService->handleInvalidParameter($id,1);
        $this->userService->handleModelNotFound($id);

        $user = $this->user->find($id);
        return formatResponse(false,(["user"=>$user]));
    }


    /**
     * Update the specified user in database.
     *
     * @param  \Illuminate\Http\Request\UpdateUser  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUser $request, $id)
    {
        $this->userService->handleInvalidParameter($id,1);
        $this->userService->handleModelNotFound($id);

        $this->user->find($id)->update($request->validated());
        return formatResponse(false,(["user"=>["user was successfully updated"]]));
    }

    /**
     * Soft delete the specified user from database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->userService->handleInvalidParameter($id,1);
        $this->userService->handleModelNotFound($id);

        $this->user->find($id)->delete();
        return formatResponse(false,(["user"=>["User deleted successfully"]]));
    }

    /**
     * Update the specified user password in database.
     *
     * @param  \Illuminate\Http\Request\ResetUserPassword  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetUserPassword $request,$email){
        $this->userService->handleInvalidParameter($email,2);
        $this->userService->handleModelNotFoundByEmail($email);

        $request = $request->validated();
        $request["password"] = bcrypt($request["password"]);
        $this->user->findEmail($email)->update($request);
        return formatResponse(false,(["user"=>["User password successfully reset"]]));
    }
}
