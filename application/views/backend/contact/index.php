<section class="hhv-tabs">
	<h1>Liên hệ</h1>
</section>

<section class="hhv-view">
	<section class="advanced">
		<section class="search">
			<form method="get" action="<?php echo base_url(); ?>backend/contact/index">
				<input type="hidden" name="sort_field" value="<?php echo $data['_sort']['field']; ?>" />
				<input type="hidden" name="sort_value" value="">
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
							'title' => 'Tên',
							'field' => 'fullname',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
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
							'title' => 'Số điện thoại',
							'field' => 'phone',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Tin nhắn',
							'field' => 'message',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Ngày tạo',
							'field' => 'created',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th>Người tạo</th>
					<th><?php echo get_link_sort(
						array(
							'base_url' => $data['_config']['base_url'],
							'page' => $data['_page'],
							'title' => 'Đã xem',
							'field' => 'readed',
							'sort_field' => $data['_sort']['field'], 
							'sort_value' => $data['_sort']['value']
						)); ?>
					</th>
					<th>Thao tác</th>
				</tr>
			</thead>
			<tbody>
			<?php if(isset($data['_list']) && count($data['_list'])){ ?>
			<?php foreach ($data['_list'] as $keyList => $valList) { ?>
				<tr>
					<!-- set STT -->
					<td><?php echo (($keyList+1) + $data['_config']['per_page'] * ($data['_page']-1))?></td>

					<td class="left"><?php echo $valList['fullname']?></td>
					<td class="left"><?php echo $valList['email']?></td>
					<td class="left"><?php echo $valList['phone']?></td>
					<td class="left"><?php echo $valList['message']?></td>

					<td><?php echo ($valList['created'] != '0000-00-00 00:00:00')?gmdate('H:i d/m/Y', strtotime($valList['created']) + 7*3600):'-'; ?></td>
					<td><?php $user = get_user($valList['userid_created'], 'username'); echo isset($user['username'])?$user['username']:'-' ?></td>

					<td><a href="<?php echo base_url(); ?>backend/contact/set/readed/<?php echo $valList['id']; ?>?continue=<?php echo base64_encode(common_fullurl()); ?>" title="Đã xem"><img src="<?php echo base_url(); ?>public/template/backend/images/<?php echo ($valList['readed'] == 1)?'check':'uncheck'; ?>.png" title="Trạng thái" /></a></td>

					<td>
						<a href="<?php echo base_url(); ?>backend/contact/view/<?php echo $valList['id']; ?>?continue=<?php echo base64_encode(common_fullurl()); ?>" title="Thay đổi"><img src="<?php echo base_url(); ?>public/template/backend/images/view.png" /></a>
						<a href="<?php echo base_url(); ?>backend/contact/del/<?php echo $valList['id']; ?>" title="Xóa" onclick="return confirm('Bạn có chắc chắn xóa?');"><img src="<?php echo base_url(); ?>public/template/backend/images/delete.png" /></a></td>
				</tr>
			<?php } ?>
			<?php } else { ?>
				<td class="last" colspan="8"><p>Không có dữ liệu</p></td>
			<?php } ?>
			</tbody>
			
		</table>
		</form>
	</section><!-- .table -->
	<?php echo (isset($data['pagination']) && !empty($data['pagination']))?'<section class="pagination">'.$data['pagination'].'</section>':'' ?>
</section><!-- .hhv-view -->
