<?php
 
//Volatile类允许其成员可更改
class Task extends Volatile
{
    private $data;
 
    public function __construct()
    {
        //继承自Volatile类后，我们的成员在设置成Threaded对象后，仍可改写
        $this->data = [
            'a' => 123,
            'b' => 456,
            'c' => 789,
        ];
        var_dump($this->data);
 
        //这里成员数据成功被改写
        $this->data = [
            'a' => 'aaa',
            'b' => 'bbb',
            'c' => 'ccc',
        ];
        var_dump($this->data);
 
        //由于Threaded对象实现了ArrayAccess接口，我们可以像访问数组一样，访问Volatile对象
        // echo $this->data['a'], "\t", $this->data['b'], "\t", $this->data['c'], "\n";
 
        // foreach ($this->data as $item) {
        //     echo $item, "\n";
        // }
    }
    public function run(){
        var_dump(new Volatile()); 
    }
}

$task = new Task();
//没有start方法说明并非继承volatile类
// $task->start();