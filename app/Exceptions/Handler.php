<?php

namespace App\Exceptions;

use App;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $_dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $_dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Exception $e) {
            if (App::environment() !== "local") {
                $user = [];
                if (Auth::check()) {
                    $user = Auth::user();
                    $user = [
                        'id'        => $user->id,
                        'name'      => $user->name,
                        'user_type' => $user->user_type,
                    ];
                }
                Mail::send('mails.error', [
                    'headers'        => json_encode(app('request')->header()),
                    'inputs'         => json_encode(app('request')->all()),
                    'method'         => app('request')->method(),
                    'error_message'  => $e->getMessage(),
                    'url'            => app('request')->url(),
                    'error_category' => get_class($e),
                    'trace'          => $e->getTrace(),
                    'user'           => json_encode($user),
                ], function ($mail) {
                    $mail->to(['dhineshmca.11@gmail.com'])->subject('Fund Me Error - ' . env('APP_ENV'));
                });
            }
        });

        $this->renderable(function (Throwable $exception) {
            if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->route('login');
            }
            if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException)
            {
                return response()->view('errors.413');
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }
        return parent::render($request, $exception);
    }
}
