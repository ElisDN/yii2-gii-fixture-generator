<?php
/**
 * This file is part of the elisdn/yii2-gii-fixture-generator library
 *
 * @copyright Copyright (c) Dmitriy Yeliseyev <mail@elisdn.ru>
 * @license https://github.com/ElisDN/yii2-gii-fixture-generator/blob/master/LICENSE.md
 * @link https://github.com/ElisDN/yii2-gii-fixture-generator
 */

/* @var $this yii\web\View */
/* @var $generator elisdn\gii\fixture\Generator */

echo "<?php\n";
?>

namespace <?= $generator->fixtureNs ?>;

use yii\test\ActiveFixture;

class <?= $generator->getFixtureClassName() ?> extends ActiveFixture
{
    public $modelClass = '<?= $generator->modelClass ?>';
    public $dataFile = '<?= $generator->dataPath . '/' . $generator->getDataFileName() ?>';
}