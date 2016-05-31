<?php
/**
 * This file is part of the elisdn/yii2-gii-fixture-generator library
 *
 * @copyright Copyright (c) Dmitriy Yeliseyev <mail@elisdn.ru>
 * @license https://github.com/ElisDN/yii2-gii-fixture-generator/blob/master/LICENSE.md
 * @link https://github.com/ElisDN/yii2-gii-fixture-generator
 */

namespace elisdn\gii\fixture;

use Yii;
use yii\db\ActiveRecord;
use yii\gii\CodeFile;

class Generator extends \yii\gii\Generator
{
    public $modelClass;
    public $fixtureNs = 'tests\codeception\fixtures';
    public $fixtureDataPath = '@tests/codeception/fixtures/data';
    public $grabData = false;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Fixture Class Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates fixture class for existing model class and prepares fixture data file.';
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            Yii::getAlias('@' . str_replace('\\', '/', $this->fixtureNs)) . '/' . $this->getFixtureClassName() . '.php',
            $this->render('class.php')
        );

        $files[] = new CodeFile(
            Yii::getAlias($this->fixtureDataPath) . '/' . $this->getDataFileName() . '.php',
            $this->render('data.php', ['items' => $this->getFixtureData()])
        );

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'fixtureNs', 'fixtureDataPath'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'fixtureNs', 'fixtureDataPath'], 'required'],
            [['modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['fixtureNs'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => ActiveRecord::className()]],
            [['fixtureDataPath'], 'match', 'pattern' => '/^@?\w+[\\-\\/\w]*$/', 'message' => 'Only word characters, dashes, slashes and @ are allowed.'],
            [['fixtureDataPath'], 'validatePath'],
            [['grabData'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
            'fixtureNs' => 'Fixture Class Namespace',
            'fixtureDataPath' => 'Fixture Data Path',
            'grabData' => 'Grab Existing DB Data',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['class.php', 'data.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['fixtureNs', 'fixtureDataPath']);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the model class. You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'fixtureNs' => 'This is the namespace for fixture class file, e.g., <code>tests\codeception\fixtures</code>.',
            'fixtureDataPath' => 'This is the root path to keep the generated fixture data files. You may provide either a directory or a path alias, e.g., <code>@tests/codeception/fixtures/data</code>.',
            'grabData' => 'If checked, the existed data from database will be grabbed into data file.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        return '<p>The fixture has been generated successfully.</p>';
    }

    public function validatePath($attribute)
    {
        $path = Yii::getAlias($this->$attribute, false);
        if ($path === false || !is_dir($path)) {
            $this->addError($attribute, 'Path does not exist.');
        }
    }

    public function getDataFileName()
    {
        /** @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        return preg_replace('/[{}%]+/', '', $modelClass::tableName());
    }

    public function getFixtureClassName()
    {
        return pathinfo(str_replace('\\', '/', $this->modelClass), PATHINFO_BASENAME) . 'Fixture';
    }

    /**
     * @return array
     */
    protected function getFixtureData()
    {
        /** @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $items = [];
        if ($this->grabData) {
            $orderBy = array_combine($modelClass::primaryKey(), array_fill(0, count($modelClass::primaryKey()), SORT_ASC));
            foreach ($modelClass::find()->orderBy($orderBy)->asArray()->each() as $row) {
                $item = [];
                foreach ($row as $name => $value) {
                    if (is_null($value)) {
                        $encValue = 'null';
                    } elseif (preg_match('/^(0|[1-9-]\d*)$/s', $value)) {
                        $encValue = $value;
                    } else {
                        $encValue = var_export($value, true);;
                    }
                    $item[$name] = $encValue;
                }
                $items[] = $item;
            }
        } else {
            $item = [];
            foreach ($modelClass::getTableSchema()->columns as $column) {
                $item[$column->name] = $column->allowNull ? 'null' : '\'\'';
            }
            $items[] = $item;
        }
        return $items;
    }
}
