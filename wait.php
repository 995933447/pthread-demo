<?php
 
class Task extends Thread
{
    public $flag = 1;
 
    public function run()
    {
        $this->synchronized(function () {
            //标识不为1就一直等待
            if ($this->flag !== 1) {
                $this->wait();
            }
 
            for ($i = 1; $i <= 10; $i++) {
 
                echo "flag : {$this->flag} i : {$i} \n";
 
                if ($this->flag === 1) {
                    //设置标识
                    $this->flag = 2;
                    //发送唤醒通知，然后让当前线程等待
                    //注意，notify()与wait()顺序不要搞错了，不然会一直阻塞
                    $this->notify();
                    $this->wait();
                    //当线程调用wait()的时候，调用wait()的线程将会自动释放同步锁
                    // echo '我被锁住了';
                    // die;
                }
            }
 
            //我们在这里再次调用notify()
            //因为在最后一次输出flag : 2 i : 20时，当前线程的i已经变成11了，跳出了for循环，
            //但另一个线程则一直阻塞在wait()那里，程序无法结束，所以需要notify()再次唤醒一次
            $this->notify();
        });
    }
}
 
$t = new Task();
$t->start();
 
$t->synchronized(function ($obj) {
    //标识不为2就一直等待
    if ($obj->flag !== 2) {
        $obj->wait();
    }
 
    for ($i = 11; $i <= 20; $i++) {
 
        echo "flag : {$obj->flag} i : {$i} \n";
 
        if ($obj->flag === 2) {
            $obj->flag = 1;
            $obj->notify();
            $obj->wait();
            //当线程调用wait()的时候，调用wait()的线程将会自动释放同步锁
            // echo '我被锁住了';
            // die;
        }
    }
}, $t);
 
//把创建的线程加入主线程中，让主线程等待子线程运行结束
$t->join();