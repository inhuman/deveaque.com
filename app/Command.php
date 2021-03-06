<?php

require_once 'app/pages/Page.php';

class Command {

    private $slim;
    private $command;

    public function __construct($slim, $command) {
        $this->slim = $slim;
        $this->command = $command;
    }

    public function execute() {
        $classFile = $_SERVER['DOCUMENT_ROOT'].'/../app/pages/'.$this->command[0].'.php';
        if(!file_exists($classFile)) {
            throw new InvalidArgumentException('Handler not found ('.$classFile.')');
        }
        require_once $classFile;
        $className = $this->command[0];
        $class = new $className($this->slim);
        switch(func_num_args()) {
            case 0:
                call_user_func(array($class, $this->command[1]));
                break;
            case 1:
                call_user_func(array($class, $this->command[1]), func_get_arg(0));
                break;
            case 2:
                call_user_func(array($class, $this->command[1]), func_get_arg(0), func_get_arg(1));
                break;
            case 3:
                call_user_func(array($class, $this->command[1]), func_get_arg(0), func_get_arg(1), func_get_arg(2));
                break;
            default:
                call_user_func(array($class, $this->command[1]), func_get_args());
                break;
        }
    }

    public function getCallback() {
        return array($this, 'execute');
    }
}
