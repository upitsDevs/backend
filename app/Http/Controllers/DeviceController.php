<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\device;
use Hash;
class DeviceController extends Controller
{
    //
	public function create(Request $request) {
		$device_mac = $request->input('mac_address');
		$device_id = str_replace(':','',$device_mac);
		$data = array(
			"device" => $device_mac,
			"global_ip" => $request->ip(),
			"local_ip" => $request->input('local_ip'),
			"sn" => $request->input('sn')
		);
		$device = device::firstOrCreate([ "deviceID" => $device_id ], $data);
		$device->global_ip = $request->ip();
		$device->local_ip  = $request->input('local_ip');
		$device->touch();
		$device->save();
		return response($device);
	}
	
	public function list() {
		$devices = device::all();
		return $devices;
	}
	public function getDevice(Request $request){
		$id = $request->input('device_id');
		try{
		$device = device::where('deviceID','=',$id)->firstOrFail();
		return response()->json($device,200);
		}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
						return response()->json('No Device Id or Not Found',404);
			}
	}
	public function get_local_ip(Request $request) {
		$device_id = $request->input('deviceID');
		$device = device::where('deviceID','=',$device_id)->firstOrFail();
		return response()->json($device->local_ip);
	}
	public function ping() {
		$status = array(
						'status' => 'connected',
						);
		return response()->json($status,200);
	}
	public function get_global_ip(Request $request) {
		$device_id = $request->input('deviceID');
		$password = $request->input('password');
		try {
			$device = device::where('deviceID','=',$device_id)->firstOrFail();
				if($device->password !== null) {
					if( Hash::check($password,$device->password)){
					return response()->json($device->global_ip) ;	
					}else{
						return response()->json('Unauthorised',401);
					}
				}else{
					return response()->json($device->global_ip) ;
				}
		}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
					return response()->json('No Device Id or Not Found',404);
		}
	}
	public function set_passsword(Request $request){
		$device_id = $request->input('device_id');
		$password = $request->input('password');
		$device = device::where('deviceID','=',$device_id)->firstOrFail();
		if($device->password == null)
		{
			$hashed = Hash::make($password);
			$device->password = $hashed;
			$device->save();
			$data = array(
						  'device' => $device,
						  'password' => $hashed
						  );
			return response()->json($data);
		}else{
			return response()->json('Error password has been set before',401);
		}
	}
}
