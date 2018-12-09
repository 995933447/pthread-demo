<?php 

class Count extends Thread
{
	public $cnt = 0;

	public function run()
	{
		$this->add();
	}

	public function add()
    {
    	$this->synchronized(function()
    	{	echo 'this is mylock~ ';
    		//对成员进行加1操作
    		for ($i = 0; $i < 100000; $i++) {
    		    ++$this->cnt;
    		}

    	});
        
    }

}

$task=new Count();
$task->start();
$task->add();
$task->join();
var_dump($task->cnt);








 ?>