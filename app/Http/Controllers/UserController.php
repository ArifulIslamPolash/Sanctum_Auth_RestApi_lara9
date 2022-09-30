<?php

namespace App\Http\Controllers;

use App\Http\Model\User;
use Illuminate\Http\Request;
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
}