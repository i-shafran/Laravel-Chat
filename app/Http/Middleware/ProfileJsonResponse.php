<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class ProfileJsonResponse
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		
		if (
			$response instanceof JsonResponse &&
			app()->bound('debugbar') &&
			app('debugbar')->isEnabled()
			&& !empty($response->getData())
		) {
			$arRes = array();
			$data = app('debugbar')->getData();
			if(!env("DEBUGBAR_GET_TRACE")){
				foreach($data["queries"]["statements"] as &$value){
					unset($value["backtrace"]);
					unset($value["stmt_id"]);
					unset($value["type"]);
					unset($value["params"]);
					unset($value["bindings"]);
					unset($value["hints"]);
					unset($value["connection"]);
				}
				unset($value);
			}
			$arRes["sql"] = $data["queries"]["statements"];
			$arRes["auth"] = $data["auth"];
			$arRes["memory"] = $data["memory"];
			$arRes["time"] = $data["time"]["duration_str"];
			$arRes["sql_time"] = $data["queries"]["accumulated_duration_str"];

			
			$response->setData($response->getData(true) + [
					'_debugbar' => $arRes,
				]);
		}
		
		/* Статистика запросов */
		if(env("DEBUG_LOG_ENABLED")) {
			// Request Data
			$data = $request->all();
			$dataJson = json_encode($data);
			$dataStr = "";
			foreach ($data as $key => $value) {
				if (!is_array($value)) {
					$dataStr = $dataStr . "$key => $value; ";
				} else {
					$dataStr = $dataStr . "$key => " . json_encode($value) . "; ";
				}
			}

			$arRes = array(
				"route"        => $request->path(),
				"method"       => $request->method(),
				"time"         => round(microtime(true) - LARAVEL_START, 2),
				"memory"       => round(memory_get_usage(true) / 1024 / 1024, 2),
				"request"      => $dataStr,
				"url"          => $request->url(),
				"request_json" => $dataJson
			);
			
			// Запись в редис
			try {
				$redis = Redis::connection('default');
				$list = "Logs:API Request";
				$redis->rpush($list, array(json_encode($arRes)));
			} catch (\Exception $exception){
				// В случае Exception ничего не делаем
			}
		}

		return $response;
	}
}