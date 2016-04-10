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
/* @var $items array */

echo "<?php\n";
?>

return [
<?php foreach ($items as $item): ?>
    [
<?php foreach ($item as $name => $value): ?>        '<?= $name ?>' => <?= $value ?>,
<?php endforeach; ?>
    ],
<?php endforeach; ?>
];