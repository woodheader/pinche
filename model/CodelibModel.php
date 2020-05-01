<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/5/2
 * Time: 00:39
 */
require_once('../core/ActiveRecord.php');
class CodelibModel extends ActiveRecord
{
    private $id;
    private $code;
    private $status;
    private $type;
    private $isUsed;
    private $createTime;
    private $updateTime;

    const TYPE_DRIVER = 1;
    const TYPE_PASSENGER = 2;

    const STATUS_INVALID = 0;
    const STATUS_VALID = 1;

    const USE_NO = 0;
    const USE_YES = 1;

    /**
     * 表名称
     * @return string
     */
    public function tablename()
    {
        return $this->table('codelib');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`code` varchar(20) NOT NULL DEFAULT '' COMMENT '编码',
`status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态;0:无效;1:有效',
`type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '编码类型:(1:车主发布红包编码;2:乘客预订红包编码)',
`is_used` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用;0:否;1:是',
`create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
`update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
EOF;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getisUsed()
    {
        return $this->isUsed;
    }

    /**
     * @param mixed $isUsed
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = $isUsed;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    public function getCodeInfo($code = '', $type = 0) {
        if (empty($code) || empty($type)) {
            return [];
        }
        if (!empty($code)) {
            $this->addWhere(['code' => $code]);
        }
        if (!empty($type)) {
            $this->addWhere(['type' => $type]);
        }
        $this->addWhere(['status' => self::STATUS_VALID]);
        $this->orderBy('update_time desc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public function getRandomCode($type = 0) {
        if (!empty($type)) {
            $this->addWhere(['type' => $type]);
        }
        $this->addWhere(['status' => self::STATUS_VALID]);
        $this->addWhere(['is_used' => self::USE_NO]);
        $this->orderBy('update_time asc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

}