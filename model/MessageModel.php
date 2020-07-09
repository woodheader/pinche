<?php
require_once('../core/ActiveRecord.php');
class MessageModel extends ActiveRecord
{

    private $id;
    private $formId;
    private $dripId;
    private $channel;
    private $isDeleted;
    private $answers;
    private $ip;
    private $carTime;
    private $goto;
    private $carLine;
    private $carSeatnum;
    private $carPrice;
    private $carTel;
    private $carWechatImg;
    private $carLicensePlate;
    private $status;
    private $createTime;
    private $updateTime;

    const DELETED_NO = 0;
    const DELETED_YES = 1;

    const STATUS_INVALID = 0;
    const STATUS_VALID = 1;
    
    const CHANNEL_MIYUN = 1;
    const CHANNEL_HEBEI = 2;

    const CHANNEL_MIYUN_CN = 'miyun';
    const CHANNEL_HEBEI_CN = 'hebei';

    /**
     * 表名称
     * @return string
     */
    public function tablename()
    {
        return $this->table('message');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`form_id` varchar(100) NOT NULL DEFAULT '' COMMENT '水滴表单ID',
`drip_id` varchar(20) NOT NULL DEFAULT '' COMMENT '水滴表单提交ID',
`channel` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '通道;1:密云;2:河北',
`is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除;0:未删除;1:已删除',
`answers` text NOT NULL COMMENT '水滴表单提交内容(JSON)',
`ip` varchar(50) NOT NULL DEFAULT '' COMMENT '提交者IP地址',
`car_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发车时间',
`goto` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '方向;1:去北京;2:去密云;3:去石家庄;4:去邯郸',
`car_line` varchar(500) NOT NULL DEFAULT '' COMMENT '行驶路线',
`car_seatnum` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '座位数',
`car_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单价',
`car_tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系方式',
`car_wechat_img` varchar(500) NOT NULL DEFAULT '' COMMENT '车主微信二维码',
`car_license_plate` varchar(50) NOT NULL DEFAULT '' COMMENT '车辆信息',
`status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态;0:无效;1:有效',
`create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
`update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
EOF;
    }

    public function getCarTime() {
        return $this->carTime;
    }

    public function setCarTime($carTime) {
        $this->carTime = $carTime;
    }

    public function getGoto() {
        return $this->goto;
    }

    public function setGoto($goto) {
        $this->goto = $goto;
    }

    public function getCarLine() {
        return $this->carLine;
    }

    public function setCarLine($carLine) {
        $this->carLine = $carLine;
    }

    public function getCarSeatnum() {
        return $this->carSeatnum;
    }

    public function setCarSeatnum($carSeatnum) {
        $this->carSeatnum = $carSeatnum;
    }

    public function getCarPrice() {
        return $this->carPrice;
    }

    public function setCarPrice($carPrice) {
        $this->carPrice = $carPrice;
    }

    public function getCarTel() {
        return $this->carTel;
    }

    public function setCarTel($carTel) {
        $this->carTel = $carTel;
    }

    public function getCarWechatImg() {
        return $this->carWechatImg;
    }

    public function setCarWechatImg($carWechatImg) {
        $this->carWechatImg = $carWechatImg;
    }

    public function getCarLicensePlate() {
        return $this->carLicensePlate;
    }

    public function setCarLicensePlate($carLicensePlate) {
        $this->carLicensePlate = $carLicensePlate;
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
    public function getDripId()
    {
        return $this->dripId;
    }

    /**
     * @param mixed $sendfrom
     */
    public function setDripId($dripId)
    {
        $this->dripId = $dripId;
    }
    
    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $sendfrom
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }
    
    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $sendfrom
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }
    
    /**
     * @return mixed
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param mixed $sendfrom
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $message
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $type
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
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

    public function getMessageById($id = 0) {
        if (empty($id)) {
            return [];
        }
        $this->addWhere(['id' => $id]);
        $this->addWhere(['status' => MessageModel::STATUS_VALID]);
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    /**
     * 数据查询
     *
     * @return array
     */
    public function getMessageByConditions($carTime, $goto, $carTel) {
        if (empty($carTime) && empty($goto) && empty($carTel)) {
            return [];
        }
        if (!empty($carTime)) {
            $this->addWhere(['car_time' => $carTime]);
        }
        if (!empty($goto)) {
            $this->addWhere(['goto' => $goto]);
        }
        if (!empty($carTel)) {
            $this->addWhere(['car_tel' => $carTel]);
        }
        $this->addWhere(['status' => MessageModel::STATUS_VALID]);
        $this->orderBy('car_time desc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    /**
     * 更新某条消息
     *
     * @param $oldMsgObj
     * @return bool|mysqli_result
     */
    public function updateMessage($oldMsgObj) {
        $id = $oldMsgObj['id'];
        $oldMsg = $oldMsgObj['MessageModel'];
        if (empty($id)) {
            return false;
        }
        $msg = new MessageModel();
        $msg->setSendfrom($this->getSendfrom());
        $msg->setUpdateTime(date('Y-m-d H:i:s'));
        // 对比同一个人数据库中的和最新的消息的相似度（不必关心类型）
        $similar = compareIsSimilarWords($oldMsg, $this->getMessage());
        writeCompareLog($oldMsg, $this->getMessage(), $similar);
        $newMsg = '【`' . $this->getMessage() . '`】';
        if ($similar >= 80) {
            $msg->setMessage($this->getMessage());
        } else if ($similar <= 25) {
            $msg->setMessage($oldMsg . $newMsg);
        } else {
            // 相似度介于 20 - 80 之间的，不予处理
            return true;
        }
        // 如果相似度数值过低，会造成新消息一直往后拼接，然后相似度就会一直下降，造成恶性循环
        // 这种情况下，要增加新消息在旧消息中的匹配，若匹配到了，不予处理，否则继续追加
        if (strpos($oldMsg, $newMsg) !== false) {
            return true;
        }
        $msg->addWhere(['id' => $id]);
        // 清理缓存
        MyRedis::delete(self::REDIS_KEY_MSG_LIST);
        return $msg->update();
    }

    /**
     * 消息中是否包含【车满】关键词
     * @return bool
     */
    public function isContainFullWords() {
        foreach (self::CAR_FULL_WORD_LIST as $fullWord) {
            if (strpos($this->getMessage(), $fullWord) !== false
                && $this->getType() == MessageModel::TYPE_FIND_PERSON) {
                return true;
            }
        }
        return false;
    }

    /**
     * 显示当前所有消息列表
     * @return array
     */
    public function getMessageList($goto = '', $carTime = '', $updateTime = '',$channel = '') {
        //$this->addWhere(['like', 'create_time', date('Y-m-d')]);
        if (!empty($carTime)) {
            $this->addWhere(['between', 'car_time', date('Y-m-d H:i:s', strtotime($carTime) - 300), date('Y-m-d H:i:s', strtotime($carTime) + 86400*10)]);
        }
        if (!empty($goto)) {
            $this->addWhere(['in', 'goto', $goto]);
        }
        if (!empty($updateTime)) {
            $this->addWhere(['between', 'update_time', $updateTime, date('Y-m-d H:i:s')]);
        }
        if (!empty($channel)) {
            $this->addWhere(['channel' => $channel]);
        }
        $this->addWhere(['status' => self::STATUS_VALID]);
        $this->addWhere(['is_deleted' => self::DELETED_NO]);
        $this->orderBy('car_time asc, car_seatnum desc');
        return $this->all();
    }

}