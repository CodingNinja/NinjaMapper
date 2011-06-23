<?php
namespace Ninja\Mapper\Tests\Mapper;

use Ninja\Mapper\Mapper;

use Ninja\Mapper\Tests\TestCase;

use Ninja\Mapper\Reader\XML;

class MapperTest extends TestCase
{
    public function testMapperAcceptsAConfigurationArrayAndRemapsUsingSuppliedConfig() {
        $mapper = new Mapper ( array (
            'dataArray' => array (
                array (
                    'name' => 'Dave',
                    'name2' => 'Mann'
                )
            ),
            'mapArray' => array (
                'Name ads' => array (
                    'match' => array (
                        'name',
                        'name2'
                    ),
                    'type' => '1'
                )
            ),
            'matchMap' => array ()
        ) );

        $this->assertSame ( $mapper->map (), array (
            array (
                'Name ads' => 'DaveMann'
            )
        ) );
    }

    public function testCanSetConfigurationOptionsMayWays() {
        $mapper = new Mapper ();

        $mapper->setConfig ( array (
            'matchMap' => array ()
        ) );
        $this->assertSame ( $mapper->getConfig ( 'matchMap' ), array () );

        $mapper->setConfig ( 'matchMap', array (
            'test'
        ) );
        $this->assertSame ( $mapper->getConfig ( 'matchMap' ), array (
            'test'
        ) );

        $this->assertSame(array('matchMap' => array('test')), $mapper->getConfig());
   }

    public function getMaps() {
        return array (
            array (
                array (
                    'Name ads' => array (
                        'match' => array (
                            'name',
                            'name2'
                        ),
                        'matchMap' => array (
                            'name' => 'name1'
                        ),
                        'type' => '1'
                    )
                )
            )
        );
    }

    /**
     * @dataProvider getMaps
     */
    public function testCanUseAMappingFileForMapping($map) {
        $mapper = new Mapper ();
        $mapper->setMapFile ( __DIR__ . '/../Fixtures/SimpleMap.xml' );
        $this->assertEquals ( $mapper->getMap ()->getMap (), $map );

        return array (
            $mapper,
            $map
        );
    }

    /**
     * @dataProvider getMaps
     */
    public function testCanUseStraightArrayForMapping($map) {
        $mapper = new Mapper ();
        $mapper->setMapArray ( $map );
        $this->assertSame ( $mapper->getMap ()->getMap (), $map );
        $mapper->setMapArray ( array () );
        $this->assertSame ( $mapper->getMap ()->getMap (), array () );
        $mapper->setConfig ( 'mapArray', $map );
        $this->assertSame ( $mapper->getMap ()->getMap (), $map );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownOnNullOffsetGetOffset() {
        $mapper = new Mapper();
        $mapper[] = '';
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testExceptionThrownOnInvalidMethod() {
        $mapper = new Mapper();
        $mapper->fail();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownOnInvalidMappingFile() {
        $mapper = new Mapper();
        $mapper->setMapFile('non/existant/file.csv');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownOnInvalidDataFile() {
        $mapper = new Mapper();
        $mapper->setDataFile('non/existant/file.csv');
    }

}