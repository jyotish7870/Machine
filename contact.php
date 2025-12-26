<?php 
require_once 'config.php';
require_once 'includes/functions.php';

// Get site settings
$site_settings = getAllSettings();
$site_name = $site_settings['site_name'] ?? 'Company Machines';
$page_title = ($site_settings['contact_title'] ?? 'Contact Us') . ' - ' . $site_name;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Here you can add code to send email or save to database
    if ($name && $email && $message) {
        $success = 'Thank you for contacting us! We will get back to you soon.';
    } else {
        $error = 'Please fill in all required fields.';
    }
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1><?php echo e($site_settings['contact_title'] ?? 'Contact Us'); ?></h1>
        <p><?php echo e($site_settings['contact_subtitle'] ?? 'Get in touch with us for any queries or support'); ?></p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <h2>Get In Touch</h2>
                <p>Have a question or need assistance? Feel free to reach out to us using the contact information below or send us a message using the form.</p>
                
                <div class="contact-items">
                    <?php if (!empty($site_settings['contact_address'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Address</h4>
                            <p><?php echo nl2br(e($site_settings['contact_address'])); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($site_settings['contact_phone1']) || !empty($site_settings['contact_phone2'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Phone</h4>
                            <?php if (!empty($site_settings['contact_phone1'])): ?>
                                <p><a href="tel:<?php echo formatPhoneLink($site_settings['contact_phone1']); ?>"><?php echo e($site_settings['contact_phone1']); ?></a></p>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['contact_phone2'])): ?>
                                <p><a href="tel:<?php echo formatPhoneLink($site_settings['contact_phone2']); ?>"><?php echo e($site_settings['contact_phone2']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($site_settings['contact_email1']) || !empty($site_settings['contact_email2'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <?php if (!empty($site_settings['contact_email1'])): ?>
                                <p><a href="mailto:<?php echo e($site_settings['contact_email1']); ?>"><?php echo e($site_settings['contact_email1']); ?></a></p>
                            <?php endif; ?>
                            <?php if (!empty($site_settings['contact_email2'])): ?>
                                <p><a href="mailto:<?php echo e($site_settings['contact_email2']); ?>"><?php echo e($site_settings['contact_email2']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($site_settings['contact_timing'])): ?>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Business Hours</h4>
                            <p><?php echo nl2br(e($site_settings['contact_timing'])); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="social-links-contact">
                    <?php if (!empty($site_settings['social_facebook'])): ?>
                        <a href="<?php echo e($site_settings['social_facebook']); ?>" target="_blank" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($site_settings['social_twitter'])): ?>
                        <a href="<?php echo e($site_settings['social_twitter']); ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($site_settings['social_linkedin'])): ?>
                        <a href="<?php echo e($site_settings['social_linkedin']); ?>" target="_blank" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($site_settings['social_instagram'])): ?>
                        <a href="<?php echo e($site_settings['social_instagram']); ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($site_settings['social_whatsapp'])): ?>
                        <a href="https://wa.me/<?php echo formatPhoneLink($site_settings['social_whatsapp']); ?>" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h2>Send Us a Message</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($site_settings['contact_map'])): ?>
<section class="map-section">
    <div class="container">
        <div class="map-container">
            <iframe src="<?php echo e($site_settings['contact_map']); ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
