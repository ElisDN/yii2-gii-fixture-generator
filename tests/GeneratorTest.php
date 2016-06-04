<?php

namespace elisdn\gii\fixture\tests;

use elisdn\gii\fixture\Generator as FixtureGenerator;
use Yii;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;

class GeneratorTest extends TestCase
{
    public function testValidateIncorrect()
    {
        $generator = new FixtureGenerator();
        $generator->modelClass = 'tests\Fake';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataPath = '@tests/runtime/fake';
        $generator->grabData = true;

        $this->assertFalse($generator->validate());
        $this->assertEquals($generator->getFirstError('dataPath'), 'Path does not exist.');
        $this->assertEquals($generator->getFirstError('modelClass'), 'Class \'tests\\Fake\' does not exist or has syntax error.');
    }

    public function testValidateCorrect()
    {
        $generator = new FixtureGenerator();
        $generator->modelClass = 'elisdn\gii\fixture\tests\Post';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataPath = '@tests/runtime/data';
        $generator->grabData = true;

        $this->assertTrue($generator->validate(), 'Validation failed: ' . print_r($generator->getErrors(), true));
    }

    public function testDefaultNames()
    {
        $generator = new FixtureGenerator();
        $generator->modelClass = 'elisdn\gii\fixture\tests\Post';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataPath = '@tests/runtime/data';
        $generator->grabData = false;

        $this->assertEquals('PostFixture', $generator->getFixtureClassName());
        $this->assertEquals('post.php', $generator->getDataFileName());
    }

    public function testSpecificNames()
    {
        $generator = new FixtureGenerator();
        $generator->modelClass = 'elisdn\gii\fixture\tests\Post';
        $generator->fixtureClass = 'PostCustomFixture';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataFile = 'post-custom.php';
        $generator->dataPath = '@tests/runtime/data';
        $generator->grabData = false;

        $this->assertEquals('PostCustomFixture', $generator->getFixtureClassName());
        $this->assertEquals('post-custom.php', $generator->getDataFileName());
    }

    public function testGenerateWithoutData()
    {
        $this->initDb();

        $generator = new FixtureGenerator();
        $generator->modelClass = 'elisdn\gii\fixture\tests\Post';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataPath = '@tests/runtime/data';
        $generator->grabData = false;

        /** @var CodeFile[] $files */
        $files = $generator->generate();

        $this->assertEquals(2, count($files));

        $this->assertEquals(file_get_contents(__DIR__ . '/expected/class.php'), $files[0]->content);
        $this->assertEquals(file_get_contents(__DIR__ . '/expected/data-empty.php'), $files[1]->content);
    }

    public function testGenerateWithData()
    {
        $this->initDb();

        $generator = new FixtureGenerator();
        $generator->modelClass = 'elisdn\gii\fixture\tests\Post';
        $generator->fixtureNs = 'tests\runtime';
        $generator->dataPath = '@tests/runtime/data';
        $generator->grabData = true;

        /** @var CodeFile[] $files */
        $files = $generator->generate();

        $this->assertEquals(2, count($files));

        $this->assertEquals(file_get_contents(__DIR__ . '/expected/class.php'), $files[0]->content);
        $this->assertEquals(file_get_contents(__DIR__ . '/expected/data-full.php'), $files[1]->content);
    }

    private function initDb()
    {
        @unlink(__DIR__ . '/runtime/sqlite.db');
        $db = new Connection([
            'dsn' => 'sqlite:' . Yii::$app->getRuntimePath() . '/sqlite.db',
            'charset' => 'utf8',
        ]);
        Yii::$app->set('db', $db);
        $db->createCommand()->createTable('post', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'content' => Schema::TYPE_TEXT,
            'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL'
        ])->execute();
        $db->createCommand()->insert('post', [
            'id' => 1,
            'title' => 'First Title',
            'content' => null,
            'status' => 0,
            'created_at' => 1459672035
        ])->execute();
        $db->createCommand()->insert('post', [
            'id' => 2,
            'title' => 'Second Title',
            'content' => 'Second Content',
            'status' => 1,
            'created_at' => 1459672036,
        ])->execute();
    }
}
