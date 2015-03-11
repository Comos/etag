<?php
/**
 * User: bigbigant
 * Date: Mar 11 2015
 */
namespace Etag\Util;

class Config
{

    /**
     *
     * @var array
     */
    protected $data;

    /**
     *
     * @param array $data            
     * @throws InvalidArgumentException
     */
    public static function fromArray($data)
    {
        if (! is_array($data) && ! $data instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('the argument must be array or ArrayAccess');
        }
        return new self($data);
    }

    /**
     *
     * @param array $data            
     */
    protected function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *
     * @param mix $key            
     * @param string $default            
     * @return string|null
     */
    public function str($key, $default = null)
    {
        if (! \key_exists($key, $this->data)) {
            return $default;
        }
        
        return strval($this->data[$key]);
    }
    /**
     * get string field value in restric mode.
     * @param $mix $key
     * @throws Etag\Util\ConfigException
     */
    public function rstr($key)
    {
    	if (is_null($this->str($key))) {
    		throw new ConfigException('miss required field: '.$key);
    	}
    }
}