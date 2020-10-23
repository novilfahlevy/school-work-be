<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DepositHelperController;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    private $deposit;

    public function __construct()
    {
        $this->deposit = new DepositHelperController;
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
                'phoneNumber' => $employee->phone_number,
                'joinDate' => indonesian_date_format($employee)
            ];
        }

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data pegawai', 'users' => $data], 200);
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
        $employee = User::find($id);

        $data = [
            'id' => $employee->id,
            'name' => $employee->name,
            'gender' => get_gender_name($employee),
            'email' => $employee->email,
            'phoneNumber' => $employee->phone_number,
            'joinDate' => indonesian_date_format($employee->join_date),
            'birthDate' => indonesian_date_format($employee->birth_date),
            'job' => $employee->job,
            'deposits' => $this->deposit->getDepositDataByUserId($employee->id)
        ];

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data!', 'user' => $data], 200);
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

        return response()->json(['status' => 200, 'message' => 'Data berhasil diubah!'], 200);
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

        return response()->json(['status' => 200, 'message' => 'Data berhasil dihapus!'], 200);
    }
}
