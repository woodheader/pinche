<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2019/2/20
 * Time: 23:25
 */
class MyRedis{
    private static $handler;

    private static function handler(){
        if(!self::$handler){
            self::$handler = new Redis();
            self::$handler->connect('172.17.0.10','6379');
        }
        return self::$handler;
    }

    public static function get($key){
        $value = self::handler()->get($key);
        $valueReal = @unserialize($value);
        if(is_object($valueReal) || is_array($valueReal)){
            return $valueReal;
        }
        return $value;
    }

    public static function set($key,$value, $expire = 0){
        if(is_object($value) || is_array($value)){
            $value = serialize($value);
        }

        $res = self::handler()->set($key, $value);
        self::handler()->expireAt($key, $expire);
        return $res;
    }

    public static function hasKey($key) {
        return self::handler()->exists($key);
    }

    public static function delete($key) {
        return self::handler()->delete($key);
    }

}