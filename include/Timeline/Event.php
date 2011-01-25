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

  public function __construct( DateTime $start, DateTime $end = null,
                                $title = null ) {
    $this->_start = $start;
    $this->_end = $end;
    $this->_title = ( is_null( $title ) ? '' : $title );
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

  public function toXml() {

  }

  public function toJson() {

  }
}
