<?php  
namespace App\Helpers;
use Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SysUserLogActivity as LogActivityModel;

/**
 * 
 */
class UserLogActivity
{
	public static function addLog($subject)
	{
		$data = [
			'subject' => $subject,
			'url' => Request::fullUrl(),
			'method' => Request::method(),
			'ip_address' => Request::ip(),
			'agent' => Request::header('user-agent'),
			'user_id' => Auth::user()->id
		];
		LogActivityModel::create($data);
	}

	public static function logList()
	{
		return LogActivityModel::latest()->get();
	}
}
?>