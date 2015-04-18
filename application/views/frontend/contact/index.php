<!-- banner -->
<div class="inside-banner">
  <div class="container"> 
    <span class="pull-right"><a href="#">Trang chủ</a> / Liên hệ</span>
    <h2>Liên hệ</h2>
</div>
</div>
<!-- banner -->


<div class="container">
<div class="spacer">
<div class="row contact">
  <div class="col-lg-6 col-sm-6 ">
              <?php echo common_showerror(validation_errors()); ?>
              <form method="post" action="">
                <input type="text" name="data[fullname]" class="form-control" placeholder="Tên đầy đủ">
                <input type="text" name="data[email]" class="form-control" placeholder="Email">
                <input type="text" name="data[phone]" class="form-control" placeholder="Số điện thoại">
                <textarea rows="6" name="data[message]" class="form-control" placeholder="Tin nhắn"></textarea>
                <input type="submit" class="btn btn-success my_input" name="submit" value="Gửi" >
              </form>
  </div>
  <div class="col-lg-6 col-sm-6 ">
  <div class="well"><iframe width="100%" height="300px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.7064498691057!2d106.67181199999996!3d10.757091999999993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752efcb8bdabcb%3A0x48b00917af47eef6!2sdai+hoc+su+pham!5e0!3m2!1svi!2s!4v1429364495065" width="600" height="450" frameborder="0" style="border:0"></iframe></div>
  </div>
</div>
</div>
</div>

