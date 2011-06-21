<?php
namespace NinjaMapper\Tests\Reader;

use NinjaMapper\Reader\XMLReader;

class XmlReaderTest extends TestCase
{
    protected $data = array (
        'splitAttribs' => array (
            'configuration' => array (
                'config' => array (
                    array (
                        '_attributes' => array (
                            'name' => 'config',
                            'value' => 'test'
                        )
                    )
                ),
                'mappings' => array (
                    array (
                        'mapping' => array (
                            array (
                                '_attributes' => array (
                                    'name' => 'name'
                                ),
                                'match' => array (
                                    array (
                                        '_attributes' => array (
                                        	'name' => 'firstName'
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),
        'mergedAttribs' => array (
            'configuration' => array (
                'config' => array (
                    array (
                        'name' => 'config',
                        'value' => 'test'
                    )
                ),
                'mappings' => array (
                    array (
                        'mapping' => array (
                            array (
                                'name' => 'name',
                                'match' => array (
                                    array (
                                        'name' => 'firstName'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    );

    /**
     * Test to ensure the reader reads a file properly without dying
     */
    public function testReadsValidFile() {
        $reader = new XMLReader ();
        $data = $reader->read ( __DIR__ . '/../Fixtures/Valid.xml' )->toArray();
        $this->assertInternalType ( 'array', $data, '::read is able to interpret a valid xml into an array' );
    }

    public function testParsesXml() {
        $reader = new XMLReader ();
        $data = $reader->read ( __DIR__ . '/../Fixtures/format.xml' )->toArray();
        $this->assertEquals ( $data, $this->data ['mergedAttribs'] );
    }

    public function testParsesXmlAndSeperatesAttributesWhenSpecified() {
        $reader = new XMLReader ();
        $reader->setSeperateAttributes ( true );
        $data = $reader->read ( __DIR__ . '/../Fixtures/format.xml' )->toArray();
        $this->assertEquals ( $data, $this->data ['splitAttribs'] );
    }
}