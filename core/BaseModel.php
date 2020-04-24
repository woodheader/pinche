<?php

/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2017/10/20
 * Time: 23:14
 */
abstract class BaseModel
{
    private $message = '';
    private $messageList = [];
    private $safeModel = true;

    abstract public function tablename();
    abstract public function tableStructure();

    /**
     * 加载前端输入数据
     *
     * @param array $formData
     * @return $this|null
     */
    public function load($formData = []) {
        if (empty($formData)) {
            return null;
        }
        foreach ($formData as $key => $val) {
            $key = $this->formatKeyToHump($key);
            //echo $key.'<br/>';
            $func = 'set'.ucfirst($key);
            if (method_exists($this, $func)) {
                $this->$func($val);
            }
        }
        return $this;
    }

    /**
     * 格式化前端数据key为驼峰写法
     *
     * @param $key
     * @return string
     */
    public function formatKeyToHump($key) {
        $newKey = '';
        if (strpos($key, '_') !== false) {
            $keyArr = explode('_', $key);
            foreach ($keyArr as $i => $k) {
                $newKey .= $i == 0 ? $k : ucfirst($k);
            }
        } else {
            $newKey = $key;
        }
        return $newKey;
    }

    /**
     * 格式化数据key为下划线写法
     *
     * @param $key
     * @return string
     */
    public function formatKeyToLine($key) {
        $tempArray = array();
        for($i = 0;$i < strlen($key); $i++){
            $asciiCode = ord($key[$i]);
            if($asciiCode >= 65 && $asciiCode <= 90){
                if($i == 0){
                    $tempArray[] = chr($asciiCode + 32);
                }else{
                    $tempArray[] = '_' . chr($asciiCode + 32);
                }
            }else{
                $tempArray[] = $key[$i];
            }
        }
        return implode('',$tempArray);
    }

    /**
     * 获取模型属性
     *
     * @param array $typeList
     * @return array
     */
    private function attributes($typeList = [ReflectionProperty::IS_PUBLIC]) {
        $class = new ReflectionClass($this);
        $names = [];
        if (empty($typeList)) {
            return $names;
        }
        foreach ($typeList as $type) {
            foreach ($class->getProperties($type) as $property) {
                if (!$property->isStatic()) {
                    $names[] = $property->getName();
                }
            }
        }
        return $names;
    }

    /**
     * 返回模型数组格式
     *
     * @return array
     */
    public function toArray() {
        $data = [];
        $attributes = $this->attributes([ReflectionProperty::IS_PRIVATE, ReflectionProperty::IS_PUBLIC, ReflectionProperty::IS_PROTECTED]);
        foreach ($attributes as $property) {
            $funcName = 'get'.ucfirst($property);
            $data[$property] = $this->$funcName();
        }
        return $data;
    }

    /*
     * 驼峰转下划线
     */
    private function humpToLine($str){
        $str = preg_replace_callback('/([A-Z]{1})/',function ($matches){
            return '_'.strtolower($matches[0]);
        },$str);
        return $str;
    }

    /**
     * 生成数据库下划线形式的数据
     *
     * @return array
     */
    public function toDbArray() {
        $dataList = $this->toArray();
        $newDataList = [];
        foreach ($dataList as $key => $value) {
            if ($value instanceof BaseModel) {
                continue;
            }
            if ($value == null && strlen($value) <= 0) {
                continue;
            }
            $newDataList[$this->humpToLine($key)] = $value;
        }
        return $newDataList;
    }

    public function validateRule() {
        if (empty($this->tableStructure())) {
            throw new Exception('Table structure not found.');
        }
        // todo ... 1、检查子类 rule 方法定义的规则
        // 2、使用表结构检查用户输入内容是否合法
        $structureList = explode("\n", $this->tableStructure());
        foreach ($structureList as $item) {
            $item = strtolower($item);
            $itemList = explode(' ', $item);
            $fieldName = str_replace('`', '', $itemList[0]);
            $fieldType = $itemList[1];
            $fieldName = $this->formatKeyToHump($fieldName);
            $funcName = 'get'.ucfirst($fieldName);
            // 检查类型，只检查数字类型，字符串类型随意
            if (strpos($fieldType, 'int') !== false) {
                if (!empty($this->$funcName()) && !is_numeric($this->$funcName())) {
                    throw new Exception('Field ' . $fieldName . ' must be a numeric.');
                }
            }
            // 检查长度 - datetime、text 类型的除外
            if (strpos($fieldType, 'datetime') === false && strpos($fieldType, 'text') === false) {
                preg_match('/\((.*)\)/', $fieldType, $matches);
                if (strlen($this->$funcName()) > intval($matches[1])) {
                    throw new Exception('Field ' . $fieldName . ' length is too long.');
                }
            }
            // 补丁措施，针对没有设置默认值的字段，并且用户没有输入的，做默认值处理（排除主键字段）
            if (strpos($item, 'default') === false && empty($this->$funcName()) && strpos($item, 'auto_increment') === false) {
                $funcName = 'set'.ucfirst($fieldName);
                if (strpos($fieldType, 'int') !== false) {
                    $this->$funcName(0);
                } else {
                    $this->$funcName('');
                }
            }
            // echo $fieldName.'--'.$fieldType.'--'.$funcName.'----<br/>';
        }
        return true;
    }

    public function getFieldType ($field = '') {
        if (empty($this->tableStructure())) {
            throw new Exception('Table structure not found.');
        }
        if (empty($field)) {
            throw new Exception('Field name was empty.');
        }
        $structureList = explode("\n", $this->tableStructure());
        foreach ($structureList as $item) {
            $item = strtolower($item);
            $itemList = explode(' ', $item);
            $fieldName = str_replace('`', '', $itemList[0]);
            $fieldType = $itemList[1];
            if ($fieldName == $field) {
                if (strpos($fieldType, 'int') !== false) {
                    return 'int';
                } elseif (strpos($fieldType, 'datetime') !== false) {
                    return 'datetime';
                }
            }
        }
        return 'string';
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getMessage() {
        return $this->message;
    }

    public function addMessage($message) {
        $this->messageList[] = $message;
    }

    public function getFirstMessage() {
        return empty($this->messageList) ? '' : $this->messageList[0];
    }

    /**
     * @return bool
     */
    public function isSafeModel() {
        return $this->safeModel;
    }

    /**
     * @param bool $safeModel
     */
    public function setSafeModel($safeModel) {
        $this->safeModel = $safeModel;
    }

}