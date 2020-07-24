<?php

namespace App\Jobs;

use App\Events\ServerLog;
use App\Models\LogRedis;

class ErrorLogRedis
{
	/**
	 * Запись ошибок в лог (logs_redis)
	 *
	 * @param \Exception $exception
	 * @param $data
	 */
	public static function errorLog(\Exception $exception, $data = [])
	{
		$status_code = NULL;

		if(method_exists($exception, "getStatusCode")){
			$status_code = $exception->getStatusCode();
		}

		// Data
		$dataStr = "";
		foreach ($data as $key => $value){
			if(!is_array($value) and !is_object($value)){
				$dataStr = $dataStr."$key => $value; ";
			} else {
				$dataStr = $dataStr."$key => ".json_encode((array) $value)."; ";
			}
		}

		// Запись в базу лога
		$log = new LogRedis();
		if(isset($data["job_name"])){
			$log->job_name = $data["job_name"];
		}
		if(isset($data["to_email"])){
			$log->email = $data["to_email"];
		}

		$log->message = $exception->getMessage();
		$log->exception = get_class($exception);
		$log->file = $exception->getFile();
		$log->line = $exception->getLine();
		$log->code = $exception->getCode();
		$log->status_code = $status_code;
		$log->data = $dataStr;
		$log->trace = json_encode($exception->getTrace());
		$log->save();

		event(new ServerLog($log));
	}
}