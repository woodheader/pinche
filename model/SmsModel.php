<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/21
 * Time: 22:51
 */
require_once('../core/ActiveRecord.php');
class Sms extends ActiveRecord
{
    private $id;
    private $carTel;
    private $code;
    private $expireTime;
    private $status;
    private $createTime;
    private $updateTime;

    const STATUS_INVALID = 0;
    const STATUS_VALID = 1;

    /**
     * 表名称
     * @return string
     */
    public function tablename()
    {
        return $this->table('sms');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`car_tel` varchar(100) NOT NULL DEFAULT '' COMMENT '车主电话',
`code` varchar(20) NOT NULL DEFAULT '' COMMENT '验证码',
`expire_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
`status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态;0:无效;1:有效',
`create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
`update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
EOF;
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
    public function getId()
    {
        return $this->id;
    }

    public function getCarTel() {
        return $this->carTel;
    }

    public function setCarTel($carTel) {
        $this->carTel = $carTel;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
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

    /**
     * @return mixed
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }

    /**
     * 数据查询
     *
     * @return array
     */
    public function getSmsByConditions($carTel) {
        if (empty($carTel)) {
            return [];
        }
        if (!empty($carTel)) {
            $this->addWhere(['car_tel' => $carTel]);
        }
        $this->addWhere(['status' => Sms::STATUS_VALID]);
        $this->orderBy('update_time desc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }
}