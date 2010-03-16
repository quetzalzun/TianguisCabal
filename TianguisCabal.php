<?php
  include( "./includes/funcPrintHTMLHeader.php" );
  PrintHTMLHeader();
?>
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
             if( $Lang == 'es' )
               print( "El TianguisCabal" );
             else
               print( "The TianguisCabal" );
           ?>
          </p>
        </div>
      </div>
      <div class="content-main">

        <?php
          if( $Lang == 'es' )
            print( "<p class=\"SubTitleFont\" style=\"text-align: center;\">
                      En los <a href=\"InstallFests.php?Lang=$Lang&amp;From=Eventos\">Festivales
                      de Instalaci&oacute;n</a> semanales.
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      Mira tambi&eacute;n
                      <br />
                      <a href=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis\">El TianguisCabal
                        en linea</a>
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      &iexcl; Estos servicios son totalmente gratuitos!
                      <br />
                      &iexcl; No te cobra comisiones!
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      <a href=\"https://www.imat.com/linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=Login\">Click
                        AQU&Iacute; para registrar como un vendador</a>
                      <br />
		      o para manejar tus petitiones de compra/venta/intercambio
                    </p>
                    <p class=\"LargeTextFont\" style=\"text-align: center;\">
                      Te envitamos a que participes trayendo/publicando
                      productos,
                      <br />
                      preferentemente de inform&aacute;tica,
                      <br />
                      que quieras vender, comprar e intercambiar.
                    </p>" );
          else
            print( "<p class=\"SubTitleFont\" style=\"text-align: center;\">
                      During our weekly <a href=\"InstallFests.php?Lang=$Lang&amp;From=Eventos\">Install Fests</a>
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      Also Visit
                      <br />
                      <a href=\"TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis\">The TianguisCabal
                        on line</a>
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      These services are totally Free!
                      <br />
                      We will not charge you any commission whatsoever!
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      <a href=\"https://www.imat.com/linuxcabal.org/TianguisCabalOnLine.php?Lang=$Lang&amp;From=Tianguis&amp;Accion=Login\">Click
                        HERE to register as a seller</a>
                      <br />
		      or to manage your Buy/Sell/Trade ads
                    </p>
                    <p class=\"LargeTextFont\" style=\"text-align: center;\">
                      We invite you to bring along and/or publish items, 
                      <br />
                      prefereably computer related,
                      <br />
                      which you wish to buy, sell or trade.
                    </p>" );
          print( "  <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      <img src=\"TianguisCabalImages/P01.jpg\"
                           alt=\"P01.jpg\" />
                    </p>
                    <p class=\"SubTitleFont\" style=\"text-align: center;\">
                      <img src=\"TianguisCabalImages/P02.jpg\"
                           alt=\"P02.jpg\" />
                    </p>
                    <p style=\"text-align: center;\">
                      <img src=\"TianguisCabalImages/P03.jpg\"
                           alt=\"P03.jpg\" />
                    </p>" );
          if( $Lang == 'en' )
            print( "<p style=\"text-align: center;\">
                      LinuxCabal assumes no responsibility what-so-ever for
                      the articles purchased, leaving such responsibility in
                      the hands of the sellers and buyers.
                    </p>" );
          else
            print("<p style=\"text-align: center;\">
                     LinuxCabal no se hace responsable; cada usuario
                     ser&aacute; responsable de sus compras y/o ventas.
                    </p>" );
          print( "  <p style=\"text-align: center; font-weight:bold;\"
                       class=\"LargeTextFont\">
                      CAVEAT EMPTOR
                    </p>" );
          if( $Lang == 'en' )
            print( "<p class=\"SubTitleFont\" style=\"text-align:center;\">
                      <br />
                      Come to &quot;LEARN&quot;, &quot;TEACH&quot; and
                      &quot;SOCIALIZE&quot;
                   </p>" );
          else
            print( "<p class=\"SubTitleFont\" style=\"text-align: center\">
                      <br />
                      Ven a &quot;APRENDER&quot;, &quot;ENSE&Ntilde;AR&quot;
                      y &quot;SOCIALIZAR&quot;
                    </p>" );
        ?>
        <div class="content-main-after">
          <img src="images/LowerCornerLeft.gif" alt="LowerCornerLeft.gif" />
        </div>
      </div>
      <div class="content-footer">
        <?php
          include( "./includes/incCommonFooter.php" );
        ?>
        <br />
      </div>
    </div>
  </body>
</html>
