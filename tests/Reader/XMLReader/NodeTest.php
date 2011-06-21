<?php
namespace NinjaMapper\Tests\Reader\XMLReader;

use NinjaMapper\Tests\TestCase;

use NinjaMapper\Reader\XMLReader\Node;

use NinjaMapper\Reader\XMLReader;

class NodeTest extends TestCase
{

    public function testNode() {
        $node = new Node ();
        $this->assertInstanceOf ( '\NinjaMapper\Reader\XMLReader\Node', $node );
        $this->assertInstanceOf ( '\NinjaMapper\Reader\XMLReader\Node', $node ['test'] );
    }

    public function testGettersSetters() {
        $node = new Node ();

        $node->setName ( 'Test' );
        $this->assertEquals ( 'Test', $node->getName () );

        $node->setValue ( 'Test' );
        $this->assertEquals ( 'Test', $node->getValue () );

        $node->appendValue ( 'Test' );
        $this->assertEquals ( 'TestTest', $node->getValue () );

        $this->assertEquals ( $node->toArray (), array (
            'name' => 'Test',
            'value' => 'TestTest'
        ) );

        $node ['test'] ['test']->setName('asd');
        $this->assertEquals ( $node->toArray ( false ), array (
            'name' => 'Test',
            'value' => 'TestTest'
        ) );

        $this->assertEquals ( $node->toArray ( true ), array (
            'name' => 'Test',
            'value' => 'TestTest',
            'test' => array (
                'test' => array(
                    'name' => 'asd'
                )
            )
        ) );

        $node->setAttributes( array('test' => 'test') );
        $this->assertEquals ( array('test' => 'test'), $node->getAttributes() );
    }
}