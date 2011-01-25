<?php

$GLOBALS['TOKENS'] = array(
  'timestamp'         =>
    '^(?P<timestamp>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})',
  'player_name'       =>
    '\b(?P<player_name>\w+)\b',
  'var_player_name'   =>
    '\b(?P<%s_player_name>\w+)\b',
  'ip_address'        =>
    '\b(?P<ip_address>(?:\d{1,3}\.){3}\d{1,3})\b',
  'ip_with_port'      =>
    '\b(?P<ip_address>(?:\d{1,3}\.){3}\d{1,3}):(?P<port>\d{4,5})\b',
  'log_level'         =>
    '\[(?P<log_level>[A-Z]+)\]',
);

$GLOBALS['EVENTS'] = array(
  'player_logout'       => array(
    implode( ' ', array(
      $GLOBALS['TOKENS']['timestamp'],
      $GLOBALS['TOKENS']['log_level'],
      $GLOBALS['TOKENS']['player_name'],
      'lost connection: (?P<reason>.*)$' ) ),
    ),
  'player_login'       => array(
    implode( ' ', array(
      $GLOBALS['TOKENS']['timestamp'],
      $GLOBALS['TOKENS']['log_level'],
      $GLOBALS['TOKENS']['player_name'],
      '\[\/' . $GLOBALS['TOKENS']['ip_with_port'] . '\]',
      'logged in with entity id (?P<entity_id>\d+)$' ) ),
    ),
  'server_stop'        => array(
    implode( ' ', array(
      $GLOBALS['TOKENS']['timestamp'],
      $GLOBALS['TOKENS']['log_level'],
      'Stopping server' ) ),
    ),
  'server_start'       => array(
    implode( ' ', array(
      $GLOBALS['TOKENS']['timestamp'],
      $GLOBALS['TOKENS']['log_level'],
      'Starting minecraft server version (?P<version>.*)$' ) ),
    ),
  );