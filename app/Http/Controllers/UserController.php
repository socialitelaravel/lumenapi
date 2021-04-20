<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jenssegers\Agent\Agent;
use App\Mail\WelcomeMail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $apiToken;
    public function __construct()
    {
       
      $this->apiToken = uniqid(base64_encode(Str::random(40)));

    }

    public function viewUser(Request $request,$id)
    {
      
        $user = User::where('id',$id)->first();
        return $user;
    }

    public function storeUser(Request $request)
    {
      try{
           
        // Validations
             $rules = [
                'name' => 'required|min:3',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:8'
                ];
              $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    // Validation failed
                      return response()->json([
                      'message' => $validator->messages(),
                   ]);
                   }else{
                      $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password'=> Hash::make($request->password),
                        'phone'=> $request->phone,
                         'token' => $this->apiToken,
                        ]);
                    }
                     $token = $user->token;
                    if($user->save()){
                      Mail::to($request->email)->send(new WelcomeMail($token));
                      return response()->json(['status'=>'sucess','message'=>'User craeted sucessfully']);
                        
                    }
                    
                    
                    }catch(Exception  $e){
                      return response()->json(['status'=>'error','message'=>$e->getMessage()]);
                    }
    }


    public function updateUser(Request $request, $id)
    {
        try{
                $user = User::where('id',$id)->first();
                $user->name = $request->name;
                $user->email = $request->email;
                
                if($request->password!=null){
                    $user->password = Hash::make($request->password);
                  }
                    
                if($user->save()){
                    return response()->json(['status'=>'sucess','message'=>'User updated sucessfully']);
                }
        
                }catch(Exception $e){
                    return response()->json(['status'=>'error','message'=>$e->getMessage()]);
                }
                
    }

    public function deleteUser($id)
    {
        $post= User::where('id',$id)->delete();
        return response()->json(['status'=>'sucess','message'=>'User deleted sucessfully']);
    }
         
    public  function login(Request $request)
    {
      
        $agent = new Agent();
        $platform = $agent->platform();
        $win_version = $agent->version($platform);
        $browser = $agent->browser();
        $browser_version = $agent->version($browser);
      
        $user_details = serialize(array(
          'platform' => $platform,
          'windows_version' => $win_version ,
          'user_ip_address' => $request->ip(),
          'browser' => $browser,
          'browser_version' => $browser_version
          ));
       
        
          $user = User::where('email',$request->email)->first();
          
        if($user){
            
            if($user->status == 1)
              {
                // Verify the password
                if( password_verify($request->password, $user->password) ) {
                  $login = User::where('email',$request->email)->update(['status' =>1, 'token' => $this->apiToken, 'user_system_detail'=>$user_details]);
                
                  if($login){
                    return response()->json([
                        'name' => $user->name,
                        'email' => $user->email,
                        'access_token' => $this->apiToken,
                        ]);
                    }
                    }else{
                      return response()->json(['status'=>'failed','message'=>'credentail does not match']);
                      }
                  }else{
                    return response()->json(['status'=>'unverify','message'=>'Please verify your email']);
                  }
                  }else{
                      return response()->json(['status'=>'failed','message'=>'email does not match']);
                    }
        }
              

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('token',$token)->first();
        
        if($user) {
            $logout = User::where('id',$user->id)->update(['token' => null, 'status' => 0 ]);
        
          if($logout){
              return response()->json([
              'message' => 'User Logged Out',
            ]);
          }
          }else {
              return response()->json([
              'message' => 'User not found',
            ]);
          }
    }

     public function verifyUser($token)
     {
        $user = User::where('token', $token)->first();
        if(isset($user) ){
          User::where('id',$user->id)->update(['token' => null, 'status' => 1 ]);
           
            return response()->json([
              'message' => 'Your e-mail is verified. You can now login.',
            ]);
          
        } else {
          return response()->json([
            'message' => 'Something went wrong',
          ]);
        }
        
      }

      public function passwordReset()
      {
        return view('emails.resetpassword');
      }

    
      public function forgetPassword(Request $request)
      {
       
        $user = User::where('email',$request->email)->first();
       
        if($user){
            if($user->status == 1)
              {
                Mail::to($request->email)->send(new PasswordReset());
              }else{
                return response()->json(['status'=>'unverify','message'=>'Please verify your email']);
              }  
           }else{
            return response()->json(['status'=>'failed','message'=>'email does not match']);
           }
      }

    
    
    
      public function changePassword(Request $request)
      {
       $rules = [
          'new-password' => 'required|string|min:6|confirmed',
          ];
        $validator = Validator::make($request->all(), $rules);
          if($validator->fails()){
              // Validation failed
                return response()->json([
                'message' => $validator->messages(),
             ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return response()->json(['status'=>'failed','message'=>'Password changed successfully']);
      }

  }