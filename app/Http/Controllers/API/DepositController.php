<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\DepositHelperController;
use App\Http\Controllers\BalanceHelperController;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Models\Balance;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    private $deposit;
    private $api;

    public function __construct()
    {
        $this->deposit = new DepositHelperController;
        $this->balance = new BalanceHelperController;
        $this->api = new ApiHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposits = Deposit::orderBy('id', 'desc')->get();

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
        $user = Auth::user();
        $deposit = Deposit::create([
            'user_id' => $request->userId,
            'total_deposit' => $request->totalDeposit,
            'deposit_date' => $request->depositDate,
            'is_main_savings' => $request->depositType,
            'status' => $user->roles->first()->id === 1 ? 1 : 0
        ]);
        if ($user->roles->first()->id === 1) $this->balance->createBalance($deposit, $request->totalDeposit, 1);
        return response()->json(['status' => 201, 'message' => 'Berhasil menambah setoran'], 201);
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
            'userId' => $deposit->users()->first()->id,
            'userName' => $deposit->users()->first()->name,
            'totalDeposit' => $deposit->total_deposit,
            'depositType' => $deposit->is_main_savings,
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
        $deposit = Deposit::find($id);
        $total_deposit_before_change = $deposit->total_deposit;
        if ($request->isChangeBalance) {
            $this->balance->createBalance($deposit, $request->totalDeposit - $total_deposit_before_change, 2);
        }
        $deposit->total_deposit = $request->totalDeposit;
        $deposit->deposit_date = $request->depositDate;
        $deposit->is_main_savings = $request->depositType;
        $deposit->update();
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah data setoran'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Deposit::find($id)->delete();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }

    public function status(Request $request, $id)
    {
        $deposit = Deposit::find($id);
        $deposit->status = $request->status;
        $deposit->save();
        $request->status === 1 && $this->balance->createBalance($deposit, $deposit->total_deposit, 2);
        return response()->json(['status' => 200, 'message' => 'Berhasil mengubah status setoran'], 200);
    }
}
