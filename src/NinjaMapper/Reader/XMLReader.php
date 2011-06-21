<?php

namespace NinjaMapper\Reader;

use NinjaMapper\Reader\XMLReader\Node;

class XMLReader implements ReaderInterface
{
    protected $seperateAttributes = false;

    /**
     * Read XML File
     *
     * This method reads and parses an XML file.
     *
     * <code>
     *     $reader = new XMLReader();
     *     $data = $reader->read('/my/valid/xml/file.xml');
     *     foreach($data as $node) {
     *         var_dump$node);
     *     }
     * </code>
     *
     * @see NinjaMapper\Reader\ReaderInterface::read()
     *
     * @param string The path to a valid XML file
     * @return array A parsed aray of nodes
     */
    public function parse($contents) {
        $dom = new \DOMDocument ( '1.1', 'UTF-8' );
        $dom->loadXML ( $contents );

        return $this->doParse ( $dom );
    }

    public function read($file) {
        if(!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exist', $file));
        }

        return $this->parse(file_get_contents ( $file ));
    }

    /**
     * Parse a single node
     *
     * @param \DOMNode The DOMNode to parse
     * @return \NinjaMapper\Reader\XMLReader\Node
     */
    protected function doParse(\DOMNode $node) {
        $array = new Node ();

        if ($node->hasAttributes ()) {
            $attributes = $this->parseAttributes ( $node, $array );
        }

        if ($node->hasChildNodes ()) {
            if ($node->childNodes->length == 1) {
                $childNode = $node->firstChild;
                $this->parseNode ( $childNode, $array, false );
            } else {
                foreach ( $node->childNodes as $childNode ) {
                    $this->parseNode($childNode, $array);
                }
            }
        }

        return $array;
    }

    protected function parseNode(\DOMNode $node, Node $array, $hasSiblings = true) {
        if ($hasSiblings) {
            if ($node->nodeType != XML_TEXT_NODE) {
                $newNode = $array [$node->nodeName];
                if (! is_string ( $newNode )) {
                    $newNode [] = $this->doParse ( $node );
                }
            }
        } else {
            if ($node->nodeType != XML_TEXT_NODE) {
                $array [$node->nodeName] = $this->doParse ( $node );
            } else {
                $array ['text'] = $node->nodeValue;
            }
        }
    }

    protected function parseAttributes(\DOMNode $node, Node $array) {
        if ($this->seperateAttributes) {
            $attributes = $array ['_attributes'];
        } else {
            $attributes = $array;
        }
        foreach ( $node->attributes as $attr ) {

            $attributes [$attr->nodeName] = $attr->nodeValue;
        }
    }

    /**
     * Get XMLReader::$seperateAttributes
     *
     * @see XMLReader::$seperateAttributes
     * @return mixed
     */
    public function getSeperateAttributes() {
        return $this->seperateAttributes;
    }

    /**
     * Set XMLReader::$seperateAttributes
     *
     * @see XMLReader::$seperateAttributes
     * @return XMLReader Refrence to self for fluent interface
     */
    public function setSeperateAttributes($seperateAttributes) {
        $this->seperateAttributes = $seperateAttributes;

        return $this;
    }

}