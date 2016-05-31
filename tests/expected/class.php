<?php

namespace tests\runtime;

use yii\test\ActiveFixture;

class PostFixture extends ActiveFixture
{
    public $modelClass = 'elisdn\gii\fixture\tests\Post';
    public $dataFile = '@tests/runtime/data/post.php';
}