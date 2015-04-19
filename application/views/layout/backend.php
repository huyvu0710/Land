<!DOCTYPE html>
<html lang="en">
<head> 
	<meta charset="UTF-8">
	<title><?php echo isset($seo['title'])?$seo['title']:'' ?></title>
	<meta name="keywords" content="<?php echo isset($seo['keywords'])?htmlspecialchars($seo['keywords']):''; ?>">
	<meta name="description" content="<?php echo isset($seo['description'])?htmlspecialchars($seo['description']):''; ?>">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/template/backend/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/template/backend/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/template/backend/plugins/datetimepicker/jquery.datetimepicker.css">

	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script> 
	<script src="<?php echo base_url(); ?>public/template/backend/js/jquery-1.7.2.min.js"></script>

</head>
<body>
<header class="hhv-header"><p class="main-title">Hệ thống quản trị website</p>
	<ul class="lang">
	<?php
		$_lang = $this->session->userdata('_lang');
		$lang = array(
			'jp' => 'Tiếng Nhật',
			'en' => 'Tiếng Anh',
			'vi' => 'Tiếng Việt',
		);
		foreach ($lang as $key => $val) {
			if ($_lang == $key) {
				echo '<li><a href="'.base_url().'backend/home/lang/'.$key.'?continue='.base64_encode(common_fullurl()).'" title="'.$val.'" >['.$key.']</a></li>';
			}
			else
				echo '<li><a href="'.base_url().'backend/home/lang/'.$key.'?continue='.base64_encode(common_fullurl()).'" title="'.$val.'" >'.$key.'</a></li>';
		}
	?>
	</ul>
</header>
<?php $this->load->view('backend/common/nav');?>

<div class="container">
    <?php echo $content_for_layout ?>
</div>

<footer><p>Copyright &copy; <?php echo gmdate('Y', time() + 7*3600); ?> Power by <a href="#">hhv</a></p></footer>

<script src="<?php echo base_url(); ?>public/template/backend/plugins/datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>public/template/backend/js/function.js"></script>

<?php $this->load->view('backend/common/tinymce');?>
</body>
</html>