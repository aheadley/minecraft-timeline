<?php

class Minecraft_Parser_Timeline
  extends Minecraft_Parser {

  const MIN_PLAYER_ONLINE_SECS    = 300;
  
  protected $_timeline = null;
  protected $_loginTracking = array();
  protected $_serverTracking = null;

  public function __construct( Minecraft_Log $source, Timeline &$timeline ) {
    parent::__construct( $source );
    $this->_timeline = $timeline;
  }

  public function parse() {
    parent::parse();
    $this->_flushLogins();
    return $this->_timeline;
  }

  protected function _parseCallback( array $parsedLine ) {
    switch( $parsedLine['EVENT_TYPE'] ) {
      case 'player_login':
        if( isset( $this->_loginTracking[$parsedLine['player_name']] ) ) {
          //player logged in while still logged in, do nothing
        } else {
          $this->_loginTracking[$parsedLine['player_name']] = $parsedLine['timestamp'];
        }
        break;
      case 'player_logout':
        if( isset( $this->_loginTracking[$parsedLine['player_name']] ) ) {
          //normal logout
          if( $this->_parseTimestamp( $parsedLine['timestamp'] )->getTimestamp() -
                $this->_parseTimestamp( $this->_loginTracking[$parsedLine['player_name']] )
                  ->getTimestamp()
              > self::MIN_PLAYER_ONLINE_SECS ) {
            $this->_timeline->addEvent( new Timeline_Event( $parsedLine['player_name'],
              $this->_parseTimestamp( $this->_loginTracking[$parsedLine['player_name']] ),
              $this->_parseTimestamp( $parsedLine['timestamp'] ) ) );
          }
          unset( $this->_loginTracking[$parsedLine['player_name']] );
        } else {
          $this->_loginTracking[$parsedLine['player_name']] = $parsedLine['timestamp'];
        }
        break;
      case 'server_stop':
        if( is_null( $this->_serverTracking ) ) {
          //normal server shutdown
          $this->_flushLogins( $this->_parseTimestamp( $parsedLine['timestamp'] ) );
          $this->_serverTracking = $this->_parseTimestamp( $parsedLine['timestamp'] );
        } else {
          //this should never happen
          throw new Minecraft_Parser_Exception( 'Undetected server start' );
        }
        break;
      case 'server_start':
        if( is_null( $this->_serverTracking ) ) {
          //was hard crash
          $this->_flushLogins( $this->_parseTimestamp( $parsedLine['timestamp'] ) );
        }
        $this->_timeline->addEvent( new Timeline_Event( 'SERVER RESTART',
                                    $this->_parseTimestamp( $parsedLine['timestamp'] ) ) );
        $this->_serverTracking = null;
        break;
      default:
        //some event we don't care about
        break;
    }
  }

  private function _flushLogins( DateTime $end = null ) {
    if( is_null( $end ) ) {
      $end = new DateTime( 'now' );
    }
    foreach( $this->_loginTracking as $player => $login ) {
      $this->_timeline->addEvent( new Timeline_Event( $player,
                                  $this->_parseTimestamp( $login ),
                                  $end ) );
    }
    $this->_loginTracking = array();
  }

  private function _parseTimestamp( $timestamp ) {
    $datetime = DateTime::createFromFormat( Minecraft_Log_File::DATETIME_FORMAT,
                                         $timestamp );
    return $datetime;
  }
}