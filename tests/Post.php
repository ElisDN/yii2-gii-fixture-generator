<?php

namespace elisdn\gii\fixture\tests;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public static function tableName()
    {
        return 'post';
    }
} 