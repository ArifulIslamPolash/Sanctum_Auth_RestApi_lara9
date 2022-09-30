<?php

namespace App\Http\Controllers;

use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'failed','validation_errors' => $validator->errors()]);
        }

        $inputs=$request->all();
        $inputs['password']=Hash::make($request->password);

        $user = User::create($inputs);

        if (!is_null($user)) {
            return response()->json(['status' => 'success','message' => 'User registration successfully completed', 'data' => $user]);
        } else {
            return response()->json(['status' => 'success','message' => 'User registration failed']);
        }



    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[

            'email'=>'required|email',

            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'failed','validation_errors' => $validator->errors()]);
        }
        $user = User::where('email',$request->email)->first();

        if (!is_null($user)) {
            return response()->json(['status' => 'failed','message' => 'User not found']);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(['status'=>'success','login'=>true, 'token'=>$token,'data'=>$user]);
        }else{
            return response()->json(['status'=>'failed','success'=>false, 'message'=>'Whops! Invalid password']);
        }

    }










}