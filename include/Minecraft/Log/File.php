<?php

class Minecraft_Log_File
  extends Minecraft_Log {

  const DATETIME_FORMAT = 'Y-m-d H:i:s';

  protected $_file = null;
  protected $_fileName = null;
  protected $_lineNumber = 0;
  protected $_line = null;

  public function __construct( $file ) {
    $this->_fileName = $file;
    $this->rewind();
  }

  public function __destruct() {
    @fclose( $this->_file );
  }

  public function current() {
    if( is_null( $this->_line ) ) {
      $this->_line = fgets( $this->_file );
    }
    return $this->_line;
  }

  public function key() {
    return $this->_lineNumber;
  }

  public function next() {
    $this->_line = fgets( $this->_file );
    $this->_lineNumber += 1;
  }

  public function rewind() {
    @fclose( $this->_file );
    $this->_file = fopen( $this->_fileName, 'r' );
    $this->_lineNumber = 0;
    $this->_line = null;
  }

  public function valid() {
    //TODO: fix this so last line isn't skipped
    return !feof( $this->_file );
  }
}