<?php
  if( @$_GET{'Lang'} == 'en' )
  {
    $Lang = 'en';
  }
  else
  {
    $Lang = 'es';
  }

  if( @$_GET{'From'} )
    $From = $_GET{'From'};
  else
    $From = '';

  if( @$_GET{'DD'} )
  {
    $DD = $_GET{'DD'};
  }
  else
  {
    $DD = './';
  }

  if( @$_GET{'PicNum'} )
  {
    $PicNum = $_GET{'PicNum'};
  }
  else
  {
    $PicNum = 1;
  }

  // Este Archivo: Environ.inc
        
  ini_set( "session.cache_expire", "30" );
  ini_set( "session.cookie_lifetime", "1800" );
  ini_set( "session.gc_maxlifetime", "1820" );
  
  session_start();

  if( session_id() != $_SESSION{'PHPSESSID'} )
  {
    if( $_SERVER{'HTTP_HOST'} == "www.linuxcabal.org" )
      header( "Location: http://www.linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis" );
    else if( $_SERVER{'HTTP_HOST'} == "localhost" )
      header( "Location: http://localhost/linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis" );
    exit();
  }
?>
