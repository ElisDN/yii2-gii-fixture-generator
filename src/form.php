<?php
/**
 * This file is part of the elisdn/yii2-gii-fixture-generator library
 *
 * @copyright Copyright (c) Dmitriy Yeliseyev <mail@elisdn.ru>
 * @license https://github.com/ElisDN/yii2-gii-fixture-generator/blob/master/LICENSE.md
 * @link https://github.com/ElisDN/yii2-gii-fixture-generator
 */

use elisdn\gii\fixture\GeneratorAsset;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator elisdn\gii\fixture\Generator */

GeneratorAsset::register($this);

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'fixtureClass');
echo $form->field($generator, 'fixtureNs');
echo $form->field($generator, 'dataFile');
echo $form->field($generator, 'dataPath');
echo $form->field($generator, 'grabData')->checkbox();
echo $form->field($generator, 'rowsData');
