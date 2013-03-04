<?php
    session_start();
    $sessionUsername = $_SESSION['username'];
    $sessionuserId = $_SESSION['userid'];
    if (!isset($_SESSION['username']) || !isset($_SESSION['userid'])) {
        $location = "Location: http://" . $_SERVER['SERVER_NAME'] . ":8888/BA_Automation/login.php";
        header($location);
        exit();
    }
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Test Page</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/main.css"/>
        <link rel="stylesheet" href="css/smoothness/jquery-ui-1.9.2.custom.css" />
        <link rel="stylesheet" href="css/flexigrid/flexigrid.css"/>
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body onload="getAllData()">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div id="wrap" class="wrap border1black">
            <div id="header">
                <table>
                    <tr>
                        <td><img alt="GroupOn"
                                 src="https://assets.zendesk.com/system/logos/2019/1718/groupon-logo.png?1355774765"
                                 class="padding10" /></td>
                        <td><img alt=""
                                 src="https://assets.zendesk.com/images/logo-delimiter.png?1355774765"
                                 class="" /></td>
                        <td class="logo">GROUPON Deal Bank System</td>
                        <td width="820px" style="vertical-align: bottom;">
                            <a href="#" onclick="logout()" class="alignRight logout">
                                <span class="logout">&nbsp;</span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1" onclick="getAllData()">Data Entry Page</a></li>
                    <li><a href="#tabs-2" onclick="getAllDealsData()">All Data</a></li>
                    <li><a href="#tabs-3">Aenean lacinia</a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    include 'html/DataEntry.html';
                    ?>
                </div>
                <div id="tabs-2">
                    <?php
                    include 'html/DealData.html';
                    ?>
                </div>
                <div id="tabs-3">
                    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse
                        potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque
                        rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante.
                        Class aptent taciti sociosqu ad litora torquent per conubia nostra,
                        per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim
                        commodo pellentesque. Praesent eu risus hendrerit ligula tempus
                        pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a,
                        lacus.</p>
                    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at,
                        semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra
                        justo vitae neque. Praesent blandit adipiscing velit. Suspendisse
                        potenti. Donec mattis, pede vel pharetra blandit, magna ligula
                        faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque.
                        Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi
                        lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean
                        vehicula velit eu tellus interdum rutrum. Maecenas commodo.
                        Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae
                        lectus hendrerit hendrerit.</p>
                </div>
            </div>
            <div>
                <?php
                include 'html/addMerchantDialog.html';
                ?>
            </div>
        </div>
        <script
        src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script type="text/javascript" src="js/ui/jquery-ui.js"></script>
        <script src="js/plugins.js"></script>
        <script type="text/javascript" src="js/ComboBox.js"></script>
        <script type="text/javascript" src="JS/accounting.min.js"></script>
        <script type="text/javascript" src="js/flexigrid/flexigrid.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
                s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>