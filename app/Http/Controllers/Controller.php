<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected static $httpSuccessCode;
	protected static $httpCreatedCode;
	protected static $httpFailureCode;
	protected static $httpAuthInvalidCode;
	protected $dateFormat = 'Y-m-d';
	protected $dateTimeFormat = 'Y-m-d H:i:s';
	protected $user;

	public function __construct() {

		self::$httpSuccessCode = app('config')->get('general_settings.httpSuccessCode');
		self::$httpCreatedCode = app('config')->get('general_settings.httpCreatedCode');
		self::$httpFailureCode = app('config')->get('general_settings.httpFailureCode');
		self::$httpAuthInvalidCode = app('config')->get('general_settings.httpAuthInvalidCode');

		$this->middleware(function ($request, $next) {
			$this->user = Auth::user();
			return $next($request);
		});
	}

	/**
	 * Creates the error response object
	 *
	 * @param  Illuminate\Support\MessageBag $errors  error messages
	 * @param  string                        $message error message
	 *
	 * @return Illuminate\Http\Response               response
	 */
	public function createErrorResponse($errors, $message = "") {
		$errorMessages    = [];
		$validationErrors = json_decode($errors);

		foreach ($validationErrors as $key => $value) {
			$errorMessages[$key] = is_array($value) ? $value[0] : $value;
		}

		return response()->json([
			'status'	=> 'Fail',
			'message' 	=> $message ?: 'Validation failed',
			'data'    	=> (Object)[],
			'error'   	=> $errorMessages
		], 400);
	}

	public function responseSentToApi($data,$code,$responseEncodingOption='')
	{
		if($responseEncodingOption==''){
			return response()->json($data, $code)->setEncodingOptions(JSON_NUMERIC_CHECK);
		}
		else{
			return response()->json($data, $code)->setEncodingOptions($responseEncodingOption);
		}
	}
	public function loginresponseSentToApi($data, $token, $code, $responseEncodingOption = '')
	{
		if ($responseEncodingOption == '') {
			return response()->json($data, $code)->header('Authorization', $token)->setEncodingOptions(JSON_NUMERIC_CHECK);
		} else {
			return response()->json($data, $code)->header('Authorization', $token)->setEncodingOptions($responseEncodingOption);
		}
	}
}
