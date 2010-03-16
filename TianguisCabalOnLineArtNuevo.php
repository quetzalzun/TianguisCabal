<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="FixAnotherMSIEBug.xsl"?>
<?php
  include( "includes/TianguisCabalFunctions.inc" );
  include( "includes/TianguisCabalEnviron.inc" );
  include( "/etc/TianguisCabal.inc" ); 

  if( ( @$_POST{'Submit'} === "A P L I C A R" )
       && ( @$_POST{'op'} != "AgregaArticulo" )
       && ( @$_POST{'op'} != "EditArticulo"   ) )
    if( !$_POST{'ActionArtID'} || !$_POST{'CheckArtID'} )
    {
      header( "location:MensajeError.php?Errno=2110&Lang=$Lang&From=$From" );
      exit();
    }

  if( ( @$_POST{'ActionArtID'} === "BorraArtID" ) && @$_POST{'CheckArtID'} )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2101&Lang=$Lang&From=$From" );
      exit();
    }

    $Query = "delete from Ventas where VentaID = {$_POST{'CheckArtID'}}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2102&Lang=$Lang&From=$From" );
      exit();
    }

    mysqli_close( $Conn );

    $Block = "   <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\"
                         style=\"width:100%; text-align:center;\">
                   <tr style=\"background:url(images/logoBG.jpg);\">
                     <td>
                       <p style=\"text-align:center;\"
                          class=\"SubTitleFont\">";
    if( $Lang == 'en' )
      $Block .= "        -=&nbsp;Deletion of Article";
    else
      $Block .= "        -=&nbsp;Borrado de Art&iacute;culo";
    $Block .= "          #{$_POST{'CheckArtID'}}&nbsp;=-
                       </p>
                       <p style=\"text-align:center; color:#0000aa;\"
                          class=\"SubTitleFont\">";
    if( $Lang == 'en' )
      $Block .= "        CONFIRMED!";
    else
      $Block .= "        &iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .= "        </p>
                     </td>
                   </tr>
                   <tr>
                     <td>
                       <form method=\"post\" action=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=$From\">
                         <p style=\"text-align: center;\">
                           <input  type=\"hidden\" name=\"Accion\" 
                                   value=\"Entra\" /> 
                           <input type=\"submit\" name=\"Submit\"
                                  class=\"SubTitleFont\"
                                  style=\"font-weight:bold;\"";
    if( $Lang == 'en' )
      $Block .= "                 value=\"Click here to Continue\" />";
    else
      $Block .= "                 value=\"Presiona aqu&iacute; para Continuar\" />";
    $Block .= "          </p>
                       </form>
                     </td>
                   </tr>
                 </table>";
  }
  else if( ( @$_POST{'op'} === "AgregaArticulo" )
         || ( @$_POST{'op'} === "EditArticulo" ) )
  {
    if(  !$_POST{'Articulo'} || !$_POST{'Descripcion'}
      || !$_POST{'Cantidad'} || !$_POST{'Calidad'} )
    {
      header( "location:MensajeError.php?Errno=2109&Lang=$Lang&From=$From" );
      exit();
    }

    $Articulo = htmlspecialchars( $_POST{'Articulo'}, ENT_QUOTES, "UTF-8" );
    $Descripcion = htmlspecialchars( $_POST{'Descripcion'}, ENT_QUOTES, "UTF-8" );

    if( IsValidInt( "Cantidad", $_POST{'Cantidad'}, 2 ) )
      $Cantidad = $_POST{'Cantidad'};

    if( IsValidDouble( "Precio", $_POST{'Precio'}, 8 ) )
      $Precio = $_POST{'Precio'};

    $Calidad  = $_POST{'Calidad'};

    if( !$_POST{'LinkFoto'} )
      $LinkFoto = "";
    else
      $LinkFoto = htmlspecialchars( $_POST{'LinkFoto'}, ENT_QUOTES, "UTF-8" );

    $CompraVenta = $_POST{'CompraVenta'};

    if( @$_POST{'InterCambiar'} && !@$_POST{'CambiarParaQue'} )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2118&Lang=$Lang&From=$From" );
      exit();
    }
    
    if( !@$_POST{'InterCambiar'} )
      $InterCambiar = "";
    else
      $InterCambiar = $_POST{'InterCambiar'};

    if( @$_POST{'CambiarParaQue'} && !@$_POST{'InterCambiar'} )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2119&Lang=$Lang&From=$From" );
      exit();
    }
    
    if( !@$_POST{'CambiarParaQue'} )
      $CambiarParaQue = "";
    else
      $CambiarParaQue = htmlspecialchars( $_POST{'CambiarParaQue'}, ENT_QUOTES, "UTF-8" );

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );
    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2104&Lang=$Lang&From=$From" );
      exit();
    }

    if( @$_POST{'op'} === "EditArticulo" )
      $Query = "update Ventas set Fecha = CURDATE(),
                                  Articulo='{$Articulo}',
                                  Cantidad = {$Cantidad},
                                  Descripcion = '{$Descripcion}',
                                  Calidad = '{$Calidad}',
                                  Precio = {$Precio},
                                  LinkFoto = '{$LinkFoto}',
                                  CompraVenta = '{$CompraVenta}',
                                  InterCambiar = '{$InterCambiar}',
                                  CambiarParaQue = '{$CambiarParaQue}',
                                  Categoria = '{$_POST{'Categoria'}}'
                              where VentaID = {$_GET{'ARTID'}}";
    else
      $Query = "insert into Ventas Values ( NULL, {$_SESSION{'UID'}},
              CURDATE(), '{$Articulo}', {$Cantidad}, '{$Descripcion}',
              '{$Calidad}', {$Precio}, '{$LinkFoto}', '{$CompraVenta}',
              '{$InterCambiar}', '{$CambiarParaQue}', '{$_POST{'Categoria'}}' )";
    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2105&Lang=$Lang&From=$From" );
      exit();
    }

    mysqli_close( $Conn );

    $Block =    "<p style=\"font-weight:bold; text-align:center;\"
                    class=\"SubTitleFont\">";
    if( @$_POST{'op'} === "EditArticulo" )
    {
      if( $Lang == 'en' )
        $Block .= "-=&nbsp;Update of the specifications of Article";
      else
        $Block .= "-=&nbsp;Actualizaci&oacute;n de Art&iacute;culo";
      $Block .= "  #{$_GET{'ARTID'}}&nbsp;=-";
    }
    else
    {
      if( $Lang == 'en' )
        $Block .= "-=&nbsp;Addition of Your New Article&nbsp;=-";
      else
        $Block .= "-=&nbsp;Agregaci&oacute;n de Tu Art&iacute;culo Nuevo&nbsp;=-";
    }
    $Block .= "  </p>
                 <p style=\"font-weight:bold; color:#0000aa;
                    text-align:center;\" class=\"SubTitleFont\">";
    if( $Lang == 'en' )
      $Block .= "             CONFIRMED!";
    else
      $Block .= "             &iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .= "  </p>
                 <p style=\"text-align:center;\" class=\"LargeTextFont\">
                   {$_POST{'Articulo'}}
                 </p>
                 <form method=\"post\" action=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=$From\">
                   <p style=\"text-align: center;\">
                      <input  type=\"hidden\" name=\"Accion\" 
                              value=\"Entra\" /> 
                      <input type=\"submit\" name=\"Submit\"
                             class=\"SubTitleFont\"
                             style=\"font-weight:bold; color:#0000aa;\"";
    if( $Lang == 'en' )
      $Block .= "            value=\"Click Here to Continue\" />";
    else
      $Block .= "            value=\"Presiona aqu&iacute; para Continuar\" />";
    $Block .= "    </p>
                 </form>";
  }
  else
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, "localhost", $ClaseRO, $AccessTypeRO,
                         'TianguisCabal', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2108&Lang=$Lang&From=$From" );
      exit();
    }

    if( @$_POST{'ActionArtID'} == "EditArtID" )
    {
      $Query = "select * from Ventas where VentaID = {$_POST{'CheckArtID'}}";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2011&Lang=$Lang&From=$From" );
        exit();
      }

      $EditArtID = mysqli_fetch_array( $QueryRes );
      mysqli_free_result( $QueryRes );
    }

    $Block = "   <p style=\"text-align:center;font-weight:bold;\"
                    class=\"SubTitleFont\">";

    if( @$_POST{'CheckArtID'} )
    {
      if( $Lang == 'en' )
        $Block .= "  -=&nbsp;Edit Article Specifcations&nbsp;=-";
      else
        $Block .= "  -=&nbsp;Editar Arti&iacute;culo&nbsp;=-";
    }
    else
    {
      if( $Lang == 'en' )
        $Block .= "  -=&nbsp;Add a New Article&nbsp;=-";
      else
        $Block .= "  -=&nbsp;Agrega un Art&iacute;culo nuevo&nbsp;=-";
    }

    $Block .= "  </p>
                 <form action=\"{$_SERVER{'PHP_SELF'}}?Lang=$Lang&amp;From=$From";

    if( @$_POST{'CheckArtID'} )
      $Block .= "  &amp;ARTID={$_POST{'CheckArtID'}}";

    $Block .= "    \" method =\"post\">
                   <table width=\"95%\" cellspacing=\"0\" cellpadding=\"5\"
                          border=\"0\" style=\"margin:2%;\">
                     <tr>
                       <td>";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Article";
    else
      $Block .= "        <em>*</em> Art&iacute;culo";
    $Block .= "        </td>
                       <td colspan=\"4\">
                         <input type=\"text\" name=\"Articulo\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'Articulo'}}\" ";

    $Block .= "                 size=\"25\" maxlength=\"125\" />
                       </td>
                       <td style=\"text-align:center;
                                font-weight:bold;\">";

    if( @$_POST{'CheckArtID'} )
      $Block .= "        ID #{$_POST{'CheckArtID'}}";
    else
    {
      if( $Lang == 'en' )
        $Block .= "        New Article";
      else
        $Block .= "        Art&iacute;culo Nuevo";
    }
    $Block .= "        </td>
                     </tr>
                     <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Descripciption";
      else
        $Block .= "      <em>*</em> Descripci&oacute;n";
      $Block .= "      </td>
                       <td colspan=\"5\">
                         <input type=\"text\" name=\"Descripcion\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'Descripcion'}}\" ";

    $Block .= "                 size=\"50\" maxlength=\"200\" />
                       </td>
                     </tr>
                     <tr>
                       <td>";
      if( $Lang == 'en' )
        $Block .= "      <em>*</em> Quality";
      else
        $Block .= "      <em>*</em> Calidad";
      $Block .= "      </td>
                       <td colspan=\"5\">
                         <input type=\"radio\" name=\"Calidad\" ";

    if( @$EditArtID{'Calidad'} == "Excelente" )
      $Block .= "               checked=\"checked\"  ";

    $Block .= "                 value=\"Excelente\" />";
    if( $Lang == 'en' )
      $Block .= "        Excelent";
    else
      $Block .= "        Excelente";
    $Block .= "          <input type=\"radio\" name=\"Calidad\" ";

    if( @$_POST{'CheckArtID'} )
    {
      if( @$EditArtID{'Calidad'} == "Muy Buena" )
        $Block .= "             checked=\"checked\" ";
    }
    else
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Muy Buena\" />";
    if( $Lang == 'en' )
      $Block .= "        Very Good";
    else
      $Block .= "        Muy Buena";
    $Block .= "          <input type=\"radio\" name=\"Calidad\" ";

    if( @$EditArtID{'Calidad'} == "Buena" )
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Buena\" />";
    if( $Lang == 'en' )
      $Block .= "        Good";
    else
      $Block .= "        Buena";
    $Block .= "          <input type=\"radio\" name=\"Calidad\" ";

    if( @$EditArtID{'Calidad'} == "Regular" )
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Regular\" />
                         Regular
                         <input type=\"radio\" name=\"Calidad\" ";

    if( @$EditArtID{'Calidad'} == "Partes" )
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Partes\" />";
    if( $Lang == 'en' )
      $Block .= "        Parts";
    else
      $Block .= "        Partes";
    $Block .= "        </td>
                     </tr>";

    $Query = "select Categoria from Categoria";
    if( !$CatRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2120&Lang=$Lang&From=$From" );
      exit();
    }
    $Block .= "      <tr>
                       <td>";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Category";
    else
      $Block .= "        <em>*</em> Categor&iacute;a";
    $Block .= "        </td>
                       <td>
                         <select name=\"Categoria\" size=\"1\">";

    while( $Cat = mysqli_fetch_array( $CatRes ) )
    {
      $Block .= "          <option";

      if( @$EditArtID{'Categoria'} == $Cat{'Categoria'} )
        $Block .= "                selected=\"selected\">";
      else
        $Block .= "               >";

      $Block .= "{$Cat{'Categoria'}}</option>";
    }
    $Block .= "          </select>
                       </td>
                       <td style=\"text-align:right;\">";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Quantity&nbsp;";
    else
      $Block .= "        <em>*</em> Cantidad&nbsp;";
    $Block .= "        </td>
                       <td style=\"text-align:left;\">
                         <input type=\"text\" name=\"Cantidad\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'Cantidad'}}\" ";

    $Block .= "                 size=\"2\" maxlength=\"2\" />
                       </td>
                       <td style=\"text-align:right;\">";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Price&nbsp;&nbsp;\$";
    else
      $Block .= "        <em>*</em> Precio&nbsp;&nbsp;\$";
    $Block .= "        </td>
                       <td>
                         <input type=\"text\" name=\"Precio\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'Precio'}}\" ";

    $Block .= "                 size=\"8\" maxlength=\"8\" />
                       </td>
                     </tr>
                     <tr style=\"text-align:center;\">
                       <td>
                         &nbsp;
                       </td>
                       <td colspan=\"3\">
                         <input type=\"radio\" name=\"CompraVenta\" ";

    if( @$_POST{'CheckArtID'} )
    {
      if( @$EditArtID{'CompraVenta'} == "Se Vende" )
        $Block .= "             checked=\"checked\" ";
    }
    else
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Se Vende\" />";
    if( $Lang == 'en' )
      $Block .= "        For Sale";
    else
      $Block .= "        Se Vende";
    $Block .= "          <input type=\"radio\" name=\"CompraVenta\" ";

    if( @$EditArtID{'CompraVenta'} == "Quiero Comprar" )
      $Block .= "               checked=\"checked\" ";

    $Block .= "                 value=\"Quiero Comprar\" />";
    if( $Lang == 'en' )
      $Block .= "        Seeking to Purchase";
    else
      $Block .= "        Quiero Comprar";
    $Block .= "        </td>
                       <td style=\"text-align:right;\">";
    if( $Lang == 'en' )
      $Block .= "        Seeking to Trade";
    else
      $Block .= "        Quiero Intercambiar";
    $Block .= "        </td>
                       <td style=\"text-align:left;\">
                         <input type=\"checkbox\" name=\"InterCambiar\"
                                value=\"InterCambiar\"";

    if( @$EditArtID{'InterCambiar'} )
      $Block .= "               checked=\"checked\"";

    $Block .= "                 />
                       </td>
                     </tr>
                     <tr>
                       <td>";
    if( $Lang == 'en' )
      $Block .= "        For What?&nbsp;";
    else
      $Block .= "        &iquest;De qu&eacute;?&nbsp;";
    $Block .= "        </td>
                       <td colspan=\"5\">
                         <input type=\"text\" name=\"CambiarParaQue\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'CambiarParaQue'}}\" ";
    $Block .= "                 size=\"50\" maxlength=\"200\" />
                         <br />
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if( $Lang == 'en' )
      $Block .= "        ( Field Required
                         if &#8220;Seeking to Trade&#8221; is specified)";
    else
      $Block .= "        ( Obligatorio
                         si &#8220;Quiero InterCambiar&#8221; est&aacute;
                         elegido )";
    $Block .= "        </td>
                     </tr>
                     <tr>
                       <td>";
    if( $Lang == 'en' )
      $Block .= "        Seller";
    else
      $Block .= "        Vendedor";
    $Block .= "        </td>";

    $Query = "select CONCAT( Nombres, ' ', ApellidoPaterno, ' ', ApellidoMaterno ) Nombre from Vendedores where UserID = {$_SESSION{'UID'}}";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2112&Lang=$Lang&From=$From" );
      exit();
    }

    $NombreID = mysqli_fetch_array( $QueryRes );

    $Block .= "        <td colspan=\"5\">
                         <input type=\"text\" name=\"Dummy\"
                                readonly=\"readonly\"
                                value=\"{$NombreID{'Nombre'}}\"
                                size=\"50\" maxlength=\"50\" />
                       </td>
                     </tr>
                     <tr>
                       <td colspan=\"6\">
                         <p style=\"text-align:center;\">";

    if( @$_POST{'CheckArtID'} && $EditArtID{'LinkFoto'} ) 
      $Block .= "          <img src=\"http://{$EditArtID{'LinkFoto'}}\"
                                style=\"border-style:solid;
                                        border-width:medium;\"
                                alt=\"ProdFoto\" />";
    else
      $Block .= "          <img src=\"TianguisCabalImages/DefaultImage.gif\"
                                style=\"border-style:solid;
                                        border-width:medium;\"
                                alt=\"DefaultImage\" />";

    $Block .= "          </p>
                       </td>
                     </tr>
                     <tr>
                       <td colspan=\"6\" style=\"text-align:center;\">";
    if( $Lang == 'en' )
      $Block .= "        URL of the Photo ( Without the http:// )";
    else
      $Block .= "        URL de la Foto ( Sin http:// )";
    $Block .= "        </td>
                     </tr>
                     <tr>
                       <td style=\"text-align:right; font-weight:bold;\"
                           class=\"LargeTextFont\">
                         &nbsp;http://
                       </td>
                       <td colspan=\"5\">
                         <input type=\"text\" name=\"LinkFoto\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"{$EditArtID{'LinkFoto'}}\" ";

    $Block .= "                 size=\"50\" maxlength=\"125\" />
                       </td>
                     </tr>

                     <tr>
                       <td colspan=\"6\" style=\"text-align:center;
                           font-weight:bold;\">";
    if( $Lang == 'en' )
      $Block .= "        <em>*</em> Required Fields";
    else
      $Block .= "        <em>*</em> Campos Obligatorios";
    $Block .= "        </td>
                     </tr>

                     <tr>
                     <td colspan=\"6\" style=\"text-align:center;\">
                       <br />
                         <input type =\"hidden\" name=\"op\" ";

    if( @$_POST{'CheckArtID'} )
      $Block .= "               value=\"EditArticulo\" />";
    else
      $Block .= "               value=\"AgregaArticulo\" />";
    $Block .= "          <input type=\"submit\" name=\"Submit\"";
    if( $Lang == 'en' )
      $Block .= "               value =\"A P P L Y\"";
    else
      $Block .= "               value =\"A P L I C A R\"";
    $Block .= "                 style=\"font-weight:bold\" />
                       </td>
                     </tr>
                   </table>
                 </form>
                 <p>
                   <a href=\"TianguisCabalOnLine.php?LOC=DeMira&amp;Lang=$Lang&amp;From=$From\">";
    if( $Lang == 'en' )
      $Block .= "&nbsp;&nbsp;Menu&nbsp;&nbsp;</a>";
    else
      $Block .= "&nbsp;&nbsp;Men&uacute;&nbsp;&nbsp;</a>";
    $Block .= "    <br />
                   <a href=\"TianguisCabalOnLine.php?Accion=LogOut&amp;Lang=$Lang&amp;From=$From\">&nbsp;&nbsp;LogOut&nbsp;&nbsp;</a>
                 </p>";
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
            TianguisCabal en Linea
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
              print( "You may download the <a href=\"TianguisCabalOnLine.tar.bz\">Source Code of TianguisCabalOnLine</a> here" );
            else
              print( "&iexcl;Puedes descargar el <a href=\"TianguisCabalOnLine.tar.bz\">Codigo Fuente de TianguisCabalOnLine</a> aqu&iacute;" );
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
