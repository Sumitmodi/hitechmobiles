<?php include('common/header.php'); ?>
        
        <div class="container">
        	<div class="deal_all hidden-xs clearfix">
            <div class="clock"></div>
			<div class="message"></div>
            </div>
        </div><!-- daily deal end -->
        
        <div class="container-fluid repair clearfix">
        	<div class="container">
           		<div class="system_rep"><div class="row">
                <div class="col-md-4 col-sm-4">
                <div class="moblrp">
                       
                        	<div class="celinfo clearfix">
                             <img src="<?php echo base_url(); ?>uploads/images/cell.png" width="110" height="110" alt="cel" style="float:left; margin:0px 0px 0px 0px;"/>
                           <h3 class="mprblk mprblkl"> <a href="#">MOBILE PHONE<br>
                            REPAIR</a></h3>
                            </div>
                        </div>
                
                </div><!-- mobile end -->
                
                <div class="col-md-4 col-sm-4">
                <div class="cmsystm">
                       <div class="celinfo clearfix">
                             <img src="<?php echo base_url(); ?>uploads/images/pc.png" width="110" height="110" alt="pc" style="float:left; margin:0px 0px 0px 0px;"/>
                           <h3 class="mprblk mprblkl"> <a href="#">COMPUTER &amp; LAPTOP<br>
                            REPAIR</a></h3>
                            </div>
                        </div>
                </div><!-- computer end -->
                
                <div class="col-md-4 col-sm-4">
                <div class="elcectrn">
                        <div class="celinfo clearfix">
                             <img src="<?php echo base_url(); ?>uploads/images/console.png" width="110" height="110" alt="electronic" style="float:left; margin:0px 0px 0px 0px;"/>
                           <h3 class="mprblk mprblkl"> <a href="#">CONSOLE &amp; ELECTRONIC<br>
                            REPAIR</a></h3>
                            </div>
                        </div>
                </div><!-- electroinic end -->
                
                </div></div><div class="clearfix"></div><!-- all repair end -->
                
                <div class="watchvdo clearfix"><div class="row">
                    	<div class="col-md-6">
                        <div class="vdblk">
                        	<h1 class="vdttl">LOREM IPSUM DOLOR SIT AMET </h1>
                            <p class="vdtxt">
                            Lorem ipsum door sit amet, conserctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum door sit amet, conserctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br><br>
                             Lorem ipsum door sit amet, conserctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum door sit amet, conserctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </p>
                            <div class="more moree clearfix">
                            
                           <a href="#">ACTION BUTTON</a>
                            </div>
                        </div>
                        </div><!-- article end -->
                        
                        <div class="col-md-6">
                        <div class="embed-responsive embed-responsive-16by9">
                          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/s8-GZkciG1U"></iframe>
                        </div>
                        </div><!-- vdo end -->
                    </div></div><!-- watch our video end -->
                
                
                
            </div>
        </div><!-- repair end -->
        
        <div class="container-fluid offer clearfix">
        	<div class="container">
            	<div class="cell_off">
                	<div class="deal_title">
                    <h2 class="ctnam">HOT DEALS </h2>
                        	<div class="burntt"><img src="<?php echo base_url(); ?>uploads/images/burn.png" width="54" height="63" alt="burn"/>  </div>
                    </div><div class="clearfix"></div>
                    
                    <section class="listings">
		
			<ul class="properties_list">
                <?php for ($i=0; $i < count($hot_deals_data); $i++) { ?>
                    <li>
                        <a href="#">
                            <img src="<?php echo base_url().'uploads/products/1/'.$hot_deals_data[$i]['id'] . '/'.$hot_deals_data[$i]['image_name'];?>" title="" class="property_img"/>
                        </a>
                        <span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
                        <div class="property_details">
                            <h1>
                                <a href="#"><?php echo $hot_deals_data[$i]['name']; ?></a>
                            </h1>
                            <h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                            <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
                        </div>
                    </li>
                <?php } ?>
				
				<!-- <li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li>
				<li>
					<a href="#">
						<img src="<?php echo base_url(); ?>uploads/images/phone-a.jpg" alt="" title="" class="property_img"/>
					</a>
					<span class="price"><img src="<?php echo base_url(); ?>uploads/images/hot-d.png" width="32" height="55" alt="hot"/></span>
					<div class="property_details">
						<h1>
							<a href="#">SAMSUNG iPhone 6 Edge NEW</a>
						</h1>
						<h2><s>$114</s>  &nbsp; <span class="property_size"><strong>$899</strong></span></h2>
                        <h3 class="detail_sm detail_smm"><a href="#"> DETAILS </a></h3>
					</div>
				</li> -->
			</ul>
			<div class="more_listing">
				<a href="#" class="more_listing_btn">More Hot DEALS</a>
			</div>
		
	</section>	<!--  end listing section  -->

                    
                </div>
            </div>
        </div><!-- hot deal offer end -->
        
        <div class="container-fluid hitech_off clearfix">
        	<div class="container">
            	<div class="special">
                	<div class="col-md-12">
                    <div class="inner_gtf"><div class="row">
                    	<div class="col-md-4 col-sm-4">
                        
                        <div class="kiran clearfix">
                        
                        
                        <p class="vchtxt"> THE PERFECT WAY TO<br> 
                        GIVE SOMEONE EXACTYLY<br>
                        WHAT THEY WANT </p>
                        <h2 class="getvc"> GIFT VOUCHERS </h2>
                        
                        <div class="slgft">
                        
                        	<h4 class="meslt"> CHOOSE VALUE <span class="dcol">$</span> </h4>
							<h6 class="slopt"> <select class="form-control">
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
</select> </h6>
                            <h5 class="buy"> BUY NOW !</h5>
                        </div>
                        
                        
                        	<div class="gift-icon"><img src="<?php echo base_url(); ?>uploads/images/gift.png" alt="gift" class="img-responsive"/></div>
                        </div>
                        
                        </div><!-- gift end -->
                        
                        <div class="col-md-4 col-sm-4">
                        <div class="trade clearfix">
                        <h3 class="timg"><img src="<?php echo base_url(); ?>uploads/images/trade.png" width="111" height="72" alt="trade"/> </h3>
                        	<h3 class="trdttl"> 
                            WE HAVE MORE THEN<br>
                            <span class="clnt">15000</span><br>
                            <span class="custm"> HAPPY CUSTOMERS<br>
                            AROUND THE<br>
                            NEW ZEALAND</span>
                            </h3>
                        </div>
                        </div><!-- trade end -->
                        
                        <div class="col-md-4 col-sm-4">
                        <div class="finance clearfix">
                        <h4 class="apply applyy"><a href="#"> Apply Now !</a> </h4>
                        <img src="<?php echo base_url(); ?>uploads/images/fince.jpg" alt="finance" class="img-responsive"/>
                        </div>
                        </div><!-- finance end -->
                        
                    </div></div>
                    </div><!-- gtf block end -->
                    
                    <div class="col-md-12">
                    <div class="sp_events"><div class="row">
                    <div class="col-sm-4">
                            	<div class="rqrep rqrepp clearfix"><a href="#"> Request for Repair </a></div>
                                
                                <div class="calph calphh clearfix"><a href="#">0800 HITECH (448325)</a> </div>
                                
                            </div><!-- part one end -->
                            
                            <div class="col-sm-4">
                            <div class="quickqo calphh clearfix"> <a href="#"> Get an Quick Quote </a> </div>
                            
                            <div class="calph calphh clearfix"><a href="#">email Us a Question</a> </div>
                            </div><!-- part two end -->
                            
                            <div class="col-sm-4"> 
                            <div class="quickqo calphh clearfix"> <a href="#"> Track an Order </a> </div>
                            
                            <div class="lchat calphh clearfix"><a href="#"> LIVE Chat </a></div>
                            </div><!-- part three end -->
                    </div></div>
                    </div><!-- else bottom end -->
                    
                </div>
            </div>
        </div><!-- hitech offer -->

<?php include('common/footer.php');?>