<?php

class Timeline_Event {

  const FORMAT_STRING         = 'c';

  protected $_start           = null;
  protected $_latestStart     = null;
  protected $_end             = null;
  protected $_earliestEnd     = null;
  protected $_durationEvent   = null;
  protected $_title           = null;

  protected $_extraOptions    = array();

  public function __construct( $title, DateTime $start, DateTime $end = null ) {
    $this->_title = $title;
    $this->_start = $start;
    $this->_end = $end;
    $this->_durationEvent = !is_null( $this->_end );
  }

  public function getOption( $option ) {
    if( isset( $this->_extraOptions[$option] ) ) {
      return $this->_extraOptions[$option];
    } else {
      throw new Timeline_Event_Exception( 'Unknown option: ' . $option );
    }
  }

  public function setOption( $option, $value ) {
    $this->_extraOptions[$option] = $value;
  }

  public function toXml( &$parentNode ) {
    $node = $parentNode->createElement( 'event' );
    $node->setAttribute( 'title', $this->_title );
    $node->setAttribute( 'start', $this->_start->format( self::FORMAT_STRING ) );
    if( !is_null( $this->_end ) ) {
      $node->setAttribute( 'end', $this->_end->format( self::FORMAT_STRING ) );
      $node->setAttribute( 'durationEvent', 'true' );
    } else {
      $node->setAttribute( 'durationEvent', 'false' );
    }
    foreach( $this->_extraOptions as $option => $value ) {
      $node->setAttribute( $option, $value );
    }
    return $node;
  }

  public function toJson() {

  }
}
