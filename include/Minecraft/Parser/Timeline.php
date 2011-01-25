<?php

class Minecraft_Parser_Timeline
  extends Minecraft_Parser {
  
  protected $_timeline = null;
  protected $_loginTracking = array();
  protected $_serverTracking = false;

  public function __construct( Minecraft_Log $source, Timeline &$timeline ) {
    parent::__construct($source);
    $this->_timeline = $timeline;
  }

  public function  parse() {
    parent::parse();
    $this->_flushLogins();
  }

  protected function _parseCallback( array $parsedLine ) {
    switch( $parsedLine['EVENT_TYPE'] ) {
      case 'login':
        //TODO: check if they're logged in first
        $this->_loginTracking[$parsedLine['player']] = $parsedLine['timestamp'];
        break;
      case 'logout':
        break;
      case 'server_stop':
        if( $this->_serverTracking ) {
          //normal server shutdown
          $this->_flushLogins();
        } else {
          //this should never happen
          throw new Minecraft_Parser_Exception( 'Undetected server start' );
        }
        break;
      case 'server_start':
        if( $this->_serverTracking ) {
          $this->_flushLogins();
          //was hard crash
        } else {
          //soft crash or graceful restart
        }
        break;
      default:
        //some event we don't care about
        break;
    }
  }

  private function _flushLogins() {
    $logout = new DateTime( 'now' );
    foreach( $this->_loginTracking as $player => $login ) {
      $this->_timeline->addEvent( new DateTime( $login ),
                                  $logout,
                                  $player . ' online' );
    }
    $this->_loginTracking = array();
  }
}