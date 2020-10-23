<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\LoanHelperController;

class UserController extends Controller
{
    private $loan;

    public function __construct()
    {
        $this->loan = new LoanHelperController;
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

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data pengguna', 'users' => $data], 200);
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
        //
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

        return response()->json(['status' => 200, 'message' => 'Berhasil data detail pengguna', 'user' => $data], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
