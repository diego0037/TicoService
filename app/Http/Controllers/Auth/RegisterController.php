<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use DB;
use Mail;
use \Crypt;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return IlluminateContractsValidationValidator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'required|max:20|min:6',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            // 'password' => Crypt::encrypt($data['password']),
            'password' => bcrypt($data['password']),
        ]);
    }
    public function register(Request $request) {
      $input = $request->all();
      $validator = $this->validator($input);
      if ($validator->passes()){
        $user = $this->create($input)->toArray();
        $user['link'] = str_random(30);
        DB::table('user_activations')->insert(['id_user'=>$user['id'],'token'=>$user['link']]);

        Mail::send('emails.activation', $user, function($message) use ($user){
          // $message->from('hola');
          $message->to($user['email']);
          $message->subject('Active su Cuenta para Finalizar su Registro en nuestra Aplicación');
        });
        return response()->json(['code'=>'201','mensaje'=>'Se le envio un codigo de activacion.Por favor verifique su email'],201);
        // return redirect()->to('login')->with('success',"We sent activation code. Please check your mail.");
      }
      return response()->json(['error'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el proceso.'])],422);
      // return back()->with('errors',$validator->errors());
    }

    public function userActivation($token){
      dd('on user activation');
      $check = DB::table('user_activations')->where('token',$token)->first();
      if(!is_null($check)){

        $user = User::find($check->id_user);
        if ($user->is_activated ==1){
      return response()->json(['error'=>array(['code'=>422,'message'=>'Su cuenta ya esta activada.No podemos activarla de nuevo'])],422);
          // return redirect()->to('login')->with('success',"user are already actived.");

        }
        // var_dump('no activado');die();
        // $user->update(['is_activated' => 1]);
        $user->is_activated =1;
        $user->save();
        // DB::table('user_activations')->where('token',$token)->delete();
        return response()->json(['code'=>'201','mensaje'=>'Su cuenta fue activa'],201);

        // return redirect()->to('login')->with('success',"user active successfully.");
      }
        return response()->json(['error'=>array(['code'=>422,'message'=>'Su codigo es invalido.'])],422);
 }
}
