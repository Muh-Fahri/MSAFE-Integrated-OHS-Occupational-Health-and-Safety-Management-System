<?php

namespace App\Http\Controllers\Transaction;

use App\Models\User;
use App\Models\Delegation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class DelegationController extends Controller {
    public function index(Request $request){
        $user = Auth::user();
        $delegation = Delegation::where('type', 'ALL')
            ->where('delegator', $user->id)
            ->where('begin_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->first();
        $delegation_start_date = '';
        $delegation_end_date = '';
        $delegation_user_id = '';
        if($delegation!=null){
            $delegation_start_date  = $delegation->begin_date;
            $delegation_end_date    = $delegation->end_date;
            $delegation_user_id     = $delegation->delegatee;
        }
        $list_user = [''=>'-- Select --'];
        $res = User::where('status', 'active')->get(['id', 'name']);
        foreach($res as $v){
            $list_user[$v->id] = $v->name;
        }
        return view('transaction.delegation.index', compact(
            'delegation_start_date',
            'delegation_end_date',
            'delegation_user_id',
            'list_user'
        ));
    }

    public function store(Request $request){
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $old_delegation_user_id = 0;
            $delegation = Delegation::where('type', 'ALL')->where('delegator', $user->id)->first();
            if($delegation!=null){
                $old_delegation_user_id     =  $delegation->delegatee;
            } else {
                $delegation                 = new Delegation;
                $delegation->type           = 'ALL';
                $delegation->delegator      = $user->id;
            }
            $delegation->begin_date     = $request->input('delegation_start_date');   
            $delegation->end_date       = $request->input('delegation_end_date');   
            $delegation->delegatee      = $request->input('delegation_user_id');
            $delegation->save();   
            $data = (object) ['delegator_name' => $user->name, 'delegatee_name' => '', 'delegation_start_date' => $request->delegation_start_date, 'delegation_end_date' => $request->delegation_end_date, 'status' => 'GRANTED', 'type' => 'ALL'];
            if(!empty($request->delegation_user_id)){
                $delegatee = User::find($request->delegation_user_id);
                $data->delegatee_name = $delegatee->name;

                // send email to delegatee
                Mail::send('mail.delegation_notification', ['data' => $data, 'delegatee' => $delegatee], function($message) use($data, $delegatee){
                    $message->to($delegatee->email);
                    $message->subject('Delegation Notification');
                });

                $delegatee = User::find($old_delegation_user_id);
                if($delegatee!=null){
                    $data->status = 'REMOVED';
                    $data->delegatee_name = $delegatee->name;
                    // send email to delegatee
                    Mail::send('mail.delegation_notification', ['data' => $data, 'delegatee' => $delegatee], function($message) use($data, $delegatee){
                        $message->to($delegatee->email);
                        $message->subject('Delegation Notification');
                    });
                }
            } else {
                $delegatee = User::find($old_delegation_user_id);
                if($delegatee!=null){
                    $data->status = 'REMOVED';
                    $data->delegatee_name = $delegatee->name;
                    // send email to delegatee
                    Mail::send('mail.delegation_notification', ['data' => $data, 'delegatee' => $delegatee], function($message) use($data, $delegatee){
                        $message->to($delegatee->email);
                        $message->subject('Delegation Notification');
                    });
                }
            }
            DB::commit();
            return redirect()->route('transaction-delegation.index')
                ->with('success', 'Delegation Saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurs: ' . $e->getMessage());
        }
    }
    
}
