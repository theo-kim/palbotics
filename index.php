<!DOCTYPE html>
<html>
<head>
    <title>myPALBOTICS</title>
    <!-- BASE URL FOR HTML5 Navigation -->
    <base href="/">

    <!-- Web app declarations -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Icons -->
    <link rel="icon" type="image/png" href="site-resources/icons/mipmap-xxxhdpi/ic_launcher.png" />
    <link rel="apple-touch-icon" href="site-resources/icons/mipmap-xxxhdpi/ic_launcher_apple.png">
    <link rel="manifest" href="site-resources/mobile/manifest.json">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/site-resources/style/index.css">
    <link rel="stylesheet" type="text/css" href="/site-resources/js/dependencies/jquery/jquery-ui.min.css">
    <link rel="stylesheet" id="fa-style" href="/site-resources/font/fa/css/font-awesome.min.css">

    <!-- AngularJS -->
    <script src="site-resources/js/dependencies/angular/angular.min.js"></script>
    <script src="site-resources/js/dependencies/angular/angular-animate.min.js"></script>
    <script src="site-resources/js/dependencies/angular/angular-route.min.js"></script>

    <!-- Other pre-loaded JS -->
    <script src="site-resources/js/dependencies/jquery/jquery.js"></script>
    <script src="site-resources/js/dependencies/jquery/jquery-ui.min.js"></script>
    <script src="site-resources/js/mobileVerify.js"></script>

    <!-- Mobile Compatibility Check -->
    <script>
        if (window.mobilecheck() == false && screen.height < screen.width) {
            $("link[rel=stylesheet]:not(#fa-style)").attr({ href: "site-resources/style/index.css", });
        }
        else {
            $("link[rel=stylesheet]:not(#fa-style)").attr({ href: "site-resources/style/mobile.css", });
        }
        $(".icon").width($(".icon").height());
    </script>
</head>

<body>
    <div ng-app="app">
        <div class="filter-page" ng-show="loading">
            <image src="site-resources/images/logo.png" class = "loading">
        </div>
        <mcon></mcon>
        <header></header>
        <login></login>
        <div style = "width:100%"></div>
        <menu ng-if="logged && menu" class="slide"></menu>
        <ng-view></ng-view>
        <footer></footer>

        <!-- Angular script loading -->
        <script src="site-resources/js/app.js"></script>
        <script src="site-resources/js/directives.js"></script>
        <script src="site-resources/js/controllers.js"></script>
        <script src="site-resources/js/services.js"></script>
    </div>
</body>

</html>
