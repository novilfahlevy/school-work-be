<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepositHelperController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private $deposit;
    private $api;

    public function __construct()
    {
        $this->deposit = new DepositHelperController;
        $this->api = new ApiHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::whereHas('roles', function ($query) {
            $query->where('role_id', 2);
        })->orderBy('name', 'ASC')->get();

        foreach ($employees as $key => $employee) {
            $data[$key] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'gender' => get_gender_name($employee),
                'email' => $employee->email,
                'role' => User::getUserRoleName(User::find($employee->id)),
                'phoneNumber' => $employee->phone_number,
                'joinDate' => indonesian_date_format($employee)
            ];
        }

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'users' => $data
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->phone_number = $request->phoneNumber;
        $user->join_date = $request->joinDate;
        $user->date_of_birth = $request->dateOfBirth;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->job = $request->job;
        $user->save();

        $user->roles()->sync($request->role);

        $responses = [
            'status' => $this->api->created_code,
            'message' => $this->api->created_message
        ];

        return response()->json($responses, $this->api->created_code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = User::findOrFail($id);

        $data = [
            'id' => $employee->id,
            'name' => $employee->name,
            'gender' => get_gender_name($employee),
            'email' => $employee->email,
            'address' => $employee->address,
            'phoneNumber' => $employee->phone_number,
            'joinDate' => indonesian_date_format($employee->join_date),
            'dateOfBirth' => indonesian_date_format($employee->date_of_birth),
            'role' => $employee->roles->first()->id,
            'job' => $employee->job,
            'deposits' => $this->deposit->getDepositDataByUserId($employee->id)
        ];

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'user' => $data
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = User::find($id);

        $employee->name = $request->name ?? $employee->name;
        $employee->gender = $request->gender ?? $employee->gender;
        $employee->email = $request->email ?? $employee->email;
        $employee->phone_number = $request->phoneNumber ?? $employee->phone_number;
        $employee->join_date = $request->joinDate ?? $employee->join_date;
        $employee->date_of_birth = $request->dateOfBirth ?? $employee->date_of_birth;
        $employee->address = $request->address ?? $employee->address;
        $employee->job = $request->job ?? $employee->job;
        $employee->save();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->updated_message
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }
}
