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
?>
