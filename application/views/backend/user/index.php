<section class="hhv-tabs">
	<h1>Thành viên</h1>
	<ul>
		<li class="active"><a href="<?php echo base_url(); ?>backend/user/index" title="Thành viên">Thành viên</a></li>
		<li><a href="<?php echo base_url(); ?>backend/user/add" title="Thêm thành viên">Thêm thành viên</a></li>
	</ul>
</section>

<section class="hhv-view">
	<section class="advanced">
		<section class="search">
			<form method="get" action="<?php echo base_url(); ?>backend/user/index">
				<input type="hidden" name="sort_field" value="<?php echo $data['_sort']['field']; ?>" />
				<input type="hidden" name="sort_value" value="">
				<?php echo form_dropdown('groupid', (isset($data['_show']['groupid'])?$data['_show']['groupid']:NULL),common_valuepost(isset($data['_groupid'])?$data['_groupid']:0),' id="txtgroupid" class="cbSelect"'); ?>
				<input type="text" name="keyword" class="text" value="<?php echo isset($data['_keyword'])?common_valuepost($data['_keyword']):'' ?>">
				<input type="submit" class="submit" value="Tìm kiếm">
			</form>
		</section>
		
	</section>
	<section class="table">
		<form method="post" action="">
		<table cellpadding="0" cellspacing="0" class="main">
			<thead>
				<tr>
					<th>STT</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Tên thành viên',
							'field' => 'username',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th>Bài viết</th>
					<th>Nhóm</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Email',
							'field' => 'email',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Tên đầy đủ',
							'field' => 'fullname',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Ngày đăng ký',
							'field' => 'created',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th>Thao tác</th>
					<th class="last"><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Mã',
							'field' => 'id',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php if(isset($data['_list']) && count($data['_list'])){ ?>
			<?php foreach ($data['_list'] as $keyList => $valList) { ?>
				<tr>
					<!-- set STT -->
					<td><?php echo (($keyList+1) + $data['_config']['per_page'] * ($data['_page']-1))?></td>

					<td class="left"><?php echo $valList['username']?></td>
					<td><?php echo get_count_post('article_item', array('userid_created' => $valList['id']));?></td>
					<td><?php $category = get_category('user_group', 'id, title', array('id' => $valList['groupid'])); echo isset($category['title'])?$category['title']:''; ?></td>
					<td class="left"><?php echo $valList['email']?></td>
					<td class="left"><?php echo $valList['fullname']?></td>

					<td><?php echo ($valList['created'] != '0000-00-00 00:00:00')?gmdate('H:i d/m/Y', strtotime($valList['created']) + 7*3600):'-'; ?></td>
					<td>
						<a href="<?php echo base_url(); ?>backend/user/edit/<?php echo $valList['id']; ?>" title="Thay đổi"><img src="<?php echo base_url(); ?>public/template/backend/images/edit.png" /></a>
						<a href="<?php echo base_url(); ?>backend/user/del/<?php echo $valList['id']; ?>" title="Xóa" onclick="return confirm('Bạn có chắc chắn xóa?');"><img src="<?php echo base_url(); ?>public/template/backend/images/delete.png" /></a></td>
					<td class="last"><?php echo $valList['id']; ?></td>
				</tr>
			<?php } ?>
			<?php } else { ?>
				<td class="last" colspan="9"><p>Không có dữ liệu</p></td>
			<?php } ?>
			</tbody>
			
		</table>
		</form>
	</section><!-- .table -->
	<?php echo (isset($data['pagination']) && !empty($data['pagination']))?'<section class="pagination">'.$data['pagination'].'</section>':'' ?>
</section><!-- .hhv-view -->
