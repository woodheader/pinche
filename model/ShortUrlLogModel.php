<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/26
 * Time: 17:14
 */
require_once('../core/ActiveRecord.php');
class ShortUrlLogModel extends ActiveRecord
{
    private $id;
    private $urlId;
    private $equ;
    private $sys;
    private $browser;
    private $ip;
    private $city;
    private $agent;
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
        return $this->table('short_url_log');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`url_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短链接表ID',
`equ` varchar(100) NOT NULL DEFAULT '' COMMENT '设备',
`sys` varchar(100) NOT NULL DEFAULT '' COMMENT '系统',
`browser` varchar(100) NOT NULL DEFAULT '' COMMENT '浏览器',
`ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'IP地址',
`city` varchar(100) NOT NULL DEFAULT '' COMMENT '城市',
`agent` varchar(300) NOT NULL DEFAULT '' COMMENT 'userAgent',
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
     * @param mixed $urlId
     */
    public function setUrlId($urlId)
    {
        $this->urlId = $urlId;
    }

    /**
     * @return mixed
     */
    public function getUrlId()
    {
        return $this->urlId;
    }


    /**
     * @param mixed $urlId
     */
    public function setEqu($equ)
    {
        $this->equ = $equ;
    }

    /**
     * @return mixed
     */
    public function getEqu()
    {
        return $this->equ;
    }

    /**
     * @param mixed $urlId
     */
    public function setSys($sys)
    {
        $this->sys = $sys;
    }

    /**
     * @return mixed
     */
    public function getSys()
    {
        return $this->sys;
    }

    /**
     * @param mixed $urlId
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param mixed $urlId
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $urlId
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $urlId
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
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
}