<?php

/**
 * Class ServerRPCjson
 * @author Lukasz Narloch lukassz111@gmail.com
 * @copyright Lukasz Narloch lukassz111@gmail.com
 * @license WTFPL
 * @since 1.0
 */
    class ServerRPCjson
    {
        private $object = null;
        public function __call($name, $arguments)
        {
            $countArgs = count($arguments);
            switch ($countArgs)
            {
                /**
                 * If you need to use more parametr
                 * You can add case to this switch.
                 **/
                case 0://For voids
                    return $this->object->$name();
                    break;
                case 1://For one parametrs
                    return $this->object->$name($arguments[0]);
                    break;
                case 2://etc
                    return $this->object->$name($arguments[0],$arguments[1]);
                    break;

                default:
                    throw new Exception("Add a case to switch with more arguments in ".basename(__FILE__));
                    break;
            }
        }

        public function __construct($_object)
        {
            file_put_contents('log.txt', "Start".PHP_EOL, FILE_APPEND);
            $this->object = $_object;
            header("Content-Type: text/plain");
            $post = file_get_contents('php://input');
            if($post=="")
            {
                return;
            }
            file_put_contents('log.txt', $post.PHP_EOL, FILE_APPEND);
            $data = json_decode($post);
            switch ($data->type)
            {
                case "function":
                    file_put_contents('log.txt', "FUNCTION:".PHP_EOL, FILE_APPEND);
                    $arguments = $data->args;
                    $method = $data->method;
                    try
                    {
                        $result = $this->__call($method, $arguments);
                        echo json_encode($result);
                    }
                    catch (Exception $e)
                    {
                        echo json_encode("RPC Error: ".$e->getMessage());
                    }
                    break;
                default:
                    throw new Exception("Undefined type of RPC");
                    break;
            }
        }
    }
?>