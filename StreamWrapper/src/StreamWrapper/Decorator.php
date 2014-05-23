<?php
namespace StreamWrapper;

class Decorator
{

    private $wrapper;

    public function __construct ()
    {
        echo "CREATED" . PHP_EOL;
        $this->wrapper = new Wrapper();
    }

    public function __call ($method, $arguments)
    {
        if (! isset($this->wrapper)) {
            echo "RE-CREATED" . PHP_EOL;
            $this->wrapper = new Wrapper();
        }
        $_arguments = array();
        foreach ($arguments as $k => $v) {
            $_arguments[$k] = var_export($v, true);
        }
        $e = new \Exception("test");
        echo '#  ' . $method;
        echo '(' . implode(', ', $_arguments) . ')' . PHP_EOL;
        echo $e->getTraceAsString() . PHP_EOL . PHP_EOL;
        if (method_exists($this->wrapper, $method)) {
            return call_user_func_array(array(
                $this->wrapper,
                $method
            ), $arguments);
        }
        die(get_class($this->wrapper) . ' does not implement ' . $method . '()!');
    }
}
