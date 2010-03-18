<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="FixAnotherMSIEBug.xsl"?>
<?php
  include( "includes/TianguisCabalFunctions.inc" );
  include( "includes/TianguisCabalROEnviron.inc" );
  require_once( "/etc/TianguisCabal.inc" );

  if( @$_POST{'Accion'} == "CambiaPWD" )
  {
    if( !@$_POST{'UserID'} )
    {
      header( "Location: MensajeError.php?Errno=2211&Lang=$Lang&From=$From" );
               //Falta Non User suplied Campos Obligatorios
      exit();
    }

    if( !@$_POST{'PNuevo'} || !@$_POST{'PVer'} )
    {
      header( "Location: MensajeError.php?Errno=2207&Lang=$Lang&From=$From" );
               //Falta Campos Obligatorios
      exit();
    }

    if( @$_POST{'PNuevo'} !== $_POST{'PVer'} )
    {
      header( "Location: MensajeError.php?Errno=2208&Lang=$Lang&From=$From" );
               //Contraseñas No son Egual
      exit();
    }

    if( !IsPWDSeguro( $_POST{'PNuevo'} ) )
    {
      header( "Location: MensajeError.php?Errno=2209&Lang=$Lang&From=$From" );
               //PWD no está Seguro
      exit();
    }

    $PWD = md5( $_POST{'PNuevo'} );

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2206&Lang=$Lang&From=$From" );
      exit();
    }
    $Query = "update Vendedores set PWD = '{$PWD}', Fecha = CURDATE()
                  where UserID = {$_POST{'UserID'}}";


    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2210&Lang=$Lang&From=$From" );
               //No puede insert PWD
      exit();
    }

    unlink( $_POST{'FileName'} );

    $Block = "   <p class=\"SubTitleFont\" style=\"font-weight:bold;
                    color:#0000aa; text-align:center;\">";
    if( $Lang == ' en' )
      $Block .=   "-=&nbsp;Cambio de Tu Contrase&ntilde;a&nbsp;=-
                   <br />
                   &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    else
      $Block .=   "-=&nbsp;Password Change&nbsp;=-
                   <br />
                   CONFIRMED!";
    $Block .=     "<br />
                 </p>";
  }  
  elseif( @$_POST{'Accion'} == "EnviarLiga" )
  {
    if( !@$_POST{'Correo'} )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2202&Lang=$Lang&From=$From" );
      exit();
    }

    require_once('recaptchalib.php');
    $publickey = $CaptchaPubKey;
    $privatekey = $CaptchaPrivKey;
    $resp = recaptcha_check_answer ($privatekey,
                                    $_SERVER{'REMOTE_ADDR'},
                                    $_POST{'recaptcha_challenge_field'},
                                    $_POST{'recaptcha_response_field'});

    if (!$resp->is_valid)
    {
      header( "Location: MensajeError.php?Errno=2203&Lang=$Lang&From=$From" );
               //Captcha Validation Error
      exit();
    }

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2201&Lang=$Lang&From=$From" );
      exit();
    }

    $Query = "select UserID, Nombres, Login from Vendedores 
                     where Correo = '{$_POST{'Correo'}}'";
    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2204&Lang=$Lang&From=$From" );
               //No Puede select
      exit();
    }

    if( ( mysqli_num_rows( $QueryRes ) ) != 1 )
    {
      header( "Location:MensajeError.php?Errno=2205&Var={$_POST{'Correo'}}" );
      // No Tengo este E-Mail
      exit();
    }

    $Pedidor = mysqli_fetch_array( $QueryRes );

    $LigaBase = md5( ( time() . $Pedidor{'UserID'} ) );
    $LigaArchivo = "TianguisCabalMaint/{$LigaBase}.php";
    $LigaURL = "https://www.imat.com/linuxcabal.org/{$LigaArchivo}?Lang=$Lang&From=$From&DD=../";

    copy( "TianguisCabalCambiarPWDHead.php", $LigaArchivo );
    $Handle = fopen( "/var/www/html/linuxcabal.org/{$LigaArchivo}", "a+" );
    $CambiarPWDBody = "
      <?php
        echo( \"<p class=\\\"SubTitleFont\\\" style=\\\"text-align:center;\\\">
                  Del Usuario {$Pedidor{'Nombres'}}
                </p>
                <form method=\\\"post\\\" action=\\\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&From=Tianguis\\\">
                  <p class=\\\"LargeTextFont\\\"
                     style=\\\"text-align:center;\\\">
                    Contrase&ntilde;a Nueva: <input type=\\\"password\\\"
                                                    name=\\\"PNuevo\\\"
                                                    size=\\\"25\\\"
                                                    maxlength=\\\"50\\\" />
                  </p>
                  <p class=\\\"LargeTextFont\\\"
                     style=\\\"text-align:center;\\\">
                    Verificar Contrase&ntilde;a: <input type=\\\"password\\\"
                                                        name=\\\"PVer\\\"
                                                        size=\\\"25\\\"
                                                        maxlength=\\\"50\\\" />
                  </p>
                  <p class=\\\"LargeTextFont\\\"
                     style=\\\"text-align:center;\\\">
                    <br />
                    <input type=\\\"hidden\\\" name=\\\"Accion\\\"
                           value=\\\"CambiaPWD\\\" />
                    <input type=\\\"hidden\\\" name=\\\"FileName\\\"
                           value=\\\"/var/www/html/linuxcabal.org/{$LigaArchivo}\\\" />
                    <input type=\\\"hidden\\\" name=\\\"UserID\\\"
                           value={$Pedidor{'UserID'}} />
                    <input type=\\\"submit\\\" name=\\\"Submit\\\"
                           style=\\\"font-weight:bold\\\"
                           value =\\\"A P L I C A R\\\" />
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type=\\\"reset\\\" value=\\\"Reset\\\" />
                  </p>
                </form>\" );
      ?>";
    fwrite( $Handle, $CambiarPWDBody );
    $CambiarPWDFoot = "<div class=\"content-main-after\">
                        <img src=\"../images/LowerCornerLeft.gif\"
                             alt=\"LowerCornerLeft.gif\" />
                      </div>
                    </div>
                    <div class=\"content-footer\">
                      <?php
                        include( \"../includes/incCommonFooter.php\" );
                      ?>
                    </div>
                  </div>
                </body>
              </html>";
    fwrite( $Handle, $CambiarPWDFoot );

    $Mensaje ="Saludos {$Pedidor{'Nombres'}}\r\n
               Recibimos una petición a cambiar la contraseña del usuario
               qeu tiene tu E-Mail\r\n
               Si quieres cambiar la contraseña del usuario
               \"{$Pedidor{'Login'}}\", haga click en la URL:\r\n
               {$LigaURL}\r\n
               \r\n
               Este URL expire en 1 (una) hora\r\n
               \r\n
               Gracias para usar el TianguisCabal\r\n\r\n
               webmaster@LinuxCabal.org";



    $Headers = "From: webmaster@LinuxCabal.org";

    $Resultado = mail( "{$_POST{'Correo'}}", 'Recuperar TianguiCabal Acceso',
                        $Mensaje, $Headers );

    if( $Resultado )
      $Block =    "<p class=\"SubTitleFont\" style=\"text-align:center;\">
                     Correo enviado a {$_POST{'Correo'}} con &eacute;xito";
    else
      $Block =    "<p class=\"SubTitleFont\" style=\"text-align:center;\">
                     Envio de Correo a {$_POST{'Correo'}} fall&oacute;";
  }
  else
  {
    if( $Lang == 'en' )
      $Block .= "<p class=\"LargeTextFont\" style=\"text-align:center;\">
                   It is not possible to recover your existing password
                   <br />
                   but,
                   <br />
                   we can help you in re-assigning a new password.
                 </p>
                 <p class=\"LargeTextFont\" style=\"text-align:center;\">
                   Use the form below to send us your E-Mail address.
                   <br />
                   If it corresponds to an E-Mail address in our database
                   <br />
                   we will send you a link which will enable you
                   <br />
                   to assign a new password to your account
                 </p>";
    else
      $Block .= "<p class=\"LargeTextFont\" style=\"text-align:center;\">
                   No es posible para nosotros a recuperar tu contrase&ntilde;a,
                   <br />
                   pero,
                   <br />
                   podemos facilitarte a re-asignar una contrase&ntilde;a nueva.
                 </p>
                 <p class=\"LargeTextFont\" style=\"text-align:center;\">
                   Usa la forma que sigue para enviarnos la
                   <br />
                   direcci&oacute;n de tu correo electr&oacute;nico.
                   <br />
                   Si el corresponde a una direcci&oacute;n en nuestra base de
                   datos,
                   <br />
                   vamos enviarte una liga que
                   <br />
                   te permitir&aacute; asignar una contrase&ntilde;a nueva.
                 </p>";

    $Block .=   "<form method=\"post\" action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=$From\">
                   <table cellspacing=\"20\" cellpadding=\"0\"
                          style=\"margin: 2%; width:95%;\">
                     <tr>
                       <td align = \"center\">
                         E-Mail: <input type=\"text\" name=\"Correo\"
                                        size=\"25\" maxlength=\"50\" />
                         <br />
                       </td>
                     </tr>";

    require_once('recaptchalib.php');
    $publickey = $CaptchaPubKey;
    $Block .=       "<tr>
                       <td align=\"center\">"
                       . recaptcha_get_html($publickey, $use_ssl=true )
                    . "</td>
                     </tr>
                     <tr>
                       <td align=\"center\">
                         <input type=\"hidden\" name=\"Accion\"
                                value=\"EnviarLiga\" />
                         <input type=\"submit\" name=\"Submit\"
                                style=\"font-weight:bold\"";
    if( $Lang == 'en' )
      $Block .= "               value =\"S E N D\" />";
    else
      $Block .= "               value =\"E N V I A R\" />";
    $Block .= "          &nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=\"reset\" value=\"Reset\" />
                       </td>
                     </tr>
                   </table>
                 </form>";
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
            echo( "Recover Access to My Account" );
          else
            echo( "Recuperar Acceso a Mi Cuenta" );
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

