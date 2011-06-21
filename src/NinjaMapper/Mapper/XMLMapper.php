<?php

namespace NinjaMapper\Mapper;

use NinjaMapper\Reader\XMLReader;

class XMLMapper extends XMLReader {

    /**
     * @see NinjaMapper\Reader.XMLReader::parse()
     */
    public function parse($contents) {
        $dom = new \DOMDocument ( '1.1', 'UTF-8' );
        $dom->loadXML ( $contents );

        $dom->schemaValidate ( __DIR__ . '/../Resources/Mappings.xsd' );
        return $this->toMap($this->doParse ( $dom ));
    }

    protected function toMap($map) {
        $config = array();
        $self = $this;
        foreach($map['configuration']['mappings'][0]['mapping'] as $mapping) {
            // list($matchMap, $match);
            $matches = $mapping['match'];
            list($matchMap, $matches) = $this->getMatchMapMatches($matches);
            $config[$mapping['name']] = array(
                'match' => $matches,
                'matchMap' => $matchMap,
                'type' => $mapping['type']->getFirst()->getFirst()
            );
        }

        return $config;
    }

    protected function getMatchMapMatches($mapDefs) {
        $matchMap = array();
        $matches = array();
        foreach($mapDefs as $map) {
            $matches[] = $map['name'];
            if(isset($map['renameTo'])) {
                $matchMap[$map['name']] = $map['renameTo'];
            }
        }

        return array($matchMap, $matches);
    }
}