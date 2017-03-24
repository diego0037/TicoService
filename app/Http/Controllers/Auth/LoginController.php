<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use \Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function Login(Request $request)
    {
      $input = $request->all();
      $datos = DB::table('users')->where('email',$input['email'])->first();
      // var_dump($datos);die();
      if (!is_null($datos)) {
          $decrypted = decrypt($datos->password);
          $passwordUser =$input['password'];
// var_dump($passwordUser);die();
if($decrypted==$passwordUser){
var_dump('considen');die();
}else{
var_dump('no considen');die();
}
        var_dump($datos->password);
        var_dump($passwordUser);die();
        if ($datos->is_activated ==0) {
          return response()->json(['error'=>array(['code'=>422,'message'=>'Verifique su bandeja de correos para activar su cuenta.Su cuenta no esta activa'])],422);

        }

      }
      return response()->json(['error'=>array(['code'=>422,'message'=>'Su cuenta no esta registrada.'])],422);

    }
}
