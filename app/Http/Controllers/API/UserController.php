<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\LoanHelperController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiHelperController;

class UserController extends Controller
{
    private $loan;
    private $api;

    public function __construct()
    {
        $this->loan = new LoanHelperController;
        $this->api = new ApiHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })->orderBy('name', 'ASC')->get();

        foreach ($users as $key => $user) {
            $data[$key] = [
                'id' => $user->id,
                'name' => $user->name,
                'gender' => get_gender_name($user),
                'email' => $user->email,
                'phoneNumber' => $user->phone_number,
                'joinDate' => indonesian_date_format($user->join_date)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $user->roles()->sync(3);

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
        $user = User::find($id);

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'gender' => get_gender_name($user),
            'email' => $user->email,
            'phoneNumber' => $user->phone_number,
            'joinDate' => indonesian_date_format($user->join_date),
            'birthDate' => indonesian_date_format($user->birth_date),
            'job' => $user->job,
            'loans' => $this->loan->getLoansDataByUserId($user->id)
        ];

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'user' => $data
        ];

        return response()->json($responses, $this->api->success_code);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user = User::find($id);

        $user->name = $request->name ?? $user->name;
        $user->gender = $request->gender ?? $user->gender;
        $user->email = $request->email ?? $user->email;
        $user->phone_number = $request->phoneNumber ?? $user->phone_number;
        $user->join_date = $request->joinDate ?? $user->join_date;
        $user->date_of_birth = $request->dateOfBirth ?? $user->date_of_birth;
        $user->address = $request->address ?? $user->address;
        $user->job = $request->job ?? $user->job;
        $user->save();

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
    public function destroy($id, $type = null)
    {
        $user = User::find($id);

        // Jika data tidak ditemukan
        if (is_null($user)) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan!'], 404);
        }

        // Jika user tidak memiliki relasi maka dihapus
        if (!$user->loans()->exists()) {
            $user->delete();
        }

        // Jika user memiliki relasi dan parameter akhir di url tidak sama dengan null
        if ($user->loans()->exists() && $type !== null) {
            $user->delete();
        }

        // Jika data memiliki relasi dan parameter akhir di url sama dengan null
        if ($user->loans()->exists()) {
            return response()->json(['status' => 403, 'message' => 'Data memiliki relasi! Gunakan `force` di akhir parameter url untuk yakin menghapus!'], 403);
        }

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }
}
