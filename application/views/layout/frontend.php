<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <!-- CSS here -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/template/frontend/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/template/frontend/css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/template/frontend/css/my_style.css"/>

    <!-- JS here -->
    <script type="text/javascript" src="<?php echo base_url(); ?>public/template/frontend/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/template/frontend/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/template/frontend/js/script.js"></script>

    <!-- Owl -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/template/frontend/css/owl.carousel.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/template/frontend/css/owl.theme.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/template/frontend/js/owl.carousel.js"></script>

</head>
<body>
<!-- Navbar Starts -->
<div class="navbar-wrapper">

    <div class="navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">


                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('ft_menu_home');?></a></li>
                    <li><a href="<?php echo base_url(); ?>frontend/about/index"><?php echo $this->lang->line('ft_menu_introduce');?></a></li>
                    <li><a href="<?php echo base_url(); ?>frontend/agent/index"><?php echo $this->lang->line('ft_menu_agent');?></a></li>
                    <li><a href="<?php echo base_url(); ?>frontend/contact/index"><?php echo $this->lang->line('ft_menu_contact');?></a></li>

                    <li id="<?php echo !isset($this->auth)?'box_hidden':'#';?>"><a href="<?php echo base_url(); ?>frontend/account/index"><?php echo $this->lang->line('ft_account');?></a></li>

                    <li id="<?php echo (!isset($this->auth) || $this->auth['group_title']!='Quản trị viên')?'box_hidden':'#';?>"><a href="<?php echo base_url(); ?>backend"><?php echo $this->lang->line('ft_page_admin');?></a></li>
                    
                    <li id="<?php echo !isset($this->auth)?'box_hidden':'#';?>"><a href="<?php echo base_url(); ?>backend/auth/logout"><?php echo $this->lang->line('ft_logout');?></a></li>

                    <ul class="lang">
                    <?php
                        $_lang = $this->session->userdata('_lang');
                        
                        $lang = array(
                            'jp' => 'Tiếng Nhật',
                            'en' => 'Tiếng Anh',
                            'vi' => 'Tiếng Việt',
                        );
                        foreach ($lang as $key => $val) {
                            echo '<li><a href="'.(($key!='vi')?$key.HHV_URL_SUFFIX:'vi.html').'" title="'.$val.'" >'.$key.'</a></li>';
                        }
                    ?>
                    </ul>
                </ul>

            </div>
            <!-- #Nav Ends -->

        </div>
    </div>
</div>
<!-- Navbar  End -->

<!-- Header Starts -->
<div class="container">
    <div class="header">
        <a href="index.php"><img src="<?php echo base_url(); ?>public/template/frontend/images/logo.png" alt="Realestate"></a>

        <ul class="pull-right">
            <li><a href="#">Nhà đất bán</a></li>
            <li><a href="#">Nhà đất cho thuê</a></li>
            <li><a href="<?php echo base_url(); ?>frontend/article/index">Tin tức</a></li>
            <li class="publish"><a href="#">Đăng tin</a></li>
        </ul>
    </div>
</div>
<!-- Header End -->

<!-- Container Start -->
    <?php echo $content_for_layout ?>
<!-- Container End -->

<!-- Footer -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-3">
                <h4>Thông tin</h4>
                <ul class="row">
                    <li class="col-lg-12 col-sm-12 col-xs-3"><a href="<?php echo base_url(); ?>frontend/about/index">Giới thiệu</a></li>
                    <li class="col-lg-12 col-sm-12 col-xs-3"><a href="<?php echo base_url(); ?>frontend/agent/index">Quản trị viên</a></li>
                    <li class="col-lg-12 col-sm-12 col-xs-3"><a href="contact.php">Liên hệ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-sm-3">
                <h4>Thư tin tức</h4>

                <p>Nhận thông báo về email những tin tức về thị trường bất động sản!</p>

                <form class="form-inline" role="form">
                    <input type="text" placeholder="Nhập email của bạn" class="form-control">
                    <button class="btn btn-success" type="button">Gửi!</button>
                </form>
            </div>

            <div class="col-lg-3 col-sm-3">
                <h4>Mạng xã hội</h4>
                <a href="http://www.facebook.com"><img src="<?php echo base_url(); ?>public/template/frontend/images/facebook.png" alt="facebook"></a>
                <a href="#"><img src="<?php echo base_url(); ?>public/template/frontend/images/twitter.png" alt="twitter"></a>
                <a href="#"><img src="<?php echo base_url(); ?>public/template/frontend/images/linkedin.png" alt="linkedin"></a>
                <a href="#"><img src="<?php echo base_url(); ?>public/template/frontend/images/instagram.png"
                                 alt="instagram"></a>
            </div>

            <div class="col-lg-3 col-sm-3">
                <h4>Liên hệ</h4>

                <p><b>HHV Company</b><br>
                    <span class="glyphicon glyphicon-map-marker"></span> 280 An Dương Vương, P4, Q5, Hồ Chí Minh, Việt Nam<br>
                    <span class="glyphicon glyphicon-envelope"></span> hahuyvu0710@gmail.com<br>
                    <span class="glyphicon glyphicon-earphone"></span> 01282 252 852</p>
            </div>
        </div>
        <p class="copyright">Copyright &copy; <?php echo gmdate('Y', time() + 7*3600); ?> Power by <a href="#">hhv</a></p>
    </div>
</div>
<!-- Footer End -->
</body>
</html>