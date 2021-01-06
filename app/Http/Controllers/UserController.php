<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Models\Mesage;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    //User List  Auth_email
    public function userGet(Request $req){  
        if($req->email){
            $auth_user = User::where('email' , $req->email)->first();  //Auth User
            // $users = User::where('email' , '<>' , $req->email)->get(); //auth exacin chi tpum listi mej
            $users=User::all();

            $last_messages = Mesage::where([
                ['seen' , '=', 0],
                ['receiver_id','=' , $auth_user->id]
            ])->whereIn('creator_id' , $users->pluck('id'))
            ->orderBy('id' , 'DESC')
            ->get();
            $last_messages_group = [];

            foreach($last_messages as $v){
                if(isset($last_messages_group[$v['creator_id']])) continue;
                $last_messages_group[$v['creator_id']] = $v['message'];
            }
                
        }else{
            $users = User::all();
        }
        return ['auth_user'=>$auth_user,'users' => $users , 'messages' => $last_messages_group ?? '']  ;
        // return User::all();
    }
    


    //Login Request JO

    public function loginPost(Request $req)
    {
        $user = User::where(['email' => $req->email])->first();
        // Hash::check($req->password,$user->password)
        if ($user && $req->password == $user->password) {
            return $user;
        }
        return 'Not Match';
    }

    //Registration JO
    public function registerPost(Request $req)
    {

        $input_values = $req->only(['name', 'password', 'email', 'lastname','integration_id','img']);
        $input_values['password'] = $req->password;
        $has_email = User::where('email', $input_values['email'])->first(); //Ete ka User Tableum ->eta stugum

        if ($has_email) {
            return ['success' => false, 'message' => 'Email exists'];
        }

        if ($boolean = User::create($input_values)) { //True False  veradarcnelu hamara
            return ['success' => (bool) $boolean];
        }

        return ['success' => false, 'message' => 'Error'];
    }


    //insert User
    public function insertUsers(Request $req)
    {
        $inter = $req->users;
        $ids = [];
        foreach ($inter as $int) {
            $ids[] = $int['id'];
        }
        $exist_users = User::select('id', 'integration_id')->whereIn('integration_id', $ids)->pluck('id', 'integration_id');
      
        foreach ($inter as $int) {
            if (isset($exist_users[$int['id']])) continue;
            $user = [
                'integration_id' => $int['id'],
                'img' => $int['avatar'] ?? 'default.png',
                'name' => $int['firstname'],
                'lastname'=>$int['lastname'],
                'email'=>$int['id'],
                'password'=>$int['id'],
            ];
            
            User::create($user);
        }
        return response()->json(['success' => true]);
    }

    public function search(Request $req)
    {

        $users = User::all();
      
        $search_resault = [];
        $word_search =  $req->search;
        $regexp = '/.*' . $word_search . '.*/isu';

        foreach($users as $us){
            $append = preg_match($regexp , $us['name'] , $match) || preg_match($regexp , $us['lastname'] , $match);

            if($append) $search_resault[] = $us;
        }
        return $search_resault;
    }

}
