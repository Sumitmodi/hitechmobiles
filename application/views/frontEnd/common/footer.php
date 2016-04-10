<div class="container-fluid footer clearfix">
    <div class="container">
        <div class="upft clearfix">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="me_a clearfix">
                                <ul>
                                    <li><a href="<?php echo base_url(); ?>">HOME</a></li>
                                    <?php
                                    if (isset($category_data) && !empty($category_data)) {
                                        foreach ($category_data as $cat) {
                                            if ($cat['parent_id'] != 0) {
                                                continue;
                                            }
                                            ?>
                                            <li>
                                                <a href="<?php echo base_url('category/' . seoString($cat['name'])); ?>">
                                                    <?php echo strtoupper($cat['name']); ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } ?>
                                    <li><a href="<?php echo base_url('hot-deals'); ?>">HOTDEALS</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- navi end -->
                        <?php
                        if (isset($pages)) {
                            $pages = array_chunk($pages, ceil(count($pages) / 2));
                        }
                        ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="me_a clearfix">
                                <ul>
                                    <li><a href="<?php echo base_url(seoString('about')); ?>"> ABOUT US </a></li>
                                    <li><a href="<?php echo base_url(seoString('contact us')); ?>"> CONTACT US </a></li>
                                    <?php if (isset($pages) && isset($pages[0])) {
                                        foreach ($pages[0] as $page) {
                                            ?>
                                            <li>
                                                <a href="<?php echo base_url($page->slug_name); ?>">
                                                    <?php echo $page->name; ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- navi end -->

                        <div class="col-md-3 col-sm-6">
                            <div class="me_a clearfix">
                                <ul>
                                    <?php if (isset($pages) && isset($pages[1])) {
                                        foreach ($pages[1] as $page) {
                                            ?>
                                            <li>
                                                <a href="<?php echo base_url($page->slug_name); ?>">
                                                    <?php echo $page->name; ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- navi end -->
                    </div>
                    <!-- footer navi end -->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="social clearfix">
                                <h2 class="solttl">Follow Us On : </h2>

                                <ul>
                                    <li><a href="#"> <i class="fa fa-youtube-square"></i> </a></li>
                                    <li><a href="#"> <i class="fa fa-tumblr-square"></i> </a></li>
                                    <li><a href="#"> <i class="fa fa-google-plus-square"></i> </a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- social end -->
                        <div class="col-md-8 col-sm-6">
                            <div class="newslet">
                                <h3 class="nltxt">Sign up for hot deals $5 off any accesory over $20 </h3>

                                <div class="merofm">
                                    <form role="form">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Your Email">
                                        </div>
                                        <button type="submit" class="btn btn-default">Submit</button>
                                    </form>
                                </div>

                            </div>
                            <!-- newsletter end -->
                        </div>

                    </div>
                    <!-- misll end -->
                    <!-- social n more end -->

                </div>
                <!-- our menu end -->

                <div class="col-md-3">
                    <div class="fblike clearfix">
                        <img src="<?php echo base_url(); ?>uploads/images/facebook.jpg" alt="facebook"
                             class="img-responsive"/>
                    </div>
                    <!-- fb like end -->

                    <div class="cards clearfix">
                        <ul>
                            <li><a href="#"> <i class="fa fa-cc-paypal"></i> </a></li>
                            <li><a href="#"> <i class="fa fa-cc-mastercard"></i> </a></li>
                            <li><a href="#"> <i class="fa fa-cc-visa"></i> </a></li>
                            <li><a href="#"> <i class="fa fa-cc-amex"></i> </a></li>
                        </ul>
                    </div>
                    <!-- card end -->

                </div>
                <!-- facebook like n card end -->

            </div>
            <!-- upper footer end -->

            <div class="col-md-12">
                <h5 class="cpyrt"> COPYRIGHT © 2015 HITECH MOBILES. ALL RIGHTS RESERVED. </h5>
            </div>
            <!-- copyright end -->

        </div>
    </div>
</div><!-- footer end -->

</div><!-- main wrapper end -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.cslider.js"></script>
<script type="text/javascript">
    $(function () {

        $('#da-slider').cslider({
            autoplay: true,
            bgincrement: 450
        });

    });
</script>
<script type="text/javascript">
    var clock;

    $(document).ready(function () {

        clock = $('.clock').FlipClock({
            clockFace: 'HourlyCounter'
        });
    });
</script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<script>
    var maxHeight = 400;

    $(function () {

        $(".dropdown > li").hover(function () {

            var $container = $(this),
                $list = $container.find("ul"),
                $anchor = $container.find("a"),
                height = $list.height() * 1.1,       // make sure there is enough room at the bottom
                multiplier = height / maxHeight;     // needs to move faster if list is taller

            // need to save height here so it can revert on mouseout
            $container.data("origHeight", $container.height());

            // so it can retain it's rollover color all the while the dropdown is open
            $anchor.addClass("hover");

            // make sure dropdown appears directly below parent list item
            $list
                .show()
                .css({
                    paddingTop: $container.data("origHeight")
                });

            // don't do any animation if list shorter than max
            if (multiplier > 1) {
                $container
                    .css({
                        height: maxHeight,
                        overflow: "hidden"
                    })
                    .mousemove(function (e) {
                        var offset = $container.offset();
                        var relativeY = ((e.pageY - offset.top) * multiplier) - ($container.data("origHeight") * multiplier);
                        if (relativeY > $container.data("origHeight")) {
                            $list.css("top", -relativeY + $container.data("origHeight"));
                        }
                        ;
                    });
            }

        }, function () {

            var $el = $(this);

            // put things back to normal
            $el
                .height($(this).data("origHeight"))
                .find("ul")
                .css({top: 0})
                .hide()
                .end()
                .find("a")
                .removeClass("hover");

        });

    });
</script>


</body>
</html>