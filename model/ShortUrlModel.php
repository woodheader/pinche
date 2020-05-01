<?php
/**
 * Created by PhpStorm.
 * User: lishaojie
 * Date: 2020/4/26
 * Time: 13:04
 */
require_once('../core/ActiveRecord.php');
class ShortUrlModel extends ActiveRecord
{

    private $id;
    private $url;
    private $shortUrl;
    private $visitCount;
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
        return $this->table('short_url');
    }

    /**
     * 表结构
     * @return string
     */
    public function tableStructure()
    {
        return <<<EOF
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`url` varchar(1000) NOT NULL DEFAULT '' COMMENT '原始url',
`short_url` varchar(20) NOT NULL DEFAULT '' COMMENT '短链接',
`visit_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
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

    /**
     * @param mixed $id
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $visitCount
     */
    public function setVisitCount($visitCount)
    {
        $this->visitCount = $visitCount;
    }

    /**
     * @return mixed
     */
    public function getVisitCount()
    {
        return $this->visitCount;
    }

    /**
     * @param mixed $id
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return mixed
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
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
     * 数据查询
     *
     * @return array
     */
    public function getUrlByConditions($shortUrl = '') {
        if (empty($shortUrl)) {
            return [];
        }
        if (!empty($shortUrl)) {
            $this->addWhere(['short_url' => $shortUrl]);
        }
        $this->addWhere(['status' => ShortUrlModel::STATUS_VALID]);
        $this->orderBy('update_time desc');
        $result = $this->one();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    /**
     * 生成并返回URL
     */
    public function generateUrl($longUrl = '') {
        if (empty($longUrl)) {
            return '';
        }
        $shortUrl = shorturl($longUrl)[0];
        // 检查短链接是否存在
        $data = $this->getUrlByConditions($shortUrl);
        if (!empty($data)) {
            return $data['short_url'];
        }

        $formData = [
            'url' => $longUrl,
            'short_url' => $shortUrl,
            'expire_time' => getAnyDateTime('+1 year'),
            'status' => ShortUrlModel::STATUS_VALID,
            'create_time' => getLocalDateTime(),
            'update_time' => getLocalDateTime(),
        ];
        $short = new ShortUrlModel();
        $short->load($formData);
        $short->save();
        return $shortUrl;
    }

}