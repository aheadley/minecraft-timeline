<?php

class Timeline {

  protected $_bands = array();

  protected $_events = array();

  public function __construct() {

  }

  public function addEvent(Timeline_Event $event) {
    $this->_events[] = $event;
  }

  public function toXml( $file ) {
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $root = $dom->createElement( 'data' );
    foreach( $this->_events as $event ) {
      $root->appendChild( $event->toXml( $dom ) );
    }
    $dom->appendChild( $root );
    return $dom->saveXML();
  }

  public function toJson( $file ) {

  }
}
