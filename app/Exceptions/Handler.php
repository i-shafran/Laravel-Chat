<?php

namespace App\Exceptions;

use App\Events\ServerLog;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

use App\Models\ServerLogs;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
		\Illuminate\Validation\ValidationException::class,
	];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
		if($exception instanceof \PDOException and $exception->getCode() == 1045){
			die("Error connecting to database: ".$exception->getMessage());
		}

		if($exception instanceof NotFoundHttpException){
			return;
		}

		if ($this->shouldntReport($exception)) {
			return;
		}

		// Не отсылать письма с этим типом Exception
		$emailSend = true;
		if(
			$exception instanceof NotFoundHttpException or
			$exception instanceof ValidationException or
			$exception instanceof ModelNotFoundException or
			$exception instanceof AuthenticationException
		)
		{
			$emailSend = false;
		}

		$status_code = NULL;
		$user_id = NULL;
		$request = \request();
		$user = $request->user("api");

		if(method_exists($exception, "getStatusCode")){
			$status_code = $exception->getStatusCode();
		}
		if($user != NULL){
			$user_id = $user->id;
		}

		// Request Data
		$data = $request->all();
		$dataJson = json_encode($data);
		$dataStr = "";
		foreach ($data as $key => $value){
			if(!is_array($value)){
				$dataStr = $dataStr."$key => $value; ";
			} else {
				$dataStr = $dataStr."$key => ".json_encode($value)."; ";
			}
		}

		// Роутинг
		$route = $request->path();
		$url = $request->url();

		// Запись в базу лога
		/*$log = new ServerLogs();
		

		$log->message = $exception->getMessage();
		$log->route = $route;
		$log->exception = get_class($exception);
		$log->file = $exception->getFile();
		$log->line = $exception->getLine();
		$log->code = $exception->getCode();
		$log->status_code = $status_code;
		$log->user_id = $user_id;
		$log->request = $dataStr;
		$log->request_json = $dataJson;
		$log->url = $url;
		$log->trace = json_encode($exception->getTrace());
		$log->ip = $request->ip();
		$log->method = $request->method();
		$log->server_arg = json_encode($request->server());
		$log->save();

		event(new ServerLog($log, $emailSend));*/
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		if($exception instanceof CustomErrorException)
		{
			return response(array(
				"errorMess" => $exception->getMessage(),
				"errorCode" => $exception->getCode(),
				"file" => $exception->getFile(),
				"line" => $exception->getLine(),
			),
				$exception->getStatusCode());
		}

		if ($request->wantsJson() || $request->isJson()) {

			$status = Response::HTTP_BAD_REQUEST;

			if ($exception instanceof ValidationException && $exception->errors()) {
				$response = $exception->errors();
				$status = $exception->status;
			}else{
				$response = [
					'errorMess' => 'Sorry, something went wrong.',
					'errorCode' => $exception->getCode()
				];
				if (config('app.debug')) {
					$response = array_merge($response,[
						'exception' => get_class($exception),
						'message' => $exception->getMessage(),
						'trace' => $exception->getTrace(),
					]);
				}

				if ($this->isHttpException($exception)) {
					$status = $exception->getStatusCode();
				}
			}

			return response()->json($response, $status);
		}

		return parent::render($request, $exception);
    }
}
