<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiHelperController;
use App\Http\Controllers\Controller;
use App\Models\LoanSubmission;
use Illuminate\Http\Request;

class LoanSubmissionController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiHelperController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loan_submissions = LoanSubmission::latest('created_at')->get()->map(function ($loan_submission) {
            return [
                'id' => $loan_submission->id,
                'userId' => $loan_submission->user_id,
                'userName' => $loan_submission->user->name,
                'totalLoan' => $loan_submission->total_loan,
                'startDate' => indonesian_date_format($loan_submission->start_date),
                'createdDate' => indonesian_date_format($loan_submission->created_at),
                'status' => get_loan_submission_approve_status($loan_submission),
                'message' => $loan_submission->message
            ];
        });

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'loanSubmissions' => $loan_submissions
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
        $loan_submission = new LoanSubmission();
        $loan_submission->user_id = auth()->id();
        $loan_submission->total_loan = $request->totalLoan;
        $loan_submission->start_date = $request->startDate;
        $loan_submission->is_approve = null;
        $loan_submission->message = null;
        $loan_submission->save();

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
        $loan_submission = LoanSubmission::find($id);
        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->success_message,
            'loanSubmission' => [
                'id' => $loan_submission->id,
                'userId' => $loan_submission->user->id,
                'totalLoan' => $loan_submission->total_loan,
                'startDate' => indonesian_date_format($loan_submission->start_date),
                'status' => get_loan_submission_approve_status($loan_submission),
                'message' => $loan_submission->message
            ]
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
        $loan_submission = LoanSubmission::findOrFail($id);

        $loan_submission->user_id = auth()->id();
        $loan_submission->total_loan = $request->totalLoan ?? $loan_submission->total_loan;
        $loan_submission->start_date = $request->startDate ?? $loan_submission->start_date;
        $loan_submission->is_approve = 0;
        $loan_submission->message = $request->message ?? $loan_submission->message;
        $loan_submission->save();

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
        LoanSubmission::findOrFail($id)->delete();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }
    public function status(Request $request, $id)
    {
        $loan_submission = LoanSubmission::find($id);
        $loan_submission->is_approve = $request->isApprove;
        $loan_submission->message = $request->message;
        $loan_submission->save();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->updated_message,
            'loanSubmission' => $loan_submission
        ];

        return response()->json($responses, $this->api->success_code);
    }

    public function deleteAll()
    {
        LoanSubmission::truncate();

        $responses = [
            'status' => $this->api->success_code,
            'message' => $this->api->deleted_message
        ];

        return response()->json($responses, $this->api->success_code);
    }
}
