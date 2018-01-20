<?php

namespace App\Http\Controllers\API\v1;

use App\Transformers\NotificationTransformer;
use App\Transformers\SummaryNotificationTransformer;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Sentinel;
use App\Models\EForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{

    public function __construct(EForm $eform, User $user, UserNotification $userNotification)
    {
        $this->eform = $eform;
        $this->userNotification = $userNotification;
        $this->user = $user;
    }

    public function index(Request $request)
    {
       //
    }

   
    public function unread(Request $request)
    {
        $branch_id = ( request()->header( 'branch_id' ) != '' ) ? request()->header( 'branch_id' ) : 0 ;
        $role = ( request()->header( 'role' ) != '' ) ? request()->header( 'role' ) : 0 ;
        $pn = ( request()->header( 'pn' ) != '' ) ? request()->header( 'pn' ) : '' ;
    	$user_id = ( request()->header( 'user_id' ) != '' ) ? request()->header( 'user_id' ) : 0 ;

        $ArrGetDataNotification = [];
        $getDataNotification = $this->userNotification->getUnreads( substr($branch_id,-3), $role, '000'.$pn , $user_id);
        if($getDataNotification){
            foreach ($getDataNotification->get() as $value) {
                $ArrGetDataNotification[] = [
                                        'id' => $value->id,
                                        'url' => $value->getSubject($value->is_approved, $value->ref_number,$user_id)['url'],
                                        'subject' => $value->getSubject($value->is_approved, $value->ref_number,$user_id)['message'],
                                        'type' => $value->type,
                                        'notifiable_id' => $value->notifiable_id,
                                        'notifiable_type' => $value->notifiable_type,
                                        'role_name' => $value->role_name,
                                        'branch_id' => $value->branch_id,
                                        'data' => $value->data,
                                        'created_at' => $value->created_at->diffForHumans(),
                                        'is_read' => (bool) $value->is_read,
                                        'read_at' => Carbon::parse($value->read_at)->format('Y-m-d H:i:s'),
                                    ];
            }
        }
    	return  response()->success( [
            'message' => 'Sukses',
            'contents' => $ArrGetDataNotification
        ], 200 );
    }

    
    public function read(Request $request,$slug, $type)
    {
        $is_read = ( request()->header( 'is_read' ) != '' ) ? request()->header( 'is_read' ) : 0 ;
        $notification = $this->userNotification
            ->where( 'slug', $slug)->where( 'type_module', $type)->whereNull('read_at')
            ->firstOrFail();
       
        if($notification) $notification->markAsRead($is_read);    
        return  response()->success( [
            'message' => 'Sukses',
            'contents' => $notification
        ], 200 );

    }

    public function unReadMobile(Request $req)
    {
        $limit    = (empty($req->limit) ? 0 : $req->limit);
        $page     = (empty($req->page) ? 0 : $req->page);
        $role     = (empty($req->role) ? 0 : $req->role);
        $userId   = (empty($req->user_id) ? 0 : $req->user_id);
        $branchId = (empty($req->branch_id) ? 0 : $req->branch_id);
        $pn       = (empty($req->pn) ? 0 : $req->pn);
        $getDataNotification =  $this->userNotification->getUnreadsMobile(
                                    substr($branchId,-3), 
                                    $role, '000'.$pn , 
                                    $userId,
                                    request()->segment(3)
                                );
        return  response()->success( [
            'message' => 'Sukses',
            'contents' => $getDataNotification
        ], 200 );
        // echo request()->segment(3);
        // die();
        // var_dump(json_encode($getDataNotification->get()->paginate()));
        // die();
    }
}
