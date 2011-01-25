<?php

abstract class Minecraft_Parser {

  protected $_source = null;

  public function __construct( Minecraft_Log $source ) {
    $this->_source = $source;
  }

  public function parse() {
    foreach( $this->_source as $line ) {
      try {
        $parsedLine = $this->_parseLine( $line );
        if( is_array( $parsedLine ) ) {
          $this->_parseCallback( $parsedLine );
        }
      } catch( Minecraft_Parser_Exception $e ) {
        /* malformed line or something, don't care */
      }
    }
  }

  abstract protected function _parseCallback( array $parsedLine );
  
  protected function _parseLine( $line ) {
    foreach( $GLOBALS['EVENTS'] as $eventType => $eventPatterns ) {
      foreach( $eventPatterns as $eventPattern ) {
        if( preg_match( '/' . $eventPattern . '/', $line, $matches ) ) {
          $matches['EVENT_TYPE'] = $eventType;
          return $matches;
        }
      }
    }
  }
}
