<?php

class HttpServer
{
	public static $instance;

	public $http;
	public static $get;
	public static $post;
	public static $header;
	public static $server;
	private $application;

	public function __construct() {
		$http = new swoole_http_server("127.0.0.1", 9501);

		$http->set(
			array(
				'worker_num' => 8,
				'daemonize' => false,
	            'max_request' => 10000,
	            'dispatch_mode' => 3
			)
		);

		$http->on('WorkerStart' , array( $this , 'onWorkerStart'));

		$http->on('request', function ($request, $response) {
			// print_r( $request->header );
			// print_r( $request->server );
			// print_r( $request->get );
			// print_r( $request->post );
			if( isset($request->server) ) {
				HttpServer::$server = $request->server;
				var_dump( HttpServer::$server);
			}
			if( isset($request->header) ) {
				HttpServer::$header = $request->header;
			}
			if( isset($request->get) ) {
				HttpServer::$get = $request->get;
			}
			if( isset($request->post) ) {
				HttpServer::$post = $request->post;
			}

			$yaf_request = new Yaf_Request_Http( HttpServer::$server['request_uri']);

			ob_start();

		    $this->application->
		    getDispatcher()->setRequest($yaf_request)->getApplication()
		    ->bootstrap()->run();
		    $result = \ob_get_contents();
		    \ob_end_clean();
		    $response->end($result);
		});

		$http->start();
	}

	public function onWorkerStart() {
		// require dirname(__DIR__). "/index.php";

		define('APPLICATION_PATH', dirname(__DIR__));

		$this->application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
	}

	public static function getInstance() {
		if (!self::$instance) {
            self::$instance = new HttpServer;
        }
        return self::$instance;
	}
}

HttpServer::getInstance();