<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Delegation;
use App\Models\Incident;
use App\Models\IncidentNextApprover;
use App\Models\CorrectiveAction;
use App\Models\CorrectiveActionNextApprover;
use App\Models\License;
use App\Models\LicenseNextApprover;
use App\Models\Asset;
use App\Models\AssetNextApprover;
use App\Models\BadgeRequest;
use App\Models\BadgeRequestNextApprover;
use App\Models\User;

class ProfileComposer {
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        $path = Request()->path();
        $profile = $this->user;
        $profile->image = !empty($profile->avatar_thumb)?$profile->avatar_thumb:'/img/user.png';
        $notification = [
            'count' => 0,
            'list'  => []
        ];
        $user_ids = [$profile->id];
        $delegation = Delegation::where('type', 'ALL')
            ->where('delegatee', $profile->id)
            ->where('begin_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->first();
        if($delegation!=null){
            $user_ids[] = $delegation->delegator;
        }

        // INCIDENT NOTIFICATION
        $res = IncidentNextApprover::whereIn('user_id', $user_ids)->get(['incident_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->incident_id;
        }
        $in_count = Incident::whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $in_count;
        if($in_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'IN',
                'application_name'      => 'Incident Notification',
                'icon'                  => 'fas fa-car-crash',
                'url'                   => '/transaction/incident-notification?action=APPROVAL',
                'count'                 => $in_count,
                'message'               => 'You have '.$in_count.' requests to approve.'
            ];
        }

        // INCIDENT INVESTIGATION
        $res = IncidentNextApprover::whereIn('user_id', $user_ids)->get(['incident_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->incident_id;
        }
        $ii_count = Incident::whereIn('status', ['INVESTIGATION_APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $ii_count;
        if($ii_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'II',
                'application_name'      => 'Incident Investigation',
                'icon'                  => 'fas fa-car-crash',
                'url'                   => '/transaction/incident-investigation?action=APPROVAL',
                'count'                 => $ii_count,
                'message'               => 'You have '.$ii_count.' requests to approve.'
            ];
        }

        // CORRECTIVE ACTION - TODO
        $res = User::whereRaw("CONCAT(department_id,'-',company_id) IN (SELECT CONCAT(department_id,'-',company_id) FROM users WHERE id IN (".implode(',',$user_ids)."))")
                ->get(['id']);
        $user_todo_ids = $user_ids;
        foreach($res as $v){
            $user_todo_ids[] = $v->id;
        }
        $res = CorrectiveActionNextApprover::whereIn('user_id', $user_todo_ids)->get(['action_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->action_id;
        }
        $cartodo_count = CorrectiveAction::whereIn('status', ['ACTION_REQUIRED', 'REJECTED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $cartodo_count;
        if($cartodo_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'CARA',
                'application_name'      => 'Corrective Action (CAR)',
                'icon'                  => 'fas fa-clipboard-check',
                'url'                   => '/transaction/corrective-actions?action=TO_DO',
                'count'                 => $cartodo_count,
                'message'               => 'You have '.$cartodo_count.' corrective action(s) to do.'
            ];
        }


        // CORRECTIVE ACTION - APPROVAL
        $res = CorrectiveActionNextApprover::whereIn('user_id', $user_ids)->get(['action_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->action_id;
        }
        $car_count = CorrectiveAction::whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $car_count;
        if($car_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'CARA',
                'application_name'      => 'Corrective Action (CAR)',
                'icon'                  => 'fas fa-clipboard-check',
                'url'                   => '/transaction/corrective-actions?action=APPROVAL',
                'count'                 => $car_count,
                'message'               => 'You have '.$car_count.' requests to approve.'
            ];
        }

        // LICENSE TO OPERATE
        $res = LicenseNextApprover::whereIn('user_id', $user_ids)->get(['license_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->license_id;
        }
        $lto_count = License::whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $lto_count;
        if($lto_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'LTO',
                'application_name'      => 'License to Operate (LTO)',
                'icon'                  => 'fas fa-car',
                'url'                   => '/transaction/license?action=APPROVAL',
                'count'                 => $lto_count,
                'message'               => 'You have '.$lto_count.' requests to approve.'
            ];
        }

        // ASSET MANAGEMENT
        $res = AssetNextApprover::whereIn('user_id', $user_ids)->get(['asset_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->asset_id;
        }
        $am_count = Asset::whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $am_count;
        if($am_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'AM',
                'application_name'      => 'Asset Management (LTO)',
                'icon'                  => 'fas fa-truck-monster',
                'url'                   => '/transaction/asset?action=APPROVAL',
                'count'                 => $am_count,
                'message'               => 'You have '.$am_count.' requests to approve.'
            ];
        }

        // BADGE REQUEST
        $res = BadgeRequestNextApprover::whereIn('user_id', $user_ids)->get(['request_id']);
        $ids = [];
        foreach($res as $v){
            $ids[] = $v->request_id;
        }
        $br_count = BadgeRequest::whereIn('status', ['APPROVAL_REQUIRED'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $br_count;
        if($br_count>0){
            $notification['list'][] = (object) [
                'application_code'      => 'BR',
                'application_name'      => 'Badge Request (BR)',
                'icon'                  => 'fas fa-id-card',
                'url'                   => '/transaction/badge?action=APPROVAL',
                'count'                 => $br_count,
                'message'               => 'You have '.$br_count.' requests to approve.'
            ];
        }
        $br_count_print = BadgeRequest::whereIn('status', ['WAITING_TO_PRINT'])->whereIn('id', $ids)->get(['id'])->count(['id']);
        $notification['count'] += $br_count_print;
        if($br_count_print>0){
            $notification['list'][] = (object) [
                'application_code'      => 'BR',
                'application_name'      => 'Badge Request (BR)',
                'icon'                  => 'fas fa-id-card',
                'url'                   => '/transaction/badge?action=PRINTING',
                'count'                 => $br_count_print,
                'message'               => 'You have '.$br_count_print.' requests to print.'
            ];
        }
        

        $view->with([
            'profile'           => $profile,
            'notification'      => $notification,
        ]);
    }

}
