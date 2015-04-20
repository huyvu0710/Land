<!DOCTYPE html>
<html lang="en">
<head> 
	<meta charset="UTF-8">
	<title><?php echo isset($seo['title'])?$seo['title']:'' ?></title>
	<meta name="keywords" content="">
	<meta name="description" content="">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/template/backend/css/login.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/template/backend/css/normalize.css">
</head>
<body>

<header></header>

<section class="hhv-wrapper">
	<form method="post" action="">
	  <fieldset>
	  	<legend>Đăng nhập hệ thống</legend>
		<?php echo common_showerror(validation_errors()); ?>
	    <label><p>Tên sử dụng:</p><input type="text" name="data[username]" value="<?php echo common_valuepost(isset($_post['username'])?$_post['username']:''); ?>" class="txtUsername" /></label>
	    <label><p>Mật khẩu:</p><input type="password" name="data[password]" value="<?php echo common_valuepost(isset($_post['password'])?$_post['password']:''); ?>" class="txtPassword" /></label>
	    <section><input type="submit" name="login" value="Đăng nhập" class="btnLogin" /><input type="reset" name="reset" value="Làm lại" class="btnReset" /></section>
	    <nav> <!-- các thẻ điều hướng thường nằm trong thẻ nav -->
		    <ul>
		    	<li><a href="#">Về trang chủ</a></li>
		    	<li>/</li>
		    	<li><a href="<?php echo base_url(); ?>backend/auth/forgot">Quên mật khẩu</a></li>
		    </ul>
	    </nav>
	  </fieldset>
	</form>
</section>

<footer><p>Copyright &copy; <?php echo gmdate('Y', time() + 7*3600); ?> Power by <a href="#">hhv</a></p></footer>

</body>
</html>