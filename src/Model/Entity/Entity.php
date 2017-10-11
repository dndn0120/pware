<?php
namespace PriWare\Model\Entity;

abstract class Entity
{
    protected static $date_properties = [];
    
    public function __construct($parameters=null)
    {
        if (is_null($parameters)) {
            // parameter 입력 안됨
            // PDO fetch 등으로 시작된 것으로 보고 이미 입력된 property들로 초기화 시도
            $parameters = get_object_vars($this);
        }
        // hydrate
        foreach ($parameters as $key => $val) {
            if (property_exists(static::class, $key)) {
                if (in_array($key, static::$date_properties)) {
                    // date properties should be an implementation of DateTimeInterface
                    if (is_string($val)) {
                        $this->{$key} = new \DateTimeImmutable($val);
                    } elseif (is_object($val)) {
                        if ($val instanceof \DateTimeInterface) {
                            $this->{$key} = $val;
                        } else {
                            throw new \Exception("{$key} : date properties should be an implementation of DateTimeInterface.");
                        }
                    }
                } else {
                    // consider as plain type
                    $this->{$key} = $val;
                }
            }
        }
    }
}
