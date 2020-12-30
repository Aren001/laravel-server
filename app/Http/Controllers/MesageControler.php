<?php

namespace App\Http\Controllers;

use App\Models\Mesage;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function GuzzleHttp\Promise\all;

class MesageControler extends Controller
{

    //Message  listna tpum  URL-i ID-nerov 
    // public function mesageGet(Request $req, $id)
    // {
    //     $user = User::where('email', $req->email)->first();     //Auth
    //     $messages = Mesage::where(function ($q) use ($user, $id) {   //ID-i pahna stugum
    //         $q->where('receiver_id', $user->id);
    //         $q->where('creator_id', $id);
    //     })->orWhere(function ($q) use ($user, $id) {  //-> ||
    //         $q->where('receiver_id', $id);
    //         $q->where('creator_id', $user->id);
    //     })->get();
    //     Mesage::where('receiver_id' , $user->id)->update([ 'seen' => 1]); 
    //     return $messages;

    // }


    //SEND MESAGE

    // public function store(Request $req)
    // {

    //     $user = User::where('email', $req->email)->first(); //Auth

    //     $to_user = User::where('id', $req->receiver_id)->first(); // Message to

    //     if ($user && $to_user) {

    //         $message = [
    //             'creator_id' => $user->id,
    //             'receiver_id' => $to_user->id,
    //             'seen' => 0,
    //             'message' => $req->message
    //         ];

    //         $message = Mesage::create($message);

    //         return ['success' => (bool) $message];
    //     }

    //     return ['success' => false, 'mes' => 'YES', 'req' => $req->all()];
    // }


        //chka

    // public function seenGet(Request $req)
    // {
    //     $user =  User::where('email', $req->email)->first(); //Auth

    //     $test1 = Mesage::where('seen',0)->where('receiver_id',$user->id)->get();
    //     // $test2 = Mesage::where('seen',0)->where('receiver_id',$user->id)->get();
    //     return $test1->toArray();

    // }
    public function delete($id)
    {
        $mess = Mesage::findOrFail($id);
        $mess->delete();
        return 204;
    }

    //PO
    public function mesageGet(Request $req, $id)
    {
        // return $req->user_id;
        $userId=$req->user_id;

            $messages = Mesage::where(function ($q) use ($userId, $id) {   //ID-i pahna stugum
                $q->where('receiver_id', $userId);
                $q->where('creator_id', $id);
            })->orWhere(function ($q) use ($userId, $id) {  //-> ||
                $q->where('receiver_id', $id);
                $q->where('creator_id', $userId);
            })->get();
            Mesage::where('receiver_id', $userId)->update(['seen' => 1]);

            return $messages;
    }



    public function store(Request $req)
    {

            $userId=$req->user_id; //inch uxarkelem eta vercnum
            // return $req->team_id;
            $message = [
                'team_id'=>$req->team_id,
                'creator_id' => $userId,
                'receiver_id' => $req->receiver_id,
                'seen' => 0,
                'message' => $req->message
            ];

            $message = Mesage::create($message);

            return ['success' => (bool) $message];

        return ['success' => false, 'mes' => 'YES', 'req' => $req->all()];
    }
}
