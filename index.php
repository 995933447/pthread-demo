<?php 
class MyWorker extends Worker{
	private $name;
	public $id = 3;
	public function __construct($name)
	{
   		$this->name = $name;
	}
	public function getId()
	{
		return $this->id;
	}

	public function run()
	{   echo 'woker run~  ';
		$this->id = $this->name;
	}

}

class Task extends Thread
{
	public function run()
	{
		//测试证明thread可以通过worker属性访问并修改其属性，修改后的上下文对复用的线程任然有效
		echo 'init：'.$this->worker->getId().' &++= ';
		echo ' '.(++$this->worker->id).'! ';
	}
}

$MyWorker = new MyWorker(1);
$threadA = new Task();
$threadB = new Task();
$MyWorker->stack($threadA);
$MyWorker->stack($threadB);
$MyWorker->start();
//循环的清理完成任务的线程，会阻塞主线程，直到栈中任务都执行完毕,释放资源
while ($MyWorker->collect());
//在执行完已入栈对象之后，关闭这个 Worker 对象
$MyWorker->shutdown();
 ?>
