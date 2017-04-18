<?php
    //Sample Class with function on RPC Server
    class Func
    {
        public $var;
        public function setVar($v)
        {
            $this->var = $v;
        }
        public function getVar()
        {
            return $this->var;
        }
        public function foo()
        {
            return "HelloWorld";
        }
        public function post()
        {
            return file_get_contents('php://input');
        }
        public function arr()
        {
            return array('var1','var2');
        }
    }
?>