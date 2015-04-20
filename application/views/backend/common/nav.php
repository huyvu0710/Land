<nav class="navigation">
	<ul class="main">
		<li class="main">
			<a class="main" href="<?php echo base_url(); ?>backend/config/index" title="Cấu hình hệ thống">Hệ thống</a>
			<ul class="item">
				<li class="item">
					<a href="<?php echo base_url(); ?>backend/config/index" title="Cấu hình hệ thống">Cấu hình</a>
				</li>
			</ul>
		</li>
		<li class="main">
			<a class="main" href="<?php echo base_url(); ?>backend/user/index" title="Thành viên">Thành viên</a>
			<ul class="item">
				<li class="item"><a href="<?php echo base_url(); ?>backend/user/group" title="Nhóm thành viên">Nhóm thành viên</a></li>
				<li class="item"><a href="<?php echo base_url(); ?>backend/user/index" title="Thành viên">Thành viên</a></li>
			</ul>
		</li>
		<li class="main">
			<a class="main" href="#">Module</a> 
			<ul class="item">
			<li class="item"><a href="<?php echo base_url(); ?>backend/menu/index" title="Menu">Menu</a></li>
				<li class="item"><a href="<?php echo base_url(); ?>backend/adv/index" title="Quảng cáo">Quảng cáo</a></li>
				<li class="item"><a href="<?php echo base_url(); ?>backend/tag/index" title="Từ khóa">Từ khóa</a></li>
				
				<li class="item"><a href="#">Hỗ trợ trực tuyến</a></li>
				<li class="item"><a href="#">Phản hồi</a></li>
			</ul>
		</li>
		<li class="main">
			<a class="main" href="<?php echo base_url(); ?>backend/article/item" title="Bài viết">Bài viết</a>
			<ul class="item">
				<li class="item"><a href="<?php echo base_url(); ?>backend/article/category" title="Danh mục">Danh mục</a></li>
				<li class="item"><a href="<?php echo base_url(); ?>backend/article/item" title="Bài viết">Tin tức</a></li>
				<li class="item"><a href="<?php echo base_url(); ?>backend/article/itemland" title="Nhà đất">Nhà đất</a></li>
			</ul>
		</li>
		<li class="main">
			<a class="main" href="<?php echo base_url(); ?>backend/contact/index" title="Liên hệ">Liên hệ</a>
		</li>
		<li class="main">
			<a class="main" href="<?php echo base_url(); ?>" title="Trang chủ">View Page</a>
		</li>
	</ul>
	<ul class="user-account">
		<li>Chào <strong><?php echo !empty($data['auth']['fullname'])?$data['auth']['fullname']:$data['auth']['username']; ?></strong></li>
		<li><a href="<?php echo base_url(); ?>backend/account/info">Thông tin</a></li>
		<li><a href="<?php echo base_url(); ?>backend/auth/logout" title="Đăng xuất">Đăng xuất</a></li>
	</ul>
</nav>