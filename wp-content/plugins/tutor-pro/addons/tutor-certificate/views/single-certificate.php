<?php
/**
 * Template for displaying certificate
 *
 * @since v.1.5.1
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Certificate
 * @version 1.5.1
 */

tutor_utils()->tutor_custom_header(); ?>

    <link rel="stylesheet" href="<?php echo TUTOR_CERT()->url . 'assets/css/certificate-page.css'; ?>">

    <div class="<?php tutor_container_classes(); ?>">
		<?php do_action('tutor_certificate/before_content'); ?>

        <div class="tutor-certificate-container">
            <div class="tutor-certificate-img-container">
                <img id="tutor-pro-certificate-preview" src="<?php echo $cert_img; ?>" data-is_generated="<?php echo $cert_file ? 'yes' : 'no'; ?>"/>
                <div class="certificate-url">
                    <span><?php _e('Certificate URL'); ?>:</span>
                    <span><?php echo $cert_url; ?></span>
                    <span>
                        <span class="tutor-copy-text" data-text="<?php echo $cert_url; ?>">
                            <i class="tutor-icon-copy"></i> <?php _e('Copy Link', 'tutor-pro'); ?>
                        </span>
                    </span>
                </div>
            </div>

            <div class="tutor-certificate-sidebar">
                <h3><?php echo $course->post_title; ?></h3>
                <table class="certificate-info">
                    <tbody>
                        <tr>
                            <td><?php _e('Certificate ID', 'tutor-pro'); ?></td>
                            <td><?php echo  $cert_hash; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Issued Date', 'tutor-pro'); ?></td>
                            <td><?php echo $completed->completion_date; ?></td>
                        </tr>
                        <?php
                            $issued_by = tutor_utils()->get_option('tutor_cert_authorised_name');
                            if($issued_by) {
                                ?>
                                    <tr>
                                        <td><?php _e('Issued By', 'tutor-pro'); ?></td>
                                        <td><?php echo $issued_by; ?></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
                <div class="tutor-dropdown">
                    <button class="tutor-dropbtn tutor-btn tutor-button-block download-btn">
                        <i class="tutor-icon-download"></i> <?php _e('Download', 'tutor-pro'); ?>
                    </button>
                    <div class="tutor-dropdown-content">
                        <ul>
                            <li>
                                <a id="tutor-pro-certificate-download-pdf" class="tutor-cert-view-page" data-cert_hash="<?php echo $cert_hash; ?>" data-course_id="<?php echo $course->ID; ?>">
                                    <i class="tutor-icon-pdf"></i> <?php _e('PDF', 'tutor-pro'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="tutor-pro-certificate-download-image">
                                    <i class="tutor-icon-jpg"></i> <?php _e('JPG', 'tutor-pro'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php tutor_utils()->tutor_custom_footer();