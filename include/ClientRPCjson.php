<?php
/**
 * Class ClientRPCjson
 * @author Lukasz Narloch lukassz111@gmail.com
 * @copyright Lukasz Narloch lukassz111@gmail.com
 * @license DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 * @license http://www.wtfpl.net/about/
 * @since 1.0
 */
class ClientRPCjson
{
    private $url = null;
    public function __construct($_url)
    {
        $this->url = $_url;
    }
    public function __call($name, $arguments)
    {
        $data = array();
        $data['type'] = 'function';
        $data['method'] = $name;
        $data['args'] = $arguments;
        return $this->send($data);
    }

    private function send($data)
    {
        $opt = array(
            'http' => array(
                // http://www.php.net/manual/en/context.http.php
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($opt);
        $response = file_get_contents($this->url,false,$context);

        return json_decode($response);
    }
}
?>