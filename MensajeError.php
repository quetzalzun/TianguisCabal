<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="FixAnotherMSIEBug.xsl"?>
<?php
  include( "includes/TianguisCabalROEnviron.inc" );
  // Este Archivo: MensajeError.php

  include( "includes/TianguisCabalROEnviron.inc");
  $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold; color:#0000aa;
               text-align:center;\">";
  switch( $_GET{'Errno'} )
  {
    case 1001:
      $Block .= "&iexcl;Faltan EMail!
                        <br />";
      break;
    case 1002:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 1007:
      $Block .= "ERROR de sistema
                        <br />
                        Envi&eacute; notificaci&oacute;n a el
                        <br />
                        administrador de sistema
                        <br />
                        Disculpa
                        <br />";
      break;
    case 1008:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 1009:
      $Block .= "No puede Select Login y EMail en la base de datos
                        <br />";
      break;
    case 1012:
      $Block .= "ERROR de sistema
                        <br />
                        Envi&eacute; notificaci&oacute;n a el
                        <br />
                        administrador de sistema
                        <br />
                        Disculpa
                        <br />";
      break;
    case 1013:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 1015:
      $Block .= "ERROR de sistema
                        <br />
                        Envi&eacute; notificaci&oacute;n a el
                        <br />
                        administrador de sistema
                        <br />
                        Disculpa
                        <br />";
      break;
    case 1017:
      $Block .= "ERROR de sistema
                        <br />
                        Envi&eacute; notificaci&oacute;n a el
                        <br />
                        administrador de sistema
                        <br />
                        Disculpa
                        <br />";
      break;
    case 2001:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2002:
      $Block .= "No puedo select from Vendedores
                        <br />";
      break;
    case 2003:
      $Block .= "Un usuario con este nombre existe
                        <br />&iquest;Olvidaste tu contrase&ntilde;a?
                        <br />";
      break;
    case 2004:
      $Block .= "El \"Password Nuevo\" y el \"Verificar Password\" no son
                       igual
                        <br />";
      break;
    case 2005:
      $Block .= "No puedo insert into Vendedores
                        <br />";
      break;
    case 2006:
      $Block .= "No puedo insert/update Vendedores
                        <br />";
      break;
    case 2008:
      $Block .= "&iexcl;El Password no es aceptable!
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                        Tu Password debe tener:
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          6 caracteres, como minimo
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          que son una combinaci&oacute;n de
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          min&uacute;sculas,
                          <br />
                          MAY&Uacute;SCULAS,
                          <br />
                          N&uacute;meros
                          <br />
                          y
                          <br />
                          Signos de puctuaci&oacute;n.
                          <br />";
      break;
    case 2009:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2010:
      $Block .= "No puedo select UserID from Vendedores
                        <br />";
      break;
    case 2011:
      $Block .= "&iexcl;No tienes una cuenta!
                        <br />
                        Si quieres vender, debes registrarte como un vendedor
                        <br />";
      break;
    case 2012:
      $Block .= "No puedo select PWD from Vendedores
                        <br />";
      break;
    case 2013:
      $Block .= "&iexcl;No tienes una cuenta!
                        <br />
                        Si quieres vender, debes regiistrarte
                        <br />";
      break;
    case 2014:
      $Block .= "Entrada Denegada
                        <br />
                        Tu Login y/o tu password son invalidos
                        <br />";
      break;
    case 2015:
      $Block .= "No puede select PWD from Vendedores
                        <br />";
      break;
    case 2016:
      $Block .= "&iexcl;Tu contrase&ntilde;a est&aacute; invalido!
                        <br />";
      break;
    case 2017:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2018:
      $Block .= "No puedo select UserID from Vendedores
                        <br />";
      break;
    case 2019:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2020:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2021:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2022:
      $Block .= "No puedo update en Vendedores
                        <br />";
      break;
    case 2023:
      $Block .= "\"{$_GET{'Var'}}\" es tan corto.
                        <br />";
      break;
    case 2024:
      $Block .= "\"{$_GET{'Var'}}\" no es un n&uacute;mero de
                        Tel&eacute;fono valido.
                        <br />";
      break;
    case 2025:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2026:
      $Block .= "No puedo select UserID from Vendedores
                        <br />";
      break;
      case 2101:          // CatProdArtNuevo.php
        $Block .= "No puede conexionar a la base de datos
                          <br />";
        break;
      case 2102:
        $Block .= "No puede borrar el Art&iacute;culo - &iquest;Tal vez se
                          usa para otro registro?
                          <br />";
        break;
      case 2103:
        $Block .= "No puede commit borrado del Art&iacute;culo
                          &iquest;Tal vez se usa para otro registro?
                          <br />";
        break;
      case 2104:
        $Block .= "No puede conexionar a la base de datos
                          <br />";
        break;
      case 2105:
        $Block .= "No puede Insert/Update a la base de datos
                          &iquest;Tal vez el registro existe?
                          <br />";
        break;
      case 2106:
        $Block .= "No puede Commit Insert/Update a la base de datos
                          <br />";
        break;
      case 2107:
        $Block .= "No puede borrar Art&iacute;culo 1 - es parte del
                          sistema
                          <br />";
        break;
      case 2108:
        $Block .= "No puede conexionar a la base de datos
                          <br />";
        break;
      case 2109:
        $Block .= "Debe seleccionar TODOS los campos que se
                          marca con <B>*</B> para continuar
                          <br />";
        break;
      case 2110:
        $Block .= "Debe seleccionar un Art&iacute;culo y una
                          ACCI&Oacute;N para continuar
                          <br />";
        break;
      case 2111:
        $Block .= "No puede \"select * from Art&iacute;culo where ArtID...\"
                          <br />";
        break;
      case 2112:
        $Block .= "No puede \"Select * from Proveedor...\"
                          <br />";
        break;
      case 2113:
        $Block .= "No puede \"Select * from Linea...\"
                          <br />";
        break;
      case 2114:
        $Block .= "No puede \"Select * from Familia...\"
                          <br />";
        break;
      case 2115:
        $Block .= "No puede \"Select * from Sub Familia...\"
                          <br />";
        break;
      case 2116:
        $Block .= "No puede modificar Art&iacute;culo 1 - es parte del
                          sistema
                          <br />";
        break;
    case 2118:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />
                 Debe utilizar \"&iquest;Intercambiar para que?\" cuando eliges
                 \"Quiero Intercambiar\"
                        <br />";

      break;
    case 2119:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />
                 Debe elegir \"Quiero Intercambiar\" cuando utilizas \"&iquest;Intercambiar para que?\"
                        <br />";

      break;
    case 2120:
      $Block .= "No puedo select Categor&iacute;a
                        <br />";
      break;
    case 2121:
      $Block .= "No puedo select Categor&iacute;a
                        <br />";
      break;
    case 2122:
      $Block .= "No puedo verificar tu humanidad - Regressa para enviar las dos
                 Palabras ye te pide
                        <br />";
      break;
    case 2123:
      $Block .= "No puedo verificar tu humanidad - Regressa para enviar las dos
                 Palabras ye te pide
                        <br />";
      break;
    case 2124:
      $Block .= "No puedo select from Vendedores
                        <br />";
      break;
    case 2125:
      $Block .= "Tu Correo: &quot;{$_GET{'Var'}}&quot;, es &iexcl;&nbsp;INVALIDO&nbsp;!
                        <br />";
      break;
    case 2201:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2202:
      $Block .= "Falta E-Mail. No puedo continuar
                        <br />";
      break;
    case 2203:
      $Block .= "No puedo verificar tu humanidad - Regressa para enviar las dos
                 Palabras ye te pide
                        <br />";
      break;
    case 2204:
      $Block .= "No puedo select from Vendedores
                        <br />";
      break;
    case 2205:
      $Block .= "No tiene \"{$_GET{'Var'}}\" registrado
                        <br />";
      break;
    case 2206:
      $Block .= "No puedo conexionar a la base de datos: TianguisCabal
                        <br />";
      break;
    case 2207:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2208:
      $Block .= "El \"Password Nuevo\" y el \"Verificar Password\" no son
                       igual
                        <br />";
      break;
    case 2209:
      $Block .= "&iexcl;El Password no es aceptable!
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                        Tu Password debe tener:
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          6 caracteres, como minimo
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          que son una combinaci&oacute;n de
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          min&uacute;sculas,
                          <br />
                          MAY&Uacute;SCULAS,
                          <br />
                          N&uacute;meros
                          <br />
                          y
                          <br />
                          Signos de puctuaci&oacute;n.
                          <br />";
      break;
    case 2210:
      $Block .= "No puedo insert/update Vendedores
                        <br />";
      break;
    case 2211:
      $Block .= "Faltan Datos Obligatorios del sistema - Disculpa pero no
                 podemos continuar
                        <br />";
      break;
      case 3001:         // CatProdArtDesc.php
        $Block .= "No tiene registros para desplegar
                          <br />";
        break;
      case 3002:
        $Block .= "No puede conexionar a la base de datos
                          <br />";
        break;
      case 3003:
        $Block .= "No puede \"Select Proveedor from Proveedor...\"
                          <br />";
        break;
      case 3004:
        $Block .= "No puede \"Select Descrip from Linea...\"
                          <br />";
        break;
      case 3005:
        $Block .= "No puede \"Select Descrip from Familia...\"
                          <br />";
        break;
      case 3006:
        $Block .= "No puede \"Select Descrip from SubFamilia...\"
                          <br />";
        break;
      case 3007:
        $Block .= "No tiene registros para desplegar
                          <br />";
        break;
      case 6001:         // Functions.inc
        $Block .= "D&iacute;gitos en exceso en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6002:
        $Block .= "Caracteres ilegales en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6003:
        $Block .= "Caracteres en exceso en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6004:
        $Block .= "D&iacute;gitos en exceso antes del punto en
                          \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6005:
        $Block .= "Caracteres ilegales en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6006:
        $Block .= "D&iacute;gitos en exceso en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6007:
        $Block .= "No se permiten mas de un punto en \"{$_GET{'Var'}}\"
                          <br />";
        break;
      case 6008:
        $Block .= "D&iacute;gitos en exceso despu&eacute;s del punto
                          en \"{$_GET{'Var'}}\"
                          <br />";
        break;
    case 18003:
      $Block .= "\"{$_GET{'Var'}}\"No puede conexionar a la base de
                        datos: TianguisCabal
                        <br />";
      break;
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
                 FreeBSD" />
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
            -=&nbsp;Mensaje de
            <span style="color:#bb0000; text-decoration:blink;">
              ERROR
            </span>
            &nbsp;=-
            <br/><br />
          <?php
  print( "Error Numero: <em>{$_GET{'Errno'}}</em>" );
          ?>
          </p>
        </div>
      </div>
      <div class="content-main">
        <?php
          include( "./includes/incMenu.php" );
          echo( "$Block" );
        ?>
        </p>

         <p style="text-align: center;" class="LargeTextFont">
           Presiona el b&oacute;ton, en tu navegador,
           <br />
           para regresar a la ultima p&aacute;gina
           <br />
           para corregir el problema
         </p>
<?php
  session_start();
  if( @$_SESSION{'PHPSESSID'} )
    print("<p>
             <a href=\"TianguisCabalOnLine.php?LOC=DeMira&amp;Lang=$Lang&amp;From=Tianguis\">&nbsp;&nbsp;Men&uacute;&nbsp;&nbsp;</a>
             <br />
             <a href=\"TianguisCabalOnLine.php?Accion=LogOut&amp;Lang=$Lang&amp;From=Tianguis\">&nbsp;&nbsp;Log Out&nbsp;&nbsp;</a>
           </p>" );
?>
         <p style="text-align: center;">
           LinuxCabal no se hace responsable; cada usuario
           ser&aacute; responsable de sus compras y/o ventas.
         </p>
         <p style="text-align: center;">
           CAVEAT EMPTOR
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

