<section class="hhv-tabs">
	<h1>Bài viết liên hệ</h1>
</section>

<section class="hhv-form">
	<form method="post" action="">
	<section class="main-panel main-panel-single">
		<header>Bài viết</header>
		<section class="block">
			<?php if(isset($data) || count($data)){ ?>
				<label class="item">
					<p class="label">Họ và tên:</p>
					<input type="text" value="<?php echo $data['fullname']; ?>" class="txtText" readonly="true" disabled/>
				</label>
				<label class="item">
					<p class="label">Email:</p>
					<input type="text" value="<?php echo $data['email']; ?>" class="txtText" readonly="true" disabled/>
				</label>
				<label class="item">
					<p class="label">Số điện thoại:</p>
					<input type="text" value="<?php echo $data['phone']; ?>" class="txtText" readonly="true" disabled/>
				</label>
				<label class="item">
					<p class="label">Tin nhắn:</p>
					<textarea class="txtTextarea" readonly="true" disabled><?php echo $data['message']; ?></textarea>
				</label>
			<?php } ?>

			<section class="action">
				<p class="label">Thao tác:</p>
				<section class="goback">
					<a style="border: 1px solid blue; padding: 8px 25px; background: azure;" href="<?php echo base_url(); ?>backend/contact/index" title="Quay lại">Quay lại</a>
				</section>
			</section>
		</section><!-- .block -->
	</section><!-- .main-panel -->
	</form>
</section><!-- .hhv-form -->