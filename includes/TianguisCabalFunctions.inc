<?php
  function IsValidMexTel( $DeDonde, $Num, $Size )
  {
    if( strlen( $Num ) != $Size )
    {
      header( "location:MensajeError.php?Errno=2023&Var=$DeDonde" );
      exit();
    }

    for( $i = 0; $i < strlen( $Num ); $i++ )
      if( ( ( $Num{$i} < "0" ) || ( $Num{$i}> "9" ) ) && ( $Num{0} != "-" ) )
      {
        header( "location:MensajeError.php?Errno=2024&Var=$DeDonde" );
        exit();
      }
    return( 1 );
  }

  function DestruyeSession()
  {
    session_start();

    $_SESSION = array();
    if( isset( $_COOKIE{'session_name()' } ) )
    {
      setcookie( session_name(), '', time() - 42000, '/');
      setcookie( "Login", '', time() - 42000, '/');
    }
    session_destroy();
  }

  function IsValidCorreo( $Correo )
  {
    $Arroba = 0;
    $Punto = 0;
    $ValChar = 0;

    if( ( $Len = strlen( "$Correo" ) ) < 6 )
      return 0;

    for( $i = 0; $i < $Len; $i++ )
      if( $Correo{$i} == '@' )
      {
        $Arroba++;
        $ValChar++;
      }
      else if( $Correo{$i} == '.' )
      {
        $Punto++;
        $ValChar++;
      }
      else if( ( $Correo{$i} >= '0' && $Correo{$i} <= '9' )
            || ( $Correo{$i} >= 'a' && $Correo{$i} <= 'z' )
            || ( $Correo{$i} >= 'A' && $Correo{$i} <= 'Z' )
            ||   $Correo{$i} == '-' || $Correo{$i} == '_' )
        $ValChar++;

    if( $Arroba != 1 || $Punto == 0 || $ValChar != $Len )
      return 0;
    else
      return 1;
  }

  function IsPWDSeguro( $Password )
  {
    $Min = 0;
    $May = 0;
    $Num = 0;
    $Pun = 0;

    if( ( $Len = strlen( "$Password" ) ) < 6 )
      return 0;

    for( $i = 0; $i < $Len; $i++ )
    {
      if( $Password{$i} >= 'a' && $Password{$i} <= 'z' )
        $Min++;
      else if( $Password{$i} >= 'A' && $Password{$i} <= 'Z' )
        $May++;
      else if( $Password{$i} >= 1 && $Password{$i} <= 9 )
        $Num++;
      else
        $Pun++;
    }

    if( $Min && $May && $Num && $Pun )
      return 1;
    else
      return 0;
  }

  function SuperTrim( $Input )
  {
    $i = $j = 0;
    $Out = $Input;

    while( $Input{$i} == ' '  || $Input{$i} == '\t' || $Input{$i} == '\n'
        || $Input{$i} == '\r' || $Input{$i} == '\0' || $Input{$i} == '\x0B' )
      $i++;
    while( $i < strlen( $Input ) )
    {
      if(    $Input{$i} != ' '  && $Input{$i} != '\t' && $Input{$i} != '\n'
          && $Input{$i} != '\r' && $Input{$i} != '\0' && $Input{$i} != '\x0B' )
        $Out{$j++} = $Input{$i++};
      else
      {
        $Out{$j++} = $Input{$i++};
        while( $Input{$i} == ' ' || $Input{$i} == '\t' || $Input{$i} == '\n'
           || $Input{$i} == '\r' || $Input{$i} == '\0' || $Input{$i} == '\x0B' )
         $i++;
      }
    }
    while( $j < strlen( $Input ) )
      $Out{$j++} = ' ';
    return( trim( $Out ) );
  }

  function DiasDesdeMySQLFecha( $Fecha )
  {
    settype( $Fecha, 'string' );
    eregi( '(....)(.)(..)(.)(..)', $Fecha, $Matches );
    array_shift( $Matches );
    foreach( array( 'year', 't1', 'month', 't2', 'day' ) as $Var )
      $$Var = array_shift( $Matches );
    $Edad =  ( mktime() - mktime( 0, 0, 0, $month, $day, $year ) ) / 86400;
    settype( $Edad, 'integer' );
    return( $Edad );
  }

  function DirList( $Dir="../ProdFotos" )
  {
    $DirRes = array();
    $Handler = opendir( $Dir );
    while ( $File = readdir( $Handler ) )
      if ( ( $File != '.' ) && ( $File != '..' ) )
        $DirRes[] = $File;
    closedir( $Handler );
    return $DirRes;
  }

  function IsValidInt( $DeDonde, $Num, $Size )
  {
    if( strlen( $Num ) > $Size )
    {
      header( "location:MensajeError.php?Errno=6001&Var=$DeDonde" );
      exit();
    }

    for( $i = 0; $i < strlen( $Num ); $i++ )
      if( ( ( $Num{$i} < "0" ) || ( $Num{$i}> "9" ) ) && ( $Num{0} != "-" ) )
      {
        header( "location:MensajeError.php?Errno=6002&Var=$DeDonde" );
        exit();
      }
    return( 1 );
  }

  function IsValidDouble( $DeDonde, $Num, $Size )
  {
    $ExistPunto = 0;

    if( strlen( $Num ) > $Size )
    {
      header( "location:MensajeError.php?Errno=6003&Var=$DeDonde" );
      exit();
    }

    for( $i = 0; $i < strlen( $Num ); $i++ )
    {
      if( ( ( $Num{$i} < "0" ) || ( $Num{$i}> "9" ) ) && ( $Num{0} != "-" ) )
      {
        if( $Num{$i} == "." )
        {
          if( $i > $Size - 3 )
          {
            header( "location:MensajeError.php?Errno=6004&Var=$DeDonde" );
            exit();
          }
          else
          {
            $ExistPunto++;
            $PuntoPos = $i;
          }
        }
        else
        {
          header( "location:MensajeError.php?Errno=6005&Var=$DeDonde" );
          exit();
        }
      }
    }
    if( !$ExistPunto && ( strlen( $Num ) > ( $Size - 3 ) ) )
    {
      header( "location:MensajeError.php?Errno=6006&Var=$DeDonde" );
      exit();
    }

    if( $ExistPunto> 1 )
    {
      header( "location:MensajeError.php?Errno=6007&Var=$DeDonde" );
      exit();
    }

    if( $ExistPunto && ( ( $i - $PuntoPos ) > 3 ) )
    {
      header( "location:MensajeError.php?Errno=6008&Var=$DeDonde" );
      exit();
    }
    return( 1 );
  }
?>
