<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="FixAnotherMSIEBug.xsl"?>
<?php
  /*
    use TianguisCabal;

    CREATE TABLE `Categoria` (
      `CatID` tinyint(4) NOT NULL auto_increment,
      `Categoria` varchar(50) NOT NULL default '',
      PRIMARY KEY  (`CatID`),
      UNIQUE KEY `Categoria` (`Categoria`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT INTO `Categoria` VALUES (1,'Almacenamiento'),(2,'Audio/Sonido'),
                                   (3,'Cables'),        (4,'Camaras'),
                                   (5,'Copiadoras'),    (6,'Energia'),
                                   (7,'FAX'),           (8,'Gabinetes'),
                                   (9,'Impresoras'),    (10,'Memoria'),
                                   (11,'Miscelanea'),   (12,'Monitores'),
                                   (13,'Mouse'),        (14,'Muebles'),
                                   (15,'Notebooks'),    (16,'PCs'),
                                   (17,'PDAs'),         (18,'Redes'),
                                   (19,'Scanners'),     (20,'Tarjetas Madres'),
                                   (21,'Teclados'),     (22,'Telefonia'),
                                   (23,'Video'),        (26,'No Computacional');

    CREATE TABLE `Vendedores` (
      `UserID` int(7) NOT NULL auto_increment,
      `ApellidoPaterno` varchar(25) NOT NULL default '',
      `ApellidoMaterno` varchar(25) default '',
      `Nombres` varchar(30) NOT NULL default '',
      `Login` varchar(25) NOT NULL default '',
      `PWD` varchar(50) NOT NULL default '',
      `Fecha` date NOT NULL default '0000-00-00',
      `Correo` varchar(75) NOT NULL default '',
      `MexTelLada` varchar(2) NOT NULL default '0',
      `MexTelFront` varchar(4) NOT NULL default '0',
      `MexTelBack` varchar(4) NOT NULL default '0',
      PRIMARY KEY  (`UserID`),
      UNIQUE KEY `Login` (`Login`)
      UNIQUE KEY `Correo` (`Correo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `Ventas` (
      `VentaID` int(7) NOT NULL auto_increment,
      `UserID` int(7) NOT NULL default '0',
      `Fecha` date NOT NULL default '0000-00-00',
      `Articulo` varchar(175) NOT NULL default '',
      `Cantidad` tinyint(4) NOT NULL default '1',
      `Descripcion` varchar(250) NOT NULL default '',
      `Calidad` enum('Excelente','Muy Buena','Buena','Regular','Partes') default 'Muy Buena',
      `Precio` varchar(10) NOT NULL default '00.00',
      `LinkFoto` varchar(175) default '',
      `CompraVenta` enum('Se Vende','Quiero Comprar') default 'Se Vende', `InterCambiar` varchar(12) default '',
      `CambiarParaQue` varchar(250) default '',
      `Categoria` varchar(50) NOT NULL default '',
      PRIMARY KEY  (`VentaID`),
      KEY `UserID` (`UserID`),
      KEY `Categoria` (`Categoria`),
      CONSTRAINT `Ventas_ibfk_2` FOREIGN KEY (`Categoria`) REFERENCES `Categoria` (`Categoria`),
      CONSTRAINT `Ventas_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Vendedores` (`UserID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


  */
  include( "includes/TianguisCabalFunctions.inc" );
  require_once( "/etc/TianguisCabal.inc" );

  if( @$_POST{'Sibmit'} ||
    ( @$_GET{'Accion'} && @$_GET{'Accion'} != "LogOut" ) )
  {
    if( $_SERVER{'SERVER_PORT'} != 443 )
    {
      if( $_SERVER{'HTTP_HOST'} == "www.linuxcabal.org" )
        header( "Location: https://www.imat.com/linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&From=Tianguis" );
      else if( $_SERVER{'HTTP_HOST'} == "localhost" )
        header( "Location: https://localhost/linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&From=Tianguis" );
      exit();
    }
  }

// <<<<---- LOGOUT ---->>>>>

  if( @$_GET{'Accion'} == "LogOut" )
  {
    DestruyeSession();
  }

// <<<--- APLICAR PWD --->>>>

  if( @$_POST{'Submit'} && @$_POST{'Accion'} == "AplicarPWD" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2001&Lang=$Lang&From=$From" );
      exit();
    }

    if( !@$_POST{'CambioPWD'} && !@$_POST{'ManPerfil'} )
    {
      require_once('recaptchalib.php');
      $publickey = $CaptchaPubKey;
      $privatekey = $CaptchaPrivKey;
      $resp = recaptcha_check_answer ($privatekey,
                                      $_SERVER{'REMOTE_ADDR'},
                                      $_POST{'recaptcha_challenge_field'},
                                      $_POST{'recaptcha_response_field'});

      if (!$resp->is_valid)
      {
        header( "Location: MensajeError.php?Errno=2122&Lang=$Lang&From=$From" );
                 //Captcha Validation Error
        exit();
      }

      include( "includes/TianguisCabalROEnviron.inc" );
      $Query = "select UserID from Vendedores 
                       where Login = '{$_POST{'Login'}}'";
      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2002&Lang=$Lang&From=$From" );
                 //No Puede select
        exit();
      }

      if( mysqli_num_rows( $QueryRes ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2003&Lang=$Lang&From=$From" );
                 //Cuenta Existe
        exit();
      }
    }

    if( @$_POST{'CambioPWD'}  || @$_POST{'ManPerfil'} )
    {
      require( "includes/TianguisCabalEnviron.inc" );
      $Query = "select PWD from Vendedores where UserID = '{$_SESSION{'UID'}}'";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2015&Lang=$Lang&From=$From" );
                 //No Puede select
        exit();
      }

      $PWDRec = mysqli_fetch_Array( $QueryRes );
      $PCur = $PWDRec{'PWD'};

      if( md5( $_POST{'PCur'} ) != $PCur )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2016&Lang=$Lang&From=$From" );
                 //PWD No está Valido
        exit();
      }
    }

    if( !@$_POST{'ManPerfil'} && !@$_POST{'CambioPWD'} )
    {
      IsValidMexTel( "MexTelBack", $_POST{'MexTelBack'}, 4 );
      IsValidMexTel( "MexTelFront", $_POST{'MexTelFront'}, 4 );

      if( !@$_POST{'ApellidoPaterno'} || !@$_POST{'Nombres'}
      || !@$_POST{'Correo'} || !@$_POST{'MexTelLada'}
      || !@$_POST{'MexTelFront'} || !@$_POST{'MexTelBack'}
      || !@$_POST{'Login'} || !@$_POST{'PNuevo'} || !@$_POST{'PVer'} )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2017&Lang=$Lang&From=$From" );
                 //Falta Campos Obligatorios
        exit();
      }
    }
    elseif( @$_POST{'ManPerfil'} )
    {
      IsValidInt( "MexTelBack", $_POST{'MexTelBack'}, 4 );
      IsValidInt( "MexTelFront", $_POST{'MexTelFront'}, 4 );

      if( !@$_POST{'ApellidoPaterno'} || !@$_POST{'Nombres'}
      || !@$_POST{'Correo'} || !@$_POST{'MexTelLada'}
      || !@$_POST{'MexTelFront'} || !@$_POST{'MexTelBack'}
      || !@$_POST{'Login'} )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2020&Lang=$Lang&From=$From" );
                 //Falta Campos Obligatorios
        exit();
      }
    }
    else
    {
      if( !@$_POST{'PNuevo'} || !@$_POST{'PVer'} )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2021&Lang=$Lang&From=$From" );
                 //Falta Campos Obligatorios
        exit();
      }
    }

    if( !@$_POST{'ManPerfil'} )
    {
      if( @$_POST{'PNuevo'} !== $_POST{'PVer'} )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2004&Lang=$Lang&From=$From" );
                 //Contraseñas No son Egual
        exit();
      }

      if( !IsPWDSeguro( $_POST{'PNuevo'} ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2008&Lang=$Lang&From=$From" );
                 //PWD no está Seguro
        exit();
      }
      $PWD = md5( $_POST{'PNuevo'} );
    }

    if( !@$_POST{'CambioPWD'} && !@$_POST{'ManPerfil'} )
    {
      $ApellidoPaterno = htmlspecialchars( $_POST{'ApellidoPaterno'},
                         ENT_QUOTES, "UTF-8" );
      $ApellidoMaterno = htmlspecialchars( $_POST{'ApellidoMaterno'},
                         ENT_QUOTES, "UTF-8" );
      $Nombres = htmlspecialchars( $_POST{'Nombres'}, ENT_QUOTES, "UTF-8" );
      $Login = htmlspecialchars( $_POST{'Login'}, ENT_QUOTES, "UTF-8" );
      if( IsValidCorreo( $_POST{'Correo'} ) )
        $Correo = $_POST{'Correo'};
      else
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2125&Lang=$Lang&From=$From&Var={$_POST{'Correo'}}" );
                 //Correo Invalido
        exit();
      }

      $Query = "insert into Vendedores values ( NULL,
                                                '{$ApellidoPaterno}',
                                                '{$ApellidoMaterno}',
                                                '{$Nombres}',
                                                '{$Login}',
                                                '{$PWD}',
                                                 CURDATE(),
                                                '{$Correo}',
                                                '{$_POST{'MexTelLada'}}',
                                                '{$_POST{'MexTelFront'}}',
                                                '{$_POST{'MexTelBack'}}' )";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2005&Lang=$Lang&From=$From" );
                 //No Puede insert
        exit();
      }
    }
    elseif( @$_POST{'ManPerfil'} )
    {
      $ApellidoPaterno = htmlspecialchars( $_POST{'ApellidoPaterno'},
                         ENT_QUOTES, "UTF-8" );
      $ApellidoMaterno = htmlspecialchars( $_POST{'ApellidoMaterno'},
                         ENT_QUOTES, "UTF-8" );
      $Nombres = htmlspecialchars( $_POST{'Nombres'}, ENT_QUOTES, "UTF-8" );
      $Correo = htmlspecialchars( $_POST{'Correo'}, ENT_QUOTES, "UTF-8" );
      $Query = "update Vendedores set
                                    ApellidoPaterno = '{$ApellidoPaterno}',
                                    ApellidoMaterno = '{$ApellidoMaterno}',
                                    Nombres =         '{$Nombres}',
                                    Fecha =            CURDATE(),
                                    Correo =          '{$Correo}',
                                    MexTelLada =      '{$_POST{'MexTelLada'}}',
                                    MexTelFront =     '{$_POST{'MexTelFront'}}',
                                    MexTelBack =      '{$_POST{'MexTelBack'}}'
                  where UserID = {$_SESSION{'UID'}}";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2022&Lang=$Lang&From=$From" );
                 //No Puede update
        exit();
      }
    }
    else
    {
      $Query = "update Vendedores set PWD = '{$PWD}', Fecha = CURDATE()
                  where UserID = {$_SESSION{'UID'}}";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2006&Lang=$Lang&From=$From" );
                 //No puede insert PWD
        exit();
      }
    }
    $UsuarioID = mysqli_insert_id( $Conn );
    mysqli_free_result( $QueryRes );
    mysqli_close( $Conn );

    if( !@$_POST{'CambioPWD'} && !@$_POST{'ManPerfil'} )
    {
      ini_set( "session.cache_expire", "30" );
      ini_set( "session.cookie_lifetime", "1800" );
      ini_set( "session.gc_maxlifetime", "1820" );
      include( "includes/TianguisCabalROEnviron.inc");
      session_start();
      $_SESSION{'PHPSESSID'} = session_id();
      $_SESSION{'Login'} = $_POST{'Login'};
      $_SESSION{'UID'} = $UsuarioID;
      setcookie( "Login", $_POST{'Login'} );
    }

    $Block = "   <p class=\"SubTitleFont\" style=\"font-weight:bold;
                    color:#0000aa; text-align:center;\">";
    if( !@$_POST{'CambioPWD'} && !@$_POST{'ManPerfil'} )
    {
      if( $Lang == 'en' )
        $Block .= "-=&nbsp;Creation of Your New Account&nbsp;=-";
      else
        $Block .= "-=&nbsp;Creaci&oacute;n de Tu Cuenta Nueva&nbsp;=-";
    }
    elseif( @$_POST{'CambioPWD'} )
    {
      if( $Lang == 'en' )
        $Block .= "-=&nbsp;Password Change&nbsp;=-";
      else
        $Block .= "-=&nbsp;Cambio de Tu Contrase&ntilde;a&nbsp;=-";
    }
    else
    {
      if( $Lang == 'en' )
        $Block .= "-=&nbsp;Updating of Your Profile&nbsp;=-";
      else
        $Block .= "-=&nbsp;Actualizaci&oacute;n de Tu Perfil&nbsp;=-";
    }
    $Block .= "    <br />";
    if( $Lang == 'en' )
      $Block .= "CONFIRMED!";
    else
      $Block .= "&iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .= "    <br /><br />
                 </p>
                 <form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=Tianguis\">
                   <p style=\"text-align: center;\">
                     <input  type=\"hidden\" name=\"Accion\"
                             value=\"Entra\" />
                     <input type=\"submit\" name=\"Submit\"
                            class=\"SubTitleFont\"
                            style=\"font-weight:bold; color:#0000aa;\"";
    if( $Lang == 'en' )
      $Block .= "           value=\"Click here to Continue\" />";
    else
      $Block .= "           value=\"Presiona aqu&iacute; para Continuar\" />";
    $Block .= "    </p>
                 </form>";
  }                                 // <<<<---- PICK PARA MODIFICAR ---->>>>
  elseif( @$_GET{'Accion'} == 'PickMod' )
  {
    include( "includes/TianguisCabalEnviron.inc" );
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2025&Lang=$Lang&From=$From" );
               //No puedo connect
      exit();
    }

    $Query = "select VentaID, Articulo, Cantidad, Precio from Ventas
                where UserID = {$_SESSION{'UID'}}";

    if( !$VentasRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2026&Lang=$Lang&From=$From" );
               //No Puede select
      exit();
    }

    $Block = "   <form method=\"post\" action=\"TianguisCabalOnLineArtNuevo.php?Lang=$Lang&amp;From=$From\">";

    if( ( $NumRows = mysqli_num_rows( $VentasRes ) ) < 1 )
    {
      header( "Location:MensajeError.php?Errno=3007" );
               // No Tiene Ventas a mostrar
      exit();
    }
    else
    {
      $Block .= "  <p style=\"font-weight:bold; color:#7700AA;
                      text-align: center;\" class=\"SubTitleFont\">";
      if( $Lang == 'en' )
        $Block .= "  Choose a record to Delete or Modify";
      else
        $Block .= "  Elige un registro para Editar o Borrar";
      $Block .= "    <br />
                   </p>
                   <table cellspacing=\"0\" cellpadding=\"0\" border=\"1\"
                          style=\"width:95%; background:#aaaaaa;
                          text-align:center; margin-left:2%\">
                     <tr style=\"background:url(images/logoBG.jpg);\">
                       <th style=\"white-space:nowrap;\">";
      if( $Lang == 'en' )
        $Block .= "      &nbsp;Artcle&nbsp;";
      else
        $Block .= "      &nbsp;Art&iacute;culo&nbsp;";
      $Block .= "      </th>
                       <th style=\"white-space:nowrap;\">";
      if( $Lang == 'en' )
        $Block .= "       &nbsp;Quantity&nbsp;";
      else
        $Block .= "       &nbsp;Cantidad&nbsp;";
      $Block .= "      </th>
                       <th style=\"white-space:nowrap;\">";
      if( $Lang == 'en' )
        $Block .= "      Price";
      else
        $Block .= "      Precio";
      $Block .= "      </th>
                     </tr>";
    $LineCount = 0;

    while( $Articulo = mysqli_fetch_array( $VentasRes ) )
    {
      $Block .= "    <tr>
                       <td style=\"white-space:nowrap; text-align:left;\">
                         <input type=\"radio\" name=\"CheckArtID\"
                                value=\"{$Articulo{'VentaID'}}\" />
                         &nbsp;{$Articulo{'Articulo'}}&nbsp;
                       </td>
                       <td style=\"text-align:center; white-space:nowrap;\">
                         &nbsp;{$Articulo{'Cantidad'}}&nbsp;
                       </td>
                       <td style=\"white-space:nowrap;\">
                         &nbsp;{$Articulo{'Precio'}}&nbsp;
                       </td>
                     </tr>";
      $LineCount++;
      if( !( $LineCount % 20 )  || $NumRows == $LineCount )
      {
        $Block .= "  <tr style=\"background:url(images/logoBG.jpg)\">
                       <th colspan=\"3\" style=\"white-space:nowrap;\">";
        if( $Lang == 'en' )
          $Block .= "    &nbsp;&nbsp;&nbsp;&nbsp;Edit";
        else
          $Block .= "    &nbsp;&nbsp;&nbsp;&nbsp;Editar";
        $Block .= "      <input type=\"radio\" name=\"ActionArtID\"
                                value=\"EditArtID\" />
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=\"submit\" name=\"Submit\"";
        if( $Lang == 'en' )
          $Block .= "           value=\"A P P L Y\" />";
        else
          $Block .= "           value=\"A P L I C A R\" />";
        $Block .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=\"radio\" name=\"ActionArtID\"
                                value=\"BorraArtID\" />";
        if( $Lang == 'en' )
          $Block .= "    Delete&nbsp;&nbsp;&nbsp;&nbsp;";
        else
          $Block .= "    Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
        $Block .= "    </th>
                     </tr>";
      }
    }
    $Block .= "    </table>
                 </form>
                 <p>
                   <a href=\"TianguisCabalOnLine.php?LOC=DeMira&amp;Lang=$Lang&amp;From=$From\">";
    if( $Lang == 'en' )
      $Block .= "    &nbsp;&nbsp;Menu&nbsp;&nbsp;</a>";
    else
      $Block .= "    &nbsp;&nbsp;Men&uacute;&nbsp;&nbsp;</a>";
    $Block .= "    <br />
                   <a href=\"TianguisCabalOnLine.php?Accion=LogOut&amp;Lang=$Lang&amp;From=$From\">&nbsp;&nbsp;LogOut&nbsp;&nbsp;</a>
                 </p>";
  }
  mysqli_free_result( $VentasRes );
  mysqli_close( $Conn );
  }                                        // <<<<---- UNIRTE ---->>>>
  elseif( ( @$_POST{'Submit'} && @$_POST{'Unirte'} )
            || @$_GET{'Accion'} == "CambioPWD"
            || @$_GET{'Accion'} == "ManPerfil" )
  {
    if( @$_POST{'Unirte'} )
      include( "includes/TianguisCabalROEnviron.inc" );
    else
      include( "includes/TianguisCabalEnviron.inc" );

    $Block = "   <p style=\"font-weigth:bold; color:#000055;
                    text-align: center;\" class=\"SubTitleFont\">";

    if( @$_GET{'Accion'} != "CambioPWD" && @$_GET{'Accion'} != "ManPerfil" )
    {
      if( $Lang == 'en' )
        $Block .= "  Register As a Seller";
      else
        $Block .= "  Registrar Como un Vendedor";
    }
    elseif( @$_GET{'Accion'} == "CambioPWD" )
    {
      if( $Lang == 'en' )
        $Block .= "  Change Your Password";
      else
        $Block .= "  Cambiar Tu Contrase&ntilde;a";
    }
    else
    {
      if( $Lang == 'en' )
        $Block .= "  Manage Your Personal Profile";
      else
        $Block .= "  Manejar tu Perfil Personal";
    }

    $Block .= "  </p>
                 <form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=$From\">
                   <table cellspacing=\"20\" cellpadding=\"0\"
                          style=\"margin: 2%; width:95%;\">";

    if( @$_GET{'Accion'} == "CambioPWD" || @$_GET{'Accion'} == "ManPerfil" )
    {
      $Block .=     "<tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Your present password";
      else
        $Block .= "      <em>*</em> Tu Contrase&ntilde;a Ahora";
      $Block .= "      </td>
                       <td>
                         <input type=\"password\" name=\"PCur\"
                                size=\"25\" maxlength=\"50\" />
                       </td>
                     </tr>";

      $Conn = mysqli_init();
      mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
      mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                           'TianguisCabal', MYSQLI_CLIENT_SSL );

      if( mysqli_connect_errno() )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2019&Lang=$Lang&From=$From" );
        //No puedo connect
        exit();
      }

      $Query = "select * from Vendedores where UserID = {$_SESSION{'UID'}}";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2018&Lang=$Lang&From=$From" );
        //No Puede select
        exit();
      }
      $UIDRec = mysqli_fetch_Array( $QueryRes );
    }

    if( @$_GET{'Accion'} == "ManPerfil" || @$_POST{'Unirte'} )
    {
      $Block .= "    <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Last Name";
      else
        $Block .= "      <em>*</em> Apellido Paterno";
      $Block .= "      </td>
                       <td>
                         <input type=\"text\" name=\"ApellidoPaterno\"
                                value=\"{$UIDRec{'ApellidoPaterno'}}\"
                                size=\"15\" maxlength=\"15\" />
                       </td>
                     </tr>
                     <tr>
                       <td>
                         Apellido Materno
                       </td>
                       <td>
                         <input type=\"text\" name=\"ApellidoMaterno\"
                                value=\"{$UIDRec{'ApellidoMaterno'}}\"
                                size=\"15\" maxlength=\"15\" />
                       </td>
                     </tr>
                     <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Name(s)";
      else
        $Block .= "      <em>*</em> Nombre(s)";
      $Block .= "      </td>
                       <td>
                         <input type=\"text\" name=\"Nombres\"
                                value=\"{$UIDRec{'Nombres'}}\"
                                size=\"20\" maxlength=\"20\" />
                       </td>
                     </tr>
                     <tr>
                       <td>
                         <em>*</em> E-Mail
                       </td>
                       <td>
                         <input type=\"text\" name=\"Correo\"
                                value=\"{$UIDRec{'Correo'}}\"
                                size=\"25\" maxlength=\"50\" />
                       </td>
                     </tr>
                     <tr>
                      <td>";
      if( $Lang == 'en' )
        $Block .= "     <em>*</em> Telephone";
      else
        $Block .= "     <em>*</em> Tel&eacute;fono";
      $Block .= "      </td>
                       <td>
                         <input type=\"text\" name=\"CountryCode\"
                                maxlength=\"2\" size=\"2\"
                                value=\"+52\" readonly=\"readonly\" />
                         <select name=\"MexTelLada\" size=\"1\">";

      for( $i = 10; $i < 100; $i++ )
      {
        $Block .= "        <option";

        if( $i == @$UIDRec{'MexTelLada'} )
          $Block .= "              selected=\"selected\">{$i}</option>";
        elseif ( $i == 33 && !@$UIDRec{'MexTelLada'} )
          $Block .= "              selected=\"selected\">{$i}</option>";
        else
          $Block .= "             >{$i}</option>";
      }
      $Block .= "        </select>
                         <input type=\"text\" name=\"MexTelFront\"
                                maxlength=\"4\" size=\"4\"
                                value=\"{$UIDRec{'MexTelFront'}}\" />
                         <input type=\"text\" name=\"MexTelBack\"
                                maxlength=\"4\" size=\"4\"
                                value=\"{$UIDRec{'MexTelBack'}}\" />
                       </td>
                     </tr>
                     <tr>
                       <td>
                         <em>*</em> Login
                       </td>
                       <td>
                         <input type=\"text\" name=\"Login\"
                                value=\"{$UIDRec{'Login'}}\"";

    if( @$_GET{'Accion'} == "ManPerfil" )
      $Block .= "               readonly=\"readonly\"";
    $Block .= "                 size=\"25\" maxlength=\"25\" />
                       </td>
                     </tr>";
    }

    if( @$_GET{'Accion'} != "ManPerfil" )
    {
      $Block .= "    <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> New Password";
      else
        $Block .= "      <em>*</em> Contrase&ntilde;a Nueva";
      $Block .= "      </td>
                       <td>
                         <input type=\"password\" name=\"PNuevo\"
                                size=\"25\" maxlength=\"50\" />
                       </td>
                     </tr>
                     <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Re-Enter your Password";
      else
        $Block .= "      <em>*</em> Verificar Contrase&ntilde;a";
      $Block .= "      </td>
                       <td>
                         <input type=\"password\" name=\"PVer\"
                                size=\"25\" maxlength=\"50\" />
                       </td>
                     </tr>";
    }
    if( @$_GET{'Accion'} == "CambioPWD" )
      $Block .= "    <tr>
                       <td colspan=\"2\">
                         <input type=\"hidden\" name=\"CambioPWD\"
                                value=\"1\" />
                       </td>
                     </tr>";
    elseif( @$_GET{'Accion'} == "ManPerfil" )
      $Block .= "    <tr>
                       <td colspan=\"2\">
                         <input type=\"hidden\" name=\"ManPerfil\"
                                value=\"1\" />
                       </td>
                     </tr>";

    $Block .= "      <tr>
                       <td colspan=\"2\" style=\"text-align:center;
                           font-weight:bold;\">";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Required Fields";
    else
      $Block .= "        <em>*</em> Campos Obligatorios";
    $Block .= "        </td>
                     </tr>";

    if( @$_POST{'Unirte'} )
    {
      require_once('recaptchalib.php');
      $publickey = $CaptchaPubKey;
      $Block .= "    <tr>
                       <td colspan=\"2\" align=\"center\">"
                      .  recaptcha_get_html($publickey, $use_ssl=true )
                   . " </td>
                     </tr>";
    }

    $Block .= "      <tr>
                       <td colspan=\"2\" style=\"text-align:center;\">
                         <input type=\"hidden\" name=\"Accion\"
                                value=\"AplicarPWD\" />
                         <input type=\"submit\" name=\"Submit\"
                                style=\"font-weight:bold\"";
    if( $Lang == 'en' )
      $Block .= "               value =\"A P P L Y\" />";
    else
      $Block .= "               value =\"A P L I C A R\" />";
    $Block .= "          &nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=\"reset\" value=\"Reset\" />
                       </td>
                     </tr>
                   </table>
                 </form>";

    if( @$_GET{'Accion'} == "CambioPWD" || @$_GET{'Accion'} == "ManPerfil" )
    {
      $Block .= "<p>
                   <a href=\"TianguisCabalOnLine.php?LOC=DeMira&amp;Lang=$Lang&amp;From=$From\">";
      if( $Lang == 'en' )
        $Block .= "  &nbsp;&nbsp;Menu&nbsp;&nbsp;</a>";
      else
        $Block .= "  &nbsp;&nbsp;Men&uacute;&nbsp;&nbsp;</a>";
      $Block .= "  <br />
                   <a href=\"TianguisCabalOnLine.php?Accion=LogOut&amp;Lang=$Lang&amp;From=$From\">&nbsp;&nbsp;LogOut&nbsp;&nbsp;</a>
                 </p>";
    }
  }                                          // <<<<---- ENTRA ---->>>>
  elseif( @$_GET{'LOC'} == "DeMira" || ( @$_POST{'Submit'}
       && @$_POST{'Accion'} == "Entra" ) )
  {
    include( "includes/TianguisCabalEnviron.inc" );

    $Block = "   <p style=\"font-weight:bold; color:#7700AA;
                    text-align:center;\" class=\"SubTitleFont\">";
    if( $Lang == 'en' )
      $Block .= "  Menu";
    else
      $Block .= "  Men&uacute;";
    $Block .= "    <br />
                 </p>
                 <p class=\"LargeTextFont\" style=\"text-align:center;\">
                   <a href=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=CambioPWD\">";
    if( $Lang == 'en' )
      $Block .= "    Change Your Password</a>";
    else
      $Block .= "    Cambiar Tu Contrase&ntilde;a</a>";
    $Block .= "    <br />
                   <a href=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=ManPerfil\"";
    if( $Lang == 'en' )
      $Block .= "     title=\"You may modify the details of your personal
                              profile here\">Manage Your Profile</a>";
    else
      $Block .= "     title=\"Aqu&iacute; puedes cambiar los detalles de
                              tus datos personales\">Manejar Tu Perfil</a>";
    $Block .= "    <br />
                   <a href=\"TianguisCabalOnLineArtNuevo.php?Lang=$Lang&amp;From=Tianguis\"";
    if( $Lang == 'en' )
      $Block .= "     title=\"Announce your desire to sell, buy or exchange
                              articles\">Publish Your Sales, Purchase and/or
                              Exchange Announcements</a>";
    else
      $Block .= "     title=\"Aqu&iacute; puedes anunciar al p&uacute;blico
                              tu deseo para vender, comprar o cambiar
                              art&iacute;culos\">Publicar Tus Ventas y/o Tus
                              Deseos a Comprar o Intercambiar Articulos</a>";
    $Block .= "    <br />
                   <a href=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=PickMod\"";
    if( $Lang == 'en' )
      $Block .= "     title=\"Delete or Edit the details of your published
                      sales, purchase and/or exchange announcements\">
                      Delete/Edit Your Advertisements</a>";
    else
      $Block .= "     title=\"Aqu&iacute; puedes cambiar los detalles de tus
                              publicaciones de comercio\">Borrar/Editar Tus
                              Ventas, Compras e Intercambios</a>";
    $Block .= "    <br />
                   <a href=\"http://www.linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=LogOut\"";
    if( $Lang == 'en' )
      $Block .= "     title=\"Click here to logout and exit\">LogOut</a>";
    else
      $Block .= "     title=\"Presiona aqu&iacute; para salir\">LogOut</a>";
    $Block .= "  </p>";
  }                                            // <<<<---- VERIFICA ---->>>>
  elseif( @$_POST{'Submit'} && @$_POST{'Accion'} == "Verifica" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2009&Lang=$Lang&From=$From" );
               //No puedo connect
      exit();
    }

    $Query = "select UserID from Vendedores where Login = '{$_POST{'Login'}}'";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2010&Lang=$Lang&From=$From" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $QueryRes ) != 1 )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2011&Lang=$Lang&From=$From" );
               //No Tienes Cuenta
      exit();
    }

    $UIDRec = mysqli_fetch_Array( $QueryRes );
    $UID = $UIDRec{'UserID'};
    $Query = "select Nombres, PWD from Vendedores where UserID = {$UID}";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2012&Lang=$Lang&From=$From" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $QueryRes ) != 1 )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2013&Lang=$Lang&From=$From" );
               //No Tienes PWD
      exit();
    }

    $PWDRec = mysqli_fetch_Array( $QueryRes );
    $PWD = $PWDRec{'PWD'};

    if( $PWD != md5( $_POST{'PWD'} ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2014&Lang=$Lang&From=$From" );
               //Entrada Denegada
      exit();
    }
    else
    {
      mysqli_close( $Conn );
      include( "includes/TianguisCabalROEnviron.inc" );
      session_start();
      $_SESSION{'PHPSESSID'} = session_id();
      $_SESSION{'Login'} = $_POST{'Login'};
      $_SESSION{'UID'} = $UID;
      setcookie( "Login", $_POST{'Login'} );
    }

    $Block = "   <p style=\"font-weight:bold; color:#0000aa;
                    text-align: center;\" class=\"SubTitleFont\">";
    if( $Lang == 'en' )
      $Block .= "  -=&nbsp;Welcome&nbsp;{$PWDRec{'Nombres'}}&nbsp;=-
                 </p>
                 <p style=\"text-align: center;\">
                   Articles not sold within 90 days will be purged             
                   <br />
                 </p>";
    else
      $Block .= "  -=&nbsp;Bi&eacute;nvenido&nbsp;{$PWDRec{'Nombres'}}&nbsp;=-
                 </p>
                 <p style=\"text-align: center;\">
                   Los art&iacute;culos que no se vendan dentro de 90
                   d&iacute;as ser&aacute;n retirados
                   <br />
                 </p>";
    $Block .= "  <form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=Tianguis\">
                   <p style=\"text-align: center;\">
                     <input  type=\"hidden\" name=\"Accion\"
                             value=\"Entra\" />
                     <input type=\"submit\" name=\"Submit\"
                            style=\"font-weight:bold; color:#0000aa;\"
                            class=\"SubTitleFont\"";
    if( $Lang == 'en' )
      $Block .= "           value=\"Click here to Enter\" />";
    else
      $Block .= "           value=\"Presiona aqu&iacute; para entrar\" />";
    $Block .= "    </p>
                 </form>";
  }
  elseif( @$_GET{'Accion'} == "Login" )     // DESPLEGA VENTANA DE LOGIN
  {
    include( "includes/TianguisCabalROEnviron.inc" );
    $Block = "   <form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=$From\">
                   <p style=\"text-align:center;\">";
    if( $Lang == 'en' )
      $Block .= "    Login to manage your profile or sales";
    else
      $Block .= "    Login para manejar tu perfil o ventas";
    $Block .= "    </p>
                   <p style=\"text-align:center;\">
                     Login:&nbsp;&nbsp;
                     <input type=\"text\" name=\"Login\"
                            size=\"10\" maxlength=\"25\" />
                   </p>
                   <p style=\"text-align:center;\">";
    if( $Lang == 'en' )
      $Block .= "    Password:&nbsp;&nbsp;";
    else
      $Block .= "    Contrase&ntilde;a:&nbsp;&nbsp;";
    $Block .= "      <input type=\"password\" name=\"PWD\"
                            size=\"9\" maxlength=\"50\" />
                   </p>
                   <p style=\"text-align:center; font-weight:bold;\">
                     <input type=\"checkbox\" name=\"Unirte\" />";
    if( $Lang == 'en' )
      $Block .= "    Click in the checkbox in order to register as a seller
                   </p>
                   <p style=\"text-align:center;\">
                     <a href=\"TianguisCabalOlvidarPass.php?Lang=$Lang&amp;From=$From\">Forgot
                       My Login and/or Password</a>";
    else
      $Block .= "    Haga clic en el checkbox si deseas registrar
                     como un vendedor
                   </p>
                   <p style=\"text-align:center;\">
                     <a href=\"TianguisCabalOlvidarPass.php?Lang=$Lang&amp;From=$From\">Olvid&eacute;
                       Mi Login y/o Contrase&ntilde;a</a>";
    $Block .= "    </p>
                   <p style=\"text-align:center;\">
                     <br />
                     <input type=\"hidden\" name=\"Accion\"
                            value=\"Verifica\" />
                     <input type=\"submit\" name=\"Submit\"
                            style=\"font-weight:bold\"";
    if( $Lang == 'en' )
      $Block .= "           value =\"A P P L Y\" />";
    else
      $Block .= "           value =\"A P L I C A R\" />";
    $Block .= "      &nbsp;&nbsp;&nbsp;&nbsp;
                     <input type=\"reset\" value=\"Reset\" />
                   </p>
                 </form>";
  }
  else        // MUESTRA LAS VENTAS
  {
    include( "includes/TianguisCabalROEnviron.inc" );

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, "localhost", $ClaseRO, $AccessTypeRO,
                           "TianguisCabal", MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=18003&Lang=$Lang&From=$From" );
      exit();
    }

    $Query = "select Categoria from Categoria";

    if( !$CatRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2121&Lang=$Lang&From=$From" );
      exit();
    }

    $Block = "   <form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=$From\">
                   <p style=\"text-align:center; font-weight:bold;\"
                      class=\"LargeTextFont\">";
    if( $Lang == 'en' )
      $Block .= "    Display:&nbsp;";
    else
      $Block .= "    Desplega:&nbsp;";
    $Block .= "      <select name=\"MostrarPor\" size=\"1\">";

    if( @$_POST{'MostrarPor'} == 'Todos Por Fecha' )
      $Block .= "      <option selected=\"selected\">Todos Por Fecha</option>";
    else
      $Block .= "      <option>Todos Por Fecha</option>";

    if( @$_POST{'MostrarPor'} == 'Todos Por Vendedor' )
      $Block .= "      <option selected=\"selected\">Todos Por Vendedor</option>";
    else
      $Block .= "      <option>Todos Por Vendedor</option>";

    if( @$_POST{'MostrarPor'} == 'Todos Por Categoria' )
      $Block .= "      <option selected=\"selected\">Todos Por Categoria</option>";
    else
      $Block .= "      <option>Todos Por Categoria</option>";

    if( @$_POST{'MostrarPor'} == 'Todos Por Comprador' )
      $Block .= "      <option selected=\"selected\">Todos Por Comprador</option>";
    else
      $Block .= "      <option>Todos Por Comprador</option>";

    if( @$_POST{'MostrarPor'} == 'Todos Por Intercambio' )
      $Block .= "      <option selected=\"selected\">Todos Por Intercambio</option>";
    else
      $Block .= "      <option>Todos Por Intercambio</option>";

    while( $Cat = mysqli_fetch_array( $CatRes ) )
    {
      $Block .= "      <option";

      if( @$_POST{'MostrarPor'} == $Cat{'Categoria'} )
        $Block .= "            selected=\"selected\">";
      else
        $Block .= "           >";
      $Block .= "{$Cat{'Categoria'}}</option>";
    }
    $Block .= "      </select>
                   </p>
                   <p style=\"text-align: center;\">
                     <input type=\"hidden\" name=\"Muestra\"
                            value=\"DeLaBase\" />
                     <input type=\"submit\" name=\"Submit\"
                            class=\"SubTitleFont\" style=\"font-weight:bold;
                            color:#0000aa;\"";
    if( $Lang == 'en' )
      $Block .= "           value=\"Click here to display selection\" />";
    else
      $Block .= "           value=\"Presiona aqu&iacute; para Desplegar\" />";
    $Block .= "    </p>
                 </form>";

    if( @$_POST{'MostrarPor'} == 'Todos Por Vendedor' )
      $Query = "select * from Ventas where CompraVenta = 'Se Vende'
                order by UserID";
    else if( @$_POST{'MostrarPor'} == 'Todos Por Fecha' )
      $Query = "select * from Ventas order by Fecha";
    else if( @$_POST{'MostrarPor'} == 'Todos Por Categoria' )
      $Query = "select * from Ventas order by Categoria";
    else if( @$_POST{'MostrarPor'} == 'Todos Por Comprador' )
      $Query = "select * from Ventas where CompraVenta = 'Quiero Comprar'
                order by UserID";
    else if( @$_POST{'MostrarPor'} == 'Todos Por Intercambio' )
      $Query = "Select * from Ventas where InterCambiar = 'InterCambiar'";
    else if( @$_POST{'Muestra'} )
      $Query = "select * from Ventas where Categoria ='{$_POST{'MostrarPor'}}'";
    else
      $Query = "select * from Ventas order by Fecha";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3002&Lang=$Lang&From=$From" );
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $QueryRes ) ) < 1 )
    {
      header( "Location:MensajeError.php?Errno=3001&Lang=$Lang&From=$From" );
      exit();
    }
    else
    {
      $Block .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"1\"
                        style=\"width:95%; margin-left:2%;
                        background-image:url(images/logoBG.jpg)\">";

      while( $Articulo = mysqli_fetch_array( $QueryRes ) )
      {
        $Block .= "<tr>
                     <td style=\"align:center\">
                       <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"
                              style=\"margin-left:2%; margin-right:2%;
                                      margin-bottom:2px;\">
                         <tr>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Article:&nbsp;";
        else
          $Block .= "        Art&iacute;culo:&nbsp;";
        $Block .= "        </td>
                           <td colspan=\"2\">
                             {$Articulo{'Articulo'}}
                           </td>
                           <td>
                             &nbsp;
                           </td>
                           <td style=\"text-align:right;\">";
        if( $Lang == 'en' )
          $Block .= "        Quality:&nbsp;";
        else
          $Block .= "        Calidad:&nbsp;";
        $Block .= "        </td>
                           <td>
                             {$Articulo{'Calidad'}}
                           </td>
                         </tr>
                         <tr>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Description:&nbsp;";
        else
          $Block .= "        Descripci&oacute;n:&nbsp;";
        $Block .= "        </td>
                           <td colspan=\"5\">
                             {$Articulo{'Descripcion'}}
                           </td>
                         </tr>";

        $Query = "select CONCAT( Nombres, ' ', ApellidoPaterno, ' ',
                         ApellidoMaterno ) as Nombre, Correo, MexTelLada,
                         MexTelFront, MexTelBack from Vendedores
                         where UserID = {$Articulo{'UserID'}}";

        if( !$QueryRes2 = mysqli_query( $Conn, $Query ) )
        {
          mysqli_close( $Conn );
          header( "Location: MensajeError.php?Errno=2124&Lang=$Lang&From=$From" );
          exit();
        }

        $NombreID = mysqli_fetch_array( $QueryRes2 );
        $Block .=       "<tr>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Quantity:&nbsp;";
        else
          $Block .= "        Cantidad:&nbsp;";
        $Block .= "        </td>
                           <td>
                             {$Articulo{'Cantidad'}}
                           </td>
                           <td style=\"text-align:right;\">";
        if( $Lang == 'en' )
          $Block .= "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price per Unit:&nbsp;&nbsp;";
        else
          $Block .= "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Cada Uno:&nbsp;&nbsp;";
        $Block .= "        </td>
                           <td>
                             \${$Articulo{'Precio'}}
                           </td>
                           <td style=\"text-align:right;\">";
        if( $Lang == 'en' )
          $Block .= "        &nbsp;&nbsp;&nbsp;&nbsp;Telephone:&nbsp;";
        else
          $Block .= "        &nbsp;&nbsp;&nbsp;&nbsp;Tel&eacute;fono:&nbsp;";
        $Block .= "        </td>
                           <td>
                             ({$NombreID{'MexTelLada'}})
                     {$NombreID{'MexTelFront'}}-{$NombreID{'MexTelBack'}}
                           </td>
                         </tr>
                         <tr>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Seller:&nbsp;";
        else
          $Block .= "        Vendedor:&nbsp;";
        $Block .= "        </td>
                           <td colspan=\"5\">
                             {$NombreID{'Nombre'}}
                           </td>
                         </tr>
                         <tr>
                           <td>
                             E-Mail:&nbsp;
                           </td>
                           <td colspan=\"3\">
                             <a href=\"mailto:{$NombreID{'Correo'}}\">{$NombreID{'Correo'}}</a>
                           </td>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Category:&nbsp;";
        else
          $Block .= "        Categor&iacute;a:&nbsp;";
        $Block .= "        </td>
                           <td>
                             {$Articulo{'Categoria'}}
                           </td>
                         </tr>
                         <tr>
                           <td>";
        if( $Lang == 'en' )
          $Block .= "        Action:&nbsp;";
        else
          $Block .= "        Acci&oacute;n:&nbsp;";
        $Block .= "        </td>
                           <td>
                             {$Articulo{'CompraVenta'}}
                           </td>
                           <td>
                             &nbsp;
                           </td>
                           <td colspan=\"3\">";

        if( $Articulo{'InterCambiar'} )
        {
          if( $Lang == 'en' )
            $Block .= "      desires to trade for:&nbsp;";
          else
            $Block .= "      Quiero intercambiar por:&nbsp;";
          $Block .= "      </td>
                         </tr>
                         <tr>
                           <td colspan=\"6\" style=\"text-align:center;\">
                             {$Articulo{'CambiarParaQue'}}
                           </td>
                         </tr>";
        }
        else
        {
          if( $Lang == 'en' )
            $Block .= "        Does NOT wish to trade";
          else
            $Block .= "        No Quiero intercambiar";
          $Block .= "      </td>
                         </tr>";
        }

        if( $Articulo{'LinkFoto'} )
        {
          $Block .= "    <tr style=\"text-align:center;\">
                           <td colspan=\"6\">
                             <a href=\"http://{$Articulo{'LinkFoto'}}\">";
          if( $Lang == 'en' )
            $Block .= "        Click here to see a photo of the article</a>";
          else
            $Block .= "        Click Para Ver la Foto del Art&iacute;culo</a>";
          $Block .= "      </td>
                         </tr>";
        }
         $Block .= "   </table>
                     </td>
                   </tr>";
      }
      $Block .=" </table>";
    }
    mysqli_free_result( $QueryRes );
    mysqli_free_result( $QueryRes2 );
    mysqli_close( $Conn );
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml;
                      charset=UTF-8" />
    <meta http-equiv="expires" content="-1" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="keywords" content="linux cabal, linuxcabal, TianguisCabal,
                 open source, free software, F/OSS, FOSS, GNU,
                 GNU/Linux, Linux, GNU &amp; Linux, Free and Open Source
                 Software, Free y Open Source Software, CoffeeNet, Coffee Net,
                 Richard Couture, Guadalajara, Jalisco, Mexico, M&eacute;xico,
                 Software Freedom Day, SFD, FLISoL, resumen del mes, Mandriva,
                 Fedora, CentOS, Red Hat, Ubuntu, Kubuntu, Edubuntu, Xubuntu,
                 Debian, Puppy, SUSE, Damn Small, Yellow Dog, Moblin, OpenSuSE,
                 Linux From Scratch, OpenBSD, BSD, NetBSD, DesktopBSD,
                 FreeBSD, TianguisCabal" />
    <script type="text/javascript">
      var RecaptchaOptions = {
          theme : 'blackglass',
          lang : 'es',
          tabindex : 2
      };
    </script>

    <title>
      TianguisCabal On Line - Un Lugar Donde Confiar
    </title>
    <link rel="stylesheet" type="text/css" href="includes/org.css" />
  </head>
  <body>
    <?php
      include( "./includes/Environ.php" );
    ?>
    <div class="menu">
      <?php
        include( "./includes/incMenu.php" );
        echo( "$DisplayBlock" );
      ?>
    </div>
    <div class="content">
      <div class="content-head">
        <div class="content-head-before">
          <img src="images/UpperCornerLeft.gif" alt="UpperCornerLeft.gif" />
          <?php
            include( "./includes/incCommonHeader.php" );
          ?>
          <p class="TitleFont">
        <?php
          if( $Lang == 'en' )
            echo( "TianguisCabal on Line" );
          else
            echo( "TianguisCabal en Linea" );
        ?>
          </p>
        </div>
      </div>
      <div class="content-main">
        <?php
          echo( "$Block" );
        ?>
        <p style="text-align: center;">
          <?php
            if( $Lang == 'en' )
              print( "LinuxCabal accepts absolutely no responsiblity
                      what-so-ever as to the servicabilty of items bought and
                      sold via this system.
                      <br />
                      Each buyer and seller is solely and individually
                      responsible." );
            else
              print( "LinuxCabal no se hace responsable; cada usuario
                      ser&aacute; responsable de sus compras y/o ventas." );
          ?>
        </p>
        <p style="text-align: center; font-weight:bold;" class="LargeTextFont">
          CAVEAT EMPTOR
        </p>
        <p style="text-align: center;">
          <?php
            if( $Lang == 'en' )
              print( "You may download the
                      <a href=\"TianguisCabalOnLine.tar.bz\">Source Code of
                                TianguisCabalOnLine</a> here" );
            else
              print( "&iexcl;Puedes descargar el
                      <a href=\"TianguisCabalOnLine.tar.bz\">Codigo Fuente de
                                TianguisCabalOnLine</a> aqu&iacute;" );
          ?>
        </p>
        <div class="content-main-after">
          <img src="images/LowerCornerLeft.gif" alt="LowerCornerLeft.gif" />
        </div>
      </div>
      <div class="content-footer">
        <?php
          include( "./includes/incCommonFooter.php" );
        ?>
      </div>
    </div>
  </body>
</html>

