<?php
/**
 * User: Paris Theofanidis
 * Date: 21/05/16
 * Time: 22:22
 */
namespace ptheofan\behaviors\mysql;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class SqlToTimestampBehavior
 * @package common\behaviors
 */
class SqlToTimestampBehavior extends Behavior
{
    /**
     * Attribute names that should be converted
     * @var array
     */
    public $attributes = [];

    /**
     * The format to convert to and from
     * @var string
     */
    public $format = 'Y-m-d H:i:s';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'toSql',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'toSql',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'toSql',

            ActiveRecord::EVENT_AFTER_VALIDATE => 'toTs',
            ActiveRecord::EVENT_AFTER_INSERT => 'toTs',
            ActiveRecord::EVENT_AFTER_UPDATE => 'toTs',
            ActiveRecord::EVENT_AFTER_FIND => 'toTs',
        ];
    }

    /**
     * @param $event
     */
    public function toSql($event)
    {
        $m = $this->owner;
        foreach ($this->attributes as $attribute) {
            if (!empty($m->{$attribute})) {
                $m->{$attribute} = date($this->format, $m->{$attribute});
            }
        }
    }

    /**
     * @param $event
     */
    public function toTs($event)
    {
        $m = $this->owner;
        foreach ($this->attributes as $attribute) {
            if (!empty($m->{$attribute})) {
                $m->{$attribute} = strtotime($m->{$attribute});
            }
        }
    }
}