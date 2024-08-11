<?php 
	ob_start();
	session_start();
	require_once 'config/connect.php';

include 'inc/header.php'; 
include 'inc/nav.php'; 
?>
	
<!-- Contact Page Content -->
<section id="content">
	<div class="content-blog">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h2>Contact Us</h2>
					<div class="contact-form" style="box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1); padding: 20px;">
						<form action="send_email.php" method="post">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" name="name" required>
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" required>
							</div>
							<div class="form-group">
								<label for="subject">Subject</label>
								<input type="text" class="form-control" id="subject" name="subject" required>
							</div>
							<div class="form-group">
								<label for="message">Message</label>
								<textarea class="form-control" id="message" name="message" rows="5" required></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Send Message</button>
						</form>
					</div>
				</div>
				<div class="col-md-6">
					<h2>Find Us</h2>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d189.3838444323046!2d79.19674841185581!3d10.364517407061332!2m3!1f179.5221648832021!2f44.999999999998764!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x3b00061e4b38246d%3A0x6dfbf5383f88735e!2sThiruchitrambalam%2C%20Tamil%20Nadu%20614628!5e1!3m2!1sen!2sin!4v1723358108294!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					 
				</div>
			</div>
		</div>
	</div>
</section>
 

<?php include 'inc/footer.php'; ?>
