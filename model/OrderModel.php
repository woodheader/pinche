<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/25
 * Time: 12:23
 */
require_once('../core/ActiveRecord.php');
class OrderModel extends ActiveRecord
{
    private $id;
    private $msgId;
    private $orderTel;
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
        return $this->table('order');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`msg_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
`order_tel` varchar(100) NOT NULL DEFAULT '' COMMENT '预订电话',
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

    /**
     * @param mixed $msgId
     */
    public function setMsgId($msgId)
    {
        $this->msgId = $msgId;
    }

    /**
     * @return mixed
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

    /**
     * @param mixed $orderTel
     */
    public function setOrderTel($orderTel)
    {
        $this->orderTel = $orderTel;
    }

    /**
     * @return mixed
     */
    public function getOrderTel()
    {
        return $this->orderTel;
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

    public function getOrderInfo($msgId = '', $orderTel = '') {
        if (empty($msgId) || empty($orderTel)) {
            return [];
        }
        if (!empty($msgId)) {
            $this->addWhere(['msg_id' => $msgId]);
        }
        if (!empty($orderTel)) {
            $this->addWhere(['order_tel' => $orderTel]);
        }
        $this->addWhere(['status' => SmsModel::STATUS_VALID]);
        $this->orderBy('update_time desc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

}