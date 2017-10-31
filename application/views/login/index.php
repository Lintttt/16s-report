<link type="text/css" rel="stylesheet" href="<?php echo base_url('static/css/supersized.css')?>">

<title>登录</title>

<div class="contain" style="width: 20%;margin-left: 40%;margin-top: 300px;">
	<div class="main_box">
		<div class="loginbox">
			<?php echo validation_errors(); ?>

			<?php echo form_open('login/index'); ?>
				<div style="margin-left: 50px;margin-right: 50px;">
					<br>
					<font color="black"><strong><h5>Username</h5></strong></font>
					<input type="text" class="form-control" name="username" value="myciadmin" />

					<font color="black"><strong><h5>Password</h5></strong></font>
					<input type="password" class="form-control" name="password" value="123456" />
					<br>
					<input type="submit" class="form-control" value="Submit" />
				</div>

			</form>
		</div>
	</div>
</div>

<script src="<?php echo base_url('static/js/supersized.3.2.7.min.js')?>"></script>
<script src="<?php echo base_url('static/js/supersized-init.js')?>"></script>