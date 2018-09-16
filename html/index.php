<!doctype html>
<html>
    <head>
        <title>PHP Static Analysis</title>

        <style>
            .color1 {color: #5D5C61;}
            .color2 {color: #379683;}
            .color3 {color: #7395AE;}
            .color4 {color: #557A95;}
            .color5 {color: #B1A296;}

            body, html {margin: 0; padding: 0; background: #f3f3f3;}

            header {background: green; width: 100%; height: 2em;}
            header p {line-height: 2em; margin: 0; padding: 0 .5em;}
            header p.left {float: left;}
            header p.right {float: right;}

            .splash {
                width: 100%;
                min-height: 50vmin;
                background-repeat: no-repeat;
                background-size: 100vmax;
            }
            .splash h1 {margin: 0; padding: 1em .5em 0 .5em;}
            #splash1 {background-image: url(img/smoke21.png); background-color: #f4f4d2;}
            #splash2 {background-image: url(img/smoke23.png); background-color: #f4d2d8;}
            #splash3 {background-image: url(img/smoke22.png); background-color: #d2f4ea;}
            #splash4 {background-image: url(img/smoke25.png); background-color: #d6f4d2;}
            #splash5 {background-image: url(img/smoke24.png); background-color: #d2ddf4;}
        </style>

        <!-- Global Site Tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125784996-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-125784996-1');
        </script>
    </head>
    <body>
        <header>
            <p class="left">PHP Static Analysis</p>
            <p class="right">
                <a href="#">Success stories</a> |
                <a href="/cyclomatic-complexity">Compare tools</a> |
                <a href="#">Try tools now</a>
            </p>
        </header>

        <?php
            $request = substr($_SERVER['REQUEST_URI'], 1);

            if (ctype_alnum(str_replace('-', '', $request)) && file_exists('/code/articles/' . $request . '.php')) {
                require '/code/articles/' . $request . '.php';
            } else {
                require '/code/splash.php';
                require '/code/articles/cyclomatic-complexity.php';
                require '/code/articles/contributions-welcome.php';
            }
        ?>

    </body>
</html>
