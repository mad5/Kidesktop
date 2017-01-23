<?php
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;

// composer autoload
include __DIR__ . '/socketio/vendor/autoload.php';
include __DIR__ . '/socketio/src/autoload.php';

$io = new SocketIO(2020);
$io->on('connection', function(\PHPSocketIO\Socket $socket){

	//$socket->join("testraum");
    
    $socket->on('action', function ($data)use($socket){
    	//$socket->to("testraum")->emit('action', $data);
    	print_r($data);
    	
    	if($data["action"]=="initrun") {
    		//exec("");
    	}
    	
    	if($data["action"]=="openurl") {
    		$url = $data["url"];
    		$E = "chromium-browser --app=".escapeshellarg($url);
    		exec($E." > /dev/null &");
    	}
    	
    	if($data["action"]=="runapp") {
    		exec($data["app"]." > /dev/null &");
    	}
    	
    	$socket->emit('action', $data);
    });
   
});

$web = new WebServer('http://0.0.0.0:2022');
$web->addRoot('localhost', __DIR__ . '/public');

Worker::runAll();
