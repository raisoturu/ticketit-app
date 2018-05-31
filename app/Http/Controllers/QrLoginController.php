<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
// use App\User;
use Cache;
use Carbon\Carbon;

use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;
use DB;

class QrLoginController extends Controller
{
	
	protected $tickets;
    public function index(Request $request) {
    	
		return view('auth.QrLogin');
	}
	public function indexoption2(Request $request) {
    	
		return view('auth.QrLogin2');
	}
	public function ViewUserQrCode($value='')
	{
		return view('backEnd.users.viewqrcode');
	}
	public function checkUser(Request $request) {
		 $result =0;
		
			// if ($request->data) {
				// $user = User::where('QRpassword',$request->data)->first();
				$tickets = Ticket::where('QRpassword', $request->data)->first();
				//$tickets = Ticket::where('QRpassword',$request->data)->get();
				//$ticket = $this->tickets->findOrFail($request->data);
				if ($tickets->status_id==Setting::grab('default_pending')) {
					// Sentinel::authenticate($user);
					$result =1;
					app('Kordy\Ticketit\Controllers\TicketsController')->scan($tickets->id);//cek
				 }else{
				 	$result =0;
				 }
				
			//}
		
			return $result;
	}

	public function QrAutoGenerate(Request $request)
	{	
		$result=0;
		//echo $request->id; 
		//$user=$this->tickets->findOrFail($request->id);
		$user = $this->tickets->find($request->id);
		$qrLogin=bcrypt($user->nohp.$user->email.str_random(40));
	
        $user->QRpassword= $qrLogin;
        $user->update();
		$result=1;
		
        return $result;
	}

}