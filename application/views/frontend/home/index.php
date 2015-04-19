<!-- slider-wrapper Start -->

<!-- slider-wrapper End -->

<!-- banner search Start -->
<div class="banner-search">
    <div class="container">
        <!-- banner -->
        <h3>Tìm kiếm</h3>

        <div class="searchbar">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <input type="text" class="form-control" placeholder="Nhập địa điểm ...">

                    <div class="row">
                        <div class="col-lg-3 col-sm-3 ">
                            <select class="form-control">
                                <option>Buy</option>
                                <option>Rent</option>
                                <option>Sale</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <select class="form-control">
                                <option>Tỉnh/TP</option>
                                <option>Apartment</option>
                                <option>Building</option>
                                <option>Office Space</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <select class="form-control">
                                <option>Quận/Huyện</option>
                                <option>Apartment</option>
                                <option>Building</option>
                                <option>Office Space</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <select class="form-control">
                                <option>Diện tích</option>
                                <option>Apartment</option>
                                <option>Building</option>
                                <option>Office Space</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <select class="form-control">
                                <option>Khoảng Giá</option>
                                <option>$150,000 - $200,000</option>
                                <option>$200,000 - $250,000</option>
                                <option>$250,000 - $300,000</option>
                                <option>$300,000 - above</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <select class="form-control">
                                <option>Loại BĐS</option>
                                <option>Apartment</option>
                                <option>Building</option>
                                <option>Office Space</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <button class="btn btn-success" onclick="window.location.href='buysalerent.php'">Tìm nào!
                            </button>
                        </div>
                    </div>


                </div>
                <div class="col-lg-5 col-lg-offset-1 col-sm-6 ">
                    <p>Cùng tham gia và nhận nhiều quyền lợi nào!</p>
                    <button class="btn btn-info" data-toggle="modal" data-target="#loginpop"><a href="<?php echo base_url(); ?>backend/auth/login"><?php echo $this->lang->line('ft_login');?></a></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner search End -->

<div class="container">
    <div class="properties-listing spacer"><a href="buysalerent.php" class="pull-right viewall">View All Listing</a>

        <h2>Nhà đất nổi bật</h2>

        <div id="owl-example" class="owl-carousel">
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/1.jpg"
                                               class="img-responsive" alt="properties"/>

                    <div class="status sold">Sold</div>
                </div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/2.jpg"
                                               class="img-responsive" alt="properties"/>

                    <div class="status new">New</div>
                </div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/3.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/4.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/1.jpg"
                                               class="img-responsive" alt="properties"/>

                    <div class="status sold">Sold</div>
                </div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/2.jpg"
                                               class="img-responsive" alt="properties"/>

                    <div class="status sold">Sold</div>
                </div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/3.jpg"
                                               class="img-responsive" alt="properties"/>

                    <div class="status new">New</div>
                </div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/4.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/1.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/2.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>
            <div class="properties">
                <div class="image-holder"><img src="<?php echo base_url(); ?>public/template/frontend/images/properties/3.jpg"
                                               class="img-responsive" alt="properties"/></div>
                <h4><a href="property-detail.php">Royal Inn</a></h4>

                <p class="price">Price: $234,900</p>

                <div class="listing-detail"><span data-toggle="tooltip" data-placement="bottom"
                                                  data-original-title="Bed Room">5</span> <span data-toggle="tooltip"
                                                                                                data-placement="bottom"
                                                                                                data-original-title="Living Room">2</span>
                    <span data-toggle="tooltip" data-placement="bottom" data-original-title="Parking">2</span> <span
                        data-toggle="tooltip" data-placement="bottom" data-original-title="Kitchen">1</span></div>
                <a class="btn btn-primary" href="property-detail.php">View Details</a>
            </div>

        </div>
    </div>
    <div class="spacer">
        <div class="row">
            <div class="col-lg-6 col-sm-9 recent-view">
                <h3>Giới thiệu</h3>

                <p>Website được xây dựng nhằm giới thiệu, mua bán nhà đất online, phục vụ nhu cầu tìm kiếm và kinh doanh các ngôi nhà.<br><a
                        href="<?php echo base_url(); ?>frontend/about/index">Đọc tiếp</a></p>

            </div>
            <div class="col-lg-5 col-lg-offset-1 col-sm-3 recommended">
                <h3>Recommended Properties</h3>

                <div id="myCarousel" class="carousel slide">
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1" class=""></li>
                        <li data-target="#myCarousel" data-slide-to="2" class=""></li>
                        <li data-target="#myCarousel" data-slide-to="3" class=""></li>
                    </ol>
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="row">
                                <div class="col-lg-4"><img
                                        src="<?php echo base_url(); ?>public/template/frontend/images/properties/1.jpg"
                                        class="img-responsive" alt="properties"/></div>
                                <div class="col-lg-8">
                                    <h5><a href="property-detail.php">Integer sed porta quam</a></h5>

                                    <p class="price">$300,000</p>
                                    <a href="property-detail.php" class="more">More Detail</a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row">
                                <div class="col-lg-4"><img
                                        src="<?php echo base_url(); ?>public/template/frontend/images/properties/2.jpg"
                                        class="img-responsive" alt="properties"/></div>
                                <div class="col-lg-8">
                                    <h5><a href="property-detail.php">Integer sed porta quam</a></h5>

                                    <p class="price">$300,000</p>
                                    <a href="property-detail.php" class="more">More Detail</a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row">
                                <div class="col-lg-4"><img
                                        src="<?php echo base_url(); ?>public/template/frontend/images/properties/3.jpg"
                                        class="img-responsive" alt="properties"/></div>
                                <div class="col-lg-8">
                                    <h5><a href="property-detail.php">Integer sed porta quam</a></h5>

                                    <p class="price">$300,000</p>
                                    <a href="property-detail.php" class="more">More Detail</a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row">
                                <div class="col-lg-4"><img
                                        src="<?php echo base_url(); ?>public/template/frontend/images/properties/4.jpg"
                                        class="img-responsive" alt="properties"/></div>
                                <div class="col-lg-8">
                                    <h5><a href="property-detail.php">Integer sed porta quam</a></h5>

                                    <p class="price">$300,000</p>
                                    <a href="property-detail.php" class="more">More Detail</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>