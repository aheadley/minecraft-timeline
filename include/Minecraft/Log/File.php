<?php

class Minecraft_Log_File
  extends Minecraft_Log {

  protected $_file = null;

  public function __construct( $file ) {
    $this->_file = $file;
  }

  public function current();
  public function key();
  public function next();
  public function rewind();
  public function valid();
}