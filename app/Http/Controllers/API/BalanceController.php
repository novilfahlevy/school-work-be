<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balance;

class BalanceController extends Controller
{
    private function getBalanceType($balance)
    {
        if ($balance->balanceable_type === "App\Models\Loan") {
            return "Peminjaman";
        } else if ($balance->balanceable_type === "App\Models\Payment") {
            return "Angsuran";
        } else {
            return "Setoran";
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        foreach (Balance::orderBy('id', 'desc')->get() as $balance) {
            $prev_balance = Balance::where('id', '<', $balance->id)->orderBy('id', 'desc')->first();
            $data[] = [
                'id' => $balance->id,
                'balance' => $balance->balance,
                'status' => $prev_balance !== null && $prev_balance->balance > $balance->balance ? "Penurunan" : "Peningkatan",
                'type' => $this->getBalanceType($balance),
                'changedAt' => $balance->changed_at
            ];
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data saldo', 'balances' => $data], 200);
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
        //
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
