<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use App\Models\Email;
use Validator;

class EnquiryManageController extends Controller
{
    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function index(Request $request)
    {
        if(($user = $request->user()) && $user->user_type == 'admin'){
            $data = Enquiry::when($request->customer_name, function($q) use($request){
                $q->whereHas('createdUser', function($q1) use($request) {
                    $q1->where('full_name','like', '%'.$request->customer_name.'%')
                    ->orWhere('username','like', '%'.$request->customer_name.'%');
                });
            })->when($request->status, function($q) use($request){
                $q->where('status',$request->status);
            })->with([
                'replies'=>function($q){
                    $q->select('id','reply', 'is_read', 'user_type','notification_email','enquiry_id','created_by');
                },
                'createdUser'=>function($q){
                    $q->select('id','username', 'email','full_name');
                },
                'closedUser'=>function($q){
                    $q->select('id','username', 'email','full_name');
                }
            ])->select(
                'id',
                'enquiry_code',
                'question',
                'is_read',
                'status',
                'closed_by',
                'closed_at',
                'created_by'
            )->get();

            return response()->json([
                'status' => 'Success',
                'data' => $data,
            ], 200);

        }
        return response()->json(['status' =>'Error', 'message'=>'No Permission to see!!'], 404);

    }


    /*
	 * Create Question
	*/
	public function createQuestion(Request $request) {
        if(($user = $request->user())&& $user->user_type == 'customer'){
            $data = $request->all();
            $validator = Validator::make($data, [
                'question' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' =>'Validation-Error', 'message'=>$validator->messages()], 422);
            }
            $data['enquiry_code'] = $this->generateEnquiryCode();
            $data['created_by'] = $user->id;
            $data['is_read'] = 0;
            $data['status'] = 'Not Answered';

            if($enquiry = Enquiry::create($data)) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $enquiry->only([
                        'enquiry_code',
                        'question',
                        'is_read',
                        'status'
                    ]),
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Failed to Create'], 404);
        }
        return response()->json(['status' =>'Error', 'message'=>'You are not a customer!!, Cannot create the Question'], 404);


	}

     /*
	 * View Question
	*/
	public function viewQuestion(Request $request, $enquiry_code) {
        if($user = $request->user()){

            if($enquiry = Enquiry::with([
                'replies'=>function($q){
                    $q->select('id','reply', 'is_read', 'user_type','notification_email','enquiry_id','created_by');
                },
                'replies.createdUser'=>function($q){
                    $q->select('id','username', 'email', 'user_type','full_name');
                },
                'createdUser'=>function($q){
                    $q->select('id','username', 'email','full_name');
                },
                'closedUser'=>function($q){
                    $q->select('id','username', 'email','full_name');
                }
            ])->where('enquiry_code', $enquiry_code)->select(
                'id',
                'enquiry_code',
                'question',
                'is_read',
                'status',
                'closed_by',
                'closed_at',
                'created_by'
            )->first()??null) {
                if($user->user_type == 'admin'){
                    if($enquiry->is_read == 0){
                        $enquiry->is_read = 1;
                        $enquiry->status = 'In Progress';
                        $enquiry->save();
                    }
                }elseif($enquiry->created_by != $user->id){
                    return response()->json(['status' =>'Error', 'message'=>'No Permission to see!!'], 404);

                }
                return response()->json([
                    'status' => 'Success',
                    'data' => $enquiry,
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);
        }
        return response()->json(['status' =>'Error', 'message'=>'You are not a valid user!!'], 404);

	}

    /**
     * All replies
     */
    function allreplies(Request $request, $enquiry_code)
    {
        if($user = $request->user()){

            if($enquiry =
                Enquiry::with([
                    'replies'=>function($q){
                        $q->select('id', 'reply', 'is_read', 'user_type','notification_email','enquiry_id','created_by');
                    },
                    'replies.createdUser'=>function($q){
                        $q->select('id','username', 'email', 'user_type','full_name');
                    }
                ])->where('enquiry_code', $enquiry_code)->first()??null

            ) {
                if($user->user_type == 'admin'){
                    if($enquiry->is_read == 0){
                        $enquiry->is_read = 1;
                        $enquiry->status = 'In Progress';
                        $enquiry->save();
                    }
                }elseif($enquiry->created_by != $user->id){
                    return response()->json(['status' =>'Error', 'message'=>'Not Permission to see!!'], 404);

                }
                return response()->json([
                    'status' => 'Success',
                    'data' => $enquiry->replies,
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);
        }
        return response()->json(['status' =>'Error', 'message'=>'You are not a valid user!!'], 404);


    }

    /**
     * View Answer
     */
    function viewAnswer(Request $request, $id)
    {
        if($user = $request->user()){
            if($answer =
                EnquiryResponse::with([
                    'enquiry'=>function($q)use($id){
                        $q->select(
                            'id',
                            'enquiry_code',
                            'question',
                            'is_read',
                            'status',
                            'closed_at'
                        );
                    },
                    'createdUser'=>function($q){
                        $q->select('id','username', 'email', 'user_type','full_name');
                    }
                ])->select( 'id', 'reply', 'is_read', 'user_type','created_by', 'enquiry_id')->find($id)??null

            ) {
                if(($user->user_type != 'admin') && ($answer->enquiry->created_by != $user->id)){
                    return response()->json(['status' =>'Error', 'message'=>'Not Permission to see!!'], 404);
                }
                if($user->user_type != $answer->user_type){
                    if($answer->is_read == 0){
                        $answer->is_read = 1;
                        $answer->save();
                    }
                }
                return response()->json([
                    'status' => 'Success',
                    'data' => $answer,
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);
        }
        return response()->json(['status' =>'Error', 'message'=>'You are not a valid user!!'], 404);


    }

    /**
     * Post Answer
     */
    function postAnswer(Request $request, $enquiry_code)
    {
        if($user = $request->user()){
            if($enquiry = Enquiry::where('enquiry_code', $enquiry_code)->first()??null) {

                if(($user->user_type != 'admin') && ($enquiry->created_by != $user->id)){
                    return response()->json(['status' =>'Error', 'message'=>'No Permission!!'], 404);

                }
                if(in_array($enquiry->status, ['Answered', 'SPAM'])){
                    return response()->json(['status' =>'Error', 'message'=>'The Question has Already Closed!!'], 404);
                }
                $data = $request->all();
                $validator = Validator::make($data, [
                    'reply' => 'required|string|max:1000',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' =>'Validation-Error', 'message'=>$validator->messages()], 422);
                }
                $data['created_by'] = $user->id;
                $data['enquiry_id'] = $enquiry->id;
                $data['user_type'] = $user->user_type;
                $data['is_read'] = 0;

                if($reply = EnquiryResponse::create($data)) {

                    if($reply->user_type && ($enquiry->createdUser->email??null)){
                        Email::insert([
                            'source_table' => EnquiryResponse::class,
                            'source_table_id' => $reply->id,
                            'to' => $enquiry->createdUser->email??null,
                            'from' => 'Online support',
                            'subject' => 'Re: '.$reply->enquiry->enquiry_code,
                            'content' => view('emails.notification', ['enquiry'=> $reply->enquiry, 'answer'=>$reply]),
                            'status' =>'Pending'
                        ]);
                    }
                    return response()->json([
                        'status' => 'Success',
                        'data' => $reply->only([
                            'id', 'reply', 'is_read', 'user_type',
                        ]),
                    ], 200);
                }
                return response()->json(['status' =>'Error', 'message'=>'Failed to Create'], 404);

            }else{
                return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);
            }

            if($answer =
                EnquiryResponse::with([
                    'enquiry'=>function($q)use($id){
                        $q->select(
                            'enquiry_code',
                            'question',
                            'is_read',
                            'status',
                            'closed_by',
                            'closed_at',
                            'created_by'
                        );
                    },
                    'replies.createdUser'=>function($q){
                        $q->select('username', 'email', 'user_type','full_name');
                    }
                ])->find($id)??null

            ) {
                if($answer->enquiry->created_by != $user->id){
                    return response()->json(['status' =>'Error', 'message'=>'Not Permission to see!!'], 404);

                }
                if($user->user_type != $answer->user_type){
                    if($answer->is_read == 0){
                        $answer->is_read = 1;
                        $answer->save();
                    }
                }
                return response()->json([
                    'status' => 'Success',
                    'data' => $answer,
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);
        }
        return response()->json(['status' =>'Error', 'message'=>'You are not a valid user!!'], 404);

    }

    /**
     * SPAM Report
     */
    public function spamReport(Request $request, $enquiry_code){

        if(($user = $request->user()) && $user->user_type == 'admin'){
            if($enquiry = Enquiry::where('enquiry_code', $enquiry_code)->first()??null) {

                if(($user->user_type != 'admin') && ($enquiry->created_by != $user->id)){
                    return response()->json(['status' =>'Error', 'message'=>'No Permission!!'], 404);

                }

                if(in_array($enquiry->status, ['Answered', 'SPAM'])){
                    return response()->json(['status' =>'Error', 'message'=>'The Question has Already Closed!!'], 404);
                }
                if($answer->is_read == 0){
                    $answer->is_read = 1;
                }
                $enquiry->status = 'SPAM';
                $enquiry->closed_by = $user->id;
                $enquiry->closed_at = now();
                $enquiry->save();

                return response()->json([
                    'status' => 'Success',
                    'data' => $enquiry->only([
                        'enquiry_code',
                        'question',
                        'is_read',
                        'status'
                    ]),
                ], 200);

            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);

        }
        return response()->json(['status' =>'Error', 'message'=>'No Permission to see!!'], 404);

    }

    /**
     * Close Enquiry
     */
    public function closeEnquiry(Request $request, $enquiry_code){

        if(($user = $request->user()) && $user->user_type == 'admin'){
            if($enquiry = Enquiry::where('enquiry_code', $enquiry_code)->first()??null) {

                if(($user->user_type != 'admin') && ($enquiry->created_by != $user->id)){
                    return response()->json(['status' =>'Error', 'message'=>'No Permission!!'], 404);

                }

                if(in_array($enquiry->status, ['Answered', 'SPAM'])){
                    return response()->json(['status' =>'Error', 'message'=>'The Question has Already Closed!!'], 404);
                }
                if($answer->is_read == 0){
                    $answer->is_read = 1;
                }
                $enquiry->status = 'Answered';
                $enquiry->closed_by = $user->id;
                $enquiry->closed_at = now();
                $enquiry->save();

                return response()->json([
                    'status' => 'Success',
                    'data' => $enquiry->only([
                        'enquiry_code',
                        'question',
                        'is_read',
                        'status'
                    ]),
                ], 200);
            }
            return response()->json(['status' =>'Error', 'message'=>'Not Found'], 404);

        }
        return response()->json(['status' =>'Error', 'message'=>'No Permission to see!!'], 404);

    }

    /**
     * Generate Enquiry Code
     */
    public function generateEnquiryCode(){
        do {
            $code = Str::random(10);

        } while (Enquiry::where("enquiry_code", "=", $code)->first() instanceof Enquiry);

        return $code;
    }

}
