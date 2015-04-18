<section class="hhv-tabs">
	<h1>Thành viên</h1>
	<ul>
		<li><a href="<?php echo base_url(); ?>backend/article/item" title="thành viên">Thành viên</a></li>
		<li class="active"><a href="<?php echo base_url(); ?>backend/article/additem" title="Thêm thành viên">Thêm thành viên</a></li>
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
				<p class="label">Danh mục cha:</p>
				<?php echo form_dropdown('data[parentid]', (isset($data['_show']['parentid'])?$data['_show']['parentid']:NULL),common_valuepost(isset($data['_post']['parentid'])?$data['_post']['parentid']:0),' id="txtParentid" class="cbSelect"'); ?>
			</label>

			<label class="item">
				<p class="label">Chủ đề:</p>
				<input type="text" name="data[tags]" value="<?php echo common_valuepost(isset($data['_post']['tags'])?$data['_post']['tags']:''); ?>" class="txtText" id="txtTags" />
				<input type="button" value="Chọn" class="btnButton" id="tags-suggest">
				<div id="tagspicker-suggest"></div>
			</label>

			<label class="item">
				<p class="label">Ảnh đại diện:</p>
				<input type="text" name="data[image]" value="<?php echo common_valuepost(isset($data['_post']['image'])?$data['_post']['image']:''); ?>" class="txtText" id="txtImage" />
				<input type="button" value="Chọn" class="btnButton" onclick="browserKCFinder('txtImage', 'image'); return FALSE;">
			</label>

			<label class="item">
				<p class="label">Mô tả:</p>
				<textarea class="txtTextarea mceEditor" name="data[description]"><?php echo common_valuepost(isset($data['_post']['description'])?$data['_post']['description']:''); ?></textarea>
			</label>

			<label class="item" >
				<p class="label">Nội dung:</p>
				<textarea class="txtTextarea mceEditor" id="txtContent" name="data[content]"><?php echo common_valuepost(isset($data['_post']['content'])?$data['_post']['content']:''); ?></textarea>
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
				<section class="checkbox-radio">
					<p class="label">Nổi bật:</p>
					<section class="group">
						<label><input type="radio" name="data[highlight]" value="1" <?php echo common_valuepost(isset($data['_post']['highlight'])?(($data['_post']['highlight'] == 1)?'checked="checked"':''):''); ?> /><span>Có</span></label>
						<label><input type="radio" name="data[highlight]" value="0" <?php echo common_valuepost(isset($data['_post']['highlight'])?(($data['_post']['highlight'] == 0)?'checked="checked"':''):''); ?> /><span>Không</span></label>
					</section>
				</section>
			</section><!-- .container -->
		</section><!-- .block -->
		<section class="block">
			<header>Thời gian</header>
			<section class="container">
				<label class="item">
					<p class="label">Hẹn giờ hiển thị:</p>
					<input type="text" name="data[timer]" value="<?php echo common_valuepost(isset($data['_post']['timer'])?$data['_post']['timer']:''); ?>" class="txtText" id="txtTimer" />
				</label>	
			</section><!-- .container -->
		</section><!-- .block -->
		<section class="block">
			<header>SEO</header>
			<section class="container">
				<label class="item">
					<p class="label">Meta title:</p>
					<input type="text" name="data[meta_title]" value="<?php echo common_valuepost(isset($data['_post']['meta_title'])?$data['_post']['meta_title']:''); ?>" class="txtText" />
				</label>	
				<label class="item">
					<p class="label">Meta keywords:</p>
					<input type="text" name="data[meta_keywords]" value="<?php echo common_valuepost(isset($data['_post']['meta_keywords'])?$data['_post']['meta_keywords']:''); ?>" class="txtText" />
				</label>
				<label class="item">
					<p class="label">Meta description:</p>
					<textarea class="txtTextarea" name="data[meta_description]" ><?php echo common_valuepost(isset($data['_post']['meta_description'])?$data['_post']['meta_description']:''); ?></textarea>
				</label>
			</section><!-- .container -->
		</section><!-- .block -->
		<section class="block">
			<header>Khác</header>
			<section class="container">
				<label class="item">
					<p class="label">Nguồn:</p>
					<input type="text" name="data[source]" value="<?php echo common_valuepost(isset($data['_post']['source'])?$data['_post']['source']:''); ?>" class="txtText" />
				</label>
				<label class="item">
					<p class="label">Url tùy biến:</p>
					<input type="text" name="data[route]" value="<?php echo common_valuepost(isset($data['_post']['route'])?$data['_post']['route']:''); ?>" class="txtText" />
				</label>	
			</section><!-- .container -->
		</section><!-- .block -->
	</aside>
	</form>
</section><!-- .hhv-form -->
