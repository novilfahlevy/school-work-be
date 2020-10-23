<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepositHelperController;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
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
        $deposits = Deposit::all();

        foreach ($deposits as $key => $deposit) {
            $data[$key] = [
                'id' => $deposit->id,
                'employeeName' => $deposit->users()->first()->name,
                'totalDeposit' => $deposit->total_deposit,
                'depositDate' => indonesian_date_format($deposit->deposit_date),
                'status' => $this->deposit->getDepositStatuses($deposit)
            ];
        }

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'deposits' => $data
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
        $deposit = Deposit::find($id);

        $data = [
            'id' => $deposit->id,
            'employeeName' => $deposit->users()->first()->name,
            'totalDeposit' => $deposit->total_deposit,
            'depositDate' => indonesian_date_format($deposit->deposit_date),
            'status' => $this->deposit->getDepositStatuses($deposit)
        ];

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'deposit' => $data
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
