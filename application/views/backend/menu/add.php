<section class="hhv-tabs">
	<h1>Thêm menu</h1>
	<ul>
		<li><a href="<?php echo base_url(); ?>backend/menu/index" title="menu">Menu</a></li>
		<li class="active"><a href="<?php echo base_url(); ?>backend/menu/add" title="Thêm menu">Thêm menu</a></li>
	</ul>
</section>

<section class="hhv-form">
	<form method="post" action="">
	<section class="main-panel">
		<header>Thông tin chung</header>
		<?php echo common_showerror(validation_errors()); ?>
		<section class="block">
			<label class="item">
				<p class="label">Tiêu đề:</p>
				<input type="text" name="data[title]" value="<?php echo common_valuepost(isset($data['_post']['title'])?$data['_post']['title']:''); ?>" class="txtText" />
			</label>
			
			<label class="item">
				<p class="label">Url:</p>
				<input type="text" name="data[url]" value="<?php echo common_valuepost(isset($data['_post']['url'])?$data['_post']['url']:''); ?>" class="txtText" />
			</label>

			<label class="item">
				<p class="label">Module:</p>
				<input type="text" name="data[module]" value="<?php echo common_valuepost(isset($data['_post']['module'])?$data['_post']['module']:''); ?>" class="txtText" />
			</label>

			<label class="item">
				<p class="label">Module ID:</p>
				<input type="text" name="data[module_id]" value="<?php echo common_valuepost(isset($data['_post']['module_id'])?$data['_post']['module_id']:''); ?>" class="txtText" />
			</label>

			<section class="action">
				<p class="label">Thao tác:</p>
				<section class="group">
					<input type="submit" name="add" value="Thêm mới" />
					<input type="reset" name="add" value="Làm lại" />
					
				</section>
			</section>
		</section><!-- .block -->
	</section><!-- .main-panel -->
	<aside class="side-panel">
		<section class="block">
			<header>Nâng cao</header>
			<section class="container">
				<section class="checkbox-radio">
					<p class="label">Hiển thị:</p>
					<section class="group">
						<label><input type="radio" name="data[publish]" value="1" <?php echo common_valuepost(isset($data['_post']['publish'])?(($data['_post']['publish'] == 1)?'checked="checked"':''):''); ?> /><span>Có</span></label>
						<label><input type="radio" name="data[publish]" value="0" <?php echo common_valuepost(isset($data['_post']['publish'])?(($data['_post']['publish'] == 0)?'checked="checked"':''):''); ?> /><span>Không</span></label>
					</section>
				</section>
			</section><!-- .container -->
		</section><!-- .block -->
	</aside>
	</form>
</section><!-- .hhv-form -->
