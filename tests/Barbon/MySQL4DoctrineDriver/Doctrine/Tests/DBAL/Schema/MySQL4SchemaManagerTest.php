<?php

namespace Barbon\MySQL4DoctrineDriver\Doctrine\Tests\DBAL\Schema;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\Tests\DBAL\Mocks;
use Barbon\MySQL4DoctrineDriver\Doctrine\DBAL\Schema\MySQL4SchemaManager;

/**
 * Class MySQL4SchemaManagerTest
 *
 * @package Barbon\MySQL4DoctrineDriver\Doctrine\Tests\DBAL\Schema
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class MySQL4SchemaManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    private $manager;

    public function setUp()
    {
        $eventManager = new EventManager();
        $driverMock = $this->getMock('Doctrine\DBAL\Driver');
        $platform = $this->getMock('Barbon\MySQL4DoctrineDriver\Doctrine\DBAL\Platforms\MySQL4Platform');
        $this->conn = $this->getMock(
            'Doctrine\DBAL\Connection',
            array('fetchAll'),
            array(array('platform' => $platform), $driverMock, new Configuration(), $eventManager)
        );
        $this->manager = new MySQL4SchemaManager($this->conn);
    }

    public function testCompositeForeignKeys()
    {
        $this->conn->expects($this->once())->method('fetchAll')->will($this->returnValue($this->getFKDefinition()));
        $fkeys = $this->manager->listTableForeignKeys('dummy');
        $this->assertEquals(1, count($fkeys), "Table has to have one foreign key.");

        $this->assertInstanceOf('Doctrine\DBAL\Schema\ForeignKeyConstraint', $fkeys[0]);
        $this->assertEquals(array('column_1', 'column_2', 'column_3'), array_map('strtolower', $fkeys[0]->getLocalColumns()));
        $this->assertEquals(array('column_1', 'column_2', 'column_3'), array_map('strtolower', $fkeys[0]->getForeignColumns()));
    }

    public function getFKDefinition()
    {
        return array(
            array(
                "CONSTRAINT_NAME" => "FK_C1B1712387FE737264DE5A5511B8B3E",
                "COLUMN_NAME" => "column_1",
                "REFERENCED_TABLE_NAME" => "dummy",
                "REFERENCED_COLUMN_NAME" => "column_1",
                "update_rule" => "RESTRICT",
                "delete_rule" => "RESTRICT",
            ),
            array(
                "CONSTRAINT_NAME" => "FK_C1B1712387FE737264DE5A5511B8B3E",
                "COLUMN_NAME" => "column_2",
                "REFERENCED_TABLE_NAME" => "dummy",
                "REFERENCED_COLUMN_NAME" => "column_2",
                "update_rule" => "RESTRICT",
                "delete_rule" => "RESTRICT",
            ),
            array(
                "CONSTRAINT_NAME" => "FK_C1B1712387FE737264DE5A5511B8B3E",
                "COLUMN_NAME" => "column_3",
                "REFERENCED_TABLE_NAME" => "dummy",
                "REFERENCED_COLUMN_NAME" => "column_3",
                "update_rule" => "RESTRICT",
                "delete_rule" => "RESTRICT",
            )
        );
    }
}
