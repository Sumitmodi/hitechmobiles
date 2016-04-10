<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $title; ?></title>
        <meta name="resource-type" content="document" />
        <meta name="robots" content="all,index, nofollow"/>
        <meta name="googlebot" content="all, index, nofollow" />

        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

        <script src="<?= $this->config->item('theme_exotic_url') ?>js/jquery.bxslider.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <!-- CSS styles -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="<?= $this->config->item('theme_exotic_url') ?>css/jquery.bxslider.css" rel="stylesheet" />
        <link href="<?= $this->config->item('theme_exotic_url') ?>css/style.css" rel="stylesheet" />
        <link rel="icon" href="<?= $this->config->item('theme_exotic_url') ?>img/favicon.gif" type="image/gif" sizes="16x16">
        <?php
        /** -- Copy from here -- */
        if (!empty($meta))
            foreach ($meta as $name => $content) {
                echo "\n\t\t";
                ?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
            }
        echo "\n";

        if (!empty($canonical)) {
            echo "\n\t\t";
            ?><link rel="canonical" href="<?php echo $canonical ?>" /><?php
        }
        echo "\n\t";

        foreach ($css as $file) {
            echo "\n\t\t";
            ?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
        } echo "\n\t";

        foreach ($js as $file) {
            echo "\n\t\t";
            ?><script src="<?php echo $file; ?>"></script><?php
        } echo "\n\t";

        /** -- to here -- */
        ?>


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->


    </head>

    <body>

        <?php $this->load->section('header_section', 'exotic/common/header'); ?>
        <?php echo $this->load->get_section('header_section'); ?>
        <?php $this->load->section('nav_section', 'exotic/common/nav'); ?>
        <?php echo $this->load->get_section('nav_section'); ?>

        <article>
            <div class="container" style="display: table;">
                <div class="row">
                    <?php $this->load->section('leftmenu_section', 'exotic/common/leftside'); ?>
                    <?php echo $this->load->get_section('leftmenu_section'); ?>
                    <div class="col-xs-12 col-md-9 sliderPaddingLeft0" >
                        <div class="content">
                            <?php echo $output; ?>
                            <?php echo $this->load->get_section('sidebar'); ?>

                        </div>
                    </div>
                </div>
            </div>

        </article>


        <?php $this->load->section('footer_section', 'exotic/common/footer'); ?>
        <?php echo $this->load->get_section('footer_section'); ?>



        <script>
            $(document).ready(function () {
                //$('.carousel').carousel();
                if (!$(".bxslider1").is(':visible')) {
                    console.log($(".destination").height() + " = " + $(".content").height());
                    if (parseInt($(".destination").height()) < parseInt($(".content").height())) {

                        $(".destination").height(parseInt($(".content").height()) - 30);
                    } else {
                         $(".content").height(parseInt($(".destination").height() +30 ));
                    }
                }

                $('.bxslider1').bxSlider({
                    auto: true,
                    onSliderLoad: function () {
                        console.log($(".destination").height() + " = " + $(".content").height());
                        if (parseInt($(".destination").height()) < parseInt($(".content").height())) {

                            $(".destination").height(parseInt($(".content").height()) - 30);
                        } else {
                            $(".content").height(parseInt($(".destination").height() + 30));
                        }
                        console.log($(".destination").height() + " = " + $(".content").height());
                    }
                });


                //$('.dropdown-toggle').dropdown();


            });
        </script>
        <script type="text/javascript">
            $(function () {
                $(".dropdown").hover(
                        function () {
                            $('.dropdown-menu', this).stop(true, true).fadeIn("fast");
                            $(this).toggleClass('open');
                            //$('b', this).toggleClass("caret caret-up");                
                        },
                        function () {
                            $('.dropdown-menu', this).stop(true, true).fadeOut("fast");
                            $(this).toggleClass('open');
                            //$('b', this).toggleClass("caret caret-up");                
                        }
                );
            });

        </script>
    </body>
</html>
