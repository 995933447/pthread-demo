<?php 
//pthreads v3引入了Threaded对象自动不变性的概念
//如果成员被设置成Threaded对象，那么它将不能被再次改写
//当然，这主要还是为了性能优化，但有时我们又需要改写成员，那么就需要继承自Volatile类了,Volatile类并非继承threaded类，网上有错误，在volatile2.php得到实践
class Task extends Thread
{
    private $data;
    private $result;
    public $id;
 
    public function __construct()
    {
        $this->data = 'abc';
        var_dump($this->data);
 
        //成员设置成标量，是可以再次被改写的
        $this->data = 'def';
        var_dump($this->data);
 
        //这里给data设置为数组
        $this->data = [1, 2, 3];
        //threaded类里不能使用array[key]访问数据
        var_dump($this->result[0]);
        var_dump($this->data);
 
        //这个时候再给data赋值时，就会报错了
        //该成员就不能再次被改写了
        $this->data = [4, 5, 6];
        var_dump($this->data);
 
        //当然，我们可以显式的强制转换，让Threaded帮我自动转成Volatile对象
        $this->result = (array)[1, 2, 3];
        //打印出来是数组
        var_dump($this->result);
        //threaded类里不能使array[]=value的方式赋值
        $this->result = (array)[4, 5, 6];
        var_dump($this->result[0]);
        //成员赋值成功
        var_dump($this->result);
        $this->id=1;
    }
    public function run()
    {
    	$this->id++;
    	echo $this->id;
    }
}
 
(new Task())->start();





 ?>