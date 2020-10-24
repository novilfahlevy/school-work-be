<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Models\Balance;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function createBalance($deposit, $addValue = null)
    {
        $balance = Balance::orderBy("id", "desc")->first();
        $current_balance = $balance->balance + $deposit->total_deposit;
        if ($addValue !== null) {
            $current_balance = $balance->balance + $addValue;
        }
        $deposit->balance()->create([
            'balance' => $current_balance,
            'changed_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function index()
    {
        $data = Deposit::listOfDeposits();

        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data setoran', 'deposits' => $data], 200);
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
        if ($user->roles->first()->id === 1) $this->createBalance($deposit);
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
        $data = Deposit::detailsOfDeposit($id);
        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil detail setoran', 'deposit' => $data], 200);
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
            $this->createBalance($deposit, $request->totalDeposit - $total_deposit_before_change);
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
        //
    }
}
