<?php
 
//之所以要创建一个Id线程类，主要是为了给work取个不同的ID，方便查看，哪些task线程属于哪个work中
class Id extends Thread
{
    private $id;
 
    public function getId()
    {
        //防止出现id混乱，这里使用同步操作
        $this->synchronized(function () {
            ++$this->id;
        });
        return $this->id;
    }
}
 
class Work extends Worker
{
    private $id;
 
    public function __construct(Id $obj)
    {
        $this->id = $obj->getId();
    }
 
    public function getId()
    {
        return $this->id;
    }
}
 
class Task extends Thread
{
    private $num = 0;
 
    public function __construct($num)
    {
        $this->num = $num;
    }
 
    //计算累加和
    public function run()
    {   
        $total = 0;
        for ($i = 0; $i < $this->num; $i++) {
            $total += $i;
        }
        echo "num: {$this->num} work id : {$this->worker->getId()} task : {$total} \n";
    }
}
 
//创建pool，可容纳3个work对象
$pool = new Pool(3, 'Work', [new Id()]);
 
//循环的把20个task线程提交到pool中的work对象上运行
for ($i = 1; $i <= 20; $i++) {
    $pool->submit(new Task($i));
}
 
//循环的清理任务，会阻塞主线程，直到任务都执行完毕
while ($pool->collect()) ;
 
//关闭pool
$pool->shutdown();