<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Home :: Hitech';?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">

    <!-- our css style -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/responsive.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/compiled/flipclock.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/hitech_slide.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/mystyle.css'); ?>">

    <!-- js -->
    <script src="<?php echo base_url('assets/js/jquery-1.9.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/compiled/flipclock.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/prefixfree.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/modernizr.custom.js'); ?>"></script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/nojs.css'); ?>"/>
    </noscript>
    <script>
        $(document).ready(function () {
            var touch = $('#resp-menu');
            var menu = $('.menu');

            $(touch).on('click', function (e) {
                e.preventDefault();
                menu.slideToggle();
            });

            $(window).resize(function () {
                var w = $(window).width();
                if (w > 767 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });

        });
    </script>

</head>

<body>

<div class="container-fluid wrapper clearfix">
    <div class="container-fluid top_block clearfix">
        <div class="container">
            <div class="top_bar clearfix">
                <div class="row">
                    <div class="col-md-5 col-sm-5">
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="log logg"><a href="#">REGISTER</a>&nbsp;/&nbsp;<a href="#">LOGIN</a></div>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
                                </div>
                                <!-- /input-group -->
                            </div>

                        </div>
                    </div>
                    <!-- top a end -->

                    <div class="col-md-7 col-sm-7">
                        <div class="row">
                            <div class="col-md-8 col-sm-8">
                                <div class="tpnavi clearfix">
                                    <ul>
                                        <li><a href="<?php echo base_url(); ?>">HOME </a></li>
                                        <li><a href="<?php echo base_url('testimonials');?>">TESTIMONIALS </a></li>
                                        <li><a href="<?php echo base_url('blog');?>">BLOG </a></li>
                                        <li><a href="<?php echo base_url('about'); ?>">ABOUT US </a></li>
                                        <li><a href="<?php echo base_url(seoString('contact us'));?>">CONTACT US </a></li>
                                    </ul>

                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4">
                                <nav>
                                    <ul class="dropdown">
                                        <li class="drop"><a href="#"><i class="fa fa-cart-plus"></i>&nbsp; Your Cart [3]</a>
                                            <ul class="sub_menu">
                                                <li><a href="#">
                                                        <div class="cart_mobil clearfix">
                                                            <img
                                                                src="<?php echo base_url(); ?>uploads/slide/samsung.jpg"
                                                                width="55" height="55" alt="samsung"
                                                                style="float:left; margin:0px 7px 0px 0px;"/>

                                                            <h2 class="c_mb_ttl">SAMSUNG <br>N91OU Note 4 </h2>

                                                            <h3 class="c_mb_prc"><span class="no_prce">$114</span>
                                                                &nbsp; $899</h3>
                                                        </div>
                                                    </a></li>
                                                <!-- first end -->
                                                <li><a href="#">
                                                        <div class="cart_mobil clearfix">
                                                            <img
                                                                src="<?php echo base_url(); ?>uploads/slide/samsung.jpg"
                                                                width="55" height="55" alt="samsung"
                                                                style="float:left; margin:0px 7px 0px 0px;"/>

                                                            <h2 class="c_mb_ttl">SAMSUNG <br>N91OU Note 4 </h2>

                                                            <h3 class="c_mb_prc"><span class="no_prce">$114</span>
                                                                &nbsp; $899</h3>
                                                        </div>
                                                    </a></li>

                                                <li><a href="#"><i class="fa fa-check-square-o"></i> &nbsp;CHECKOUT</a>
                                                </li>
                                            </ul>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <!-- top b end -->

                </div>
            </div>
        </div>
    </div>
    <!-- top block end -->

    <div class="container">
        <div class="header">
            <div class="row">
                <div class="col-sm-2">
                    <div class="logo"><img src="<?php echo base_url(); ?>uploads/images/logo.png" alt="logo"
                                           class="img-responsive"/></div>
                </div>
                <!-- logo end -->

                <div class="col-sm-3 col-sm-offset-7">
                    <div class="tel_office"><span class="telph"><i class="fa fa-phone-square fa-2x"></i></span> 0800
                        HITECH (448325)
                    </div>
                </div>
                <!-- tel num end -->
            </div>
        </div>
    </div>
    <!-- banner end -->

    <div class="container-fluid mas_sec clearfix">
        <div class="container">

            <nav>
                <a id="resp-menu" class="responsive-menu" href="#"><i class="fa fa-reorder"></i> Menu</a>
                <ul class="menu">
                    <!-- <li><a class="homer" href="#">Mobile Phones</a></li> -->
                    <?php
                    if(isset($category_data) && !empty($category_data))
                    foreach ($category_data as $category) {
                        if ($category['parent_id'] == 0 && $category['status'] == 'Y') { ?>
                            <li>
                                <a href="<?php echo base_url('category/'.seoString($category['name']));?>"><?php echo $category['name']; ?></a>
                                <ul class="sub-menu">
                                    <?php
                                    foreach ($category_data as $cat) {
                                        if ($category['id'] == $cat['parent_id']) { ?>
                                            <li>
                                                <a href="<?php echo base_url('category/'.seoString($cat['name']));?>"><?php echo $cat['name']; ?></a>
                                                <ul>
                                                    <?php
                                                    foreach ($category_data as $cate) {
                                                        if ($cat['id'] == $cate['parent_id']) { ?>
                                                            <li>
                                                                <a href="<?php echo base_url('category/'.seoString($cate['name']));?>"><?php echo $cate['name']; ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
            <div class="clearfix"></div>
            <!-- menu end -->
            <div class="slide_hitech">
                <div id="da-slider" class="da-slider">
                    <?php
                    for ($i = 0; $i < count($slider_data); $i++) { ?>
                        <div class="da-slide">
                            <h2><?php echo $slider_data[$i]['title']; ?></h2>

                            <p><?php echo $slider_data[$i]['description']; ?></p>
                            <a href="#" class="da-link">Read more</a>

                            <div class="da-img"><img
                                    src="<?php echo base_url(); ?>uploads/slider/<?php echo $slider_data[$i]['id']; ?>/<?php echo $slider_data[$i]['name'] . '-' . $slider_data[$i]['id'] . '_.jpg'; ?>"
                                    alt="image01" height=256 width=256/></div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
            <div class="clearfix"></div>
            <!-- slide hitech end -->
            <div class="hidden-xs hidden-sm hidden-md visible-lg clearfix"><img
                    src="<?php echo base_url(); ?>uploads/images/sahde.png" alt="shadow" class="img-responsive"/></div>
        </div>
    </div>
    <!-- menu and slide end -->