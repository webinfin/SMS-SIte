<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<!-- BEGIN #contact-panel -->
<aside id="contact-panel">
    <h4 class="hb-focus-color"><?php echo hb_options('hb_quick_contact_box_title'); ?></h4>
    <p><?php echo hb_options('hb_quick_contact_box_text'); ?></p>

    <form id="contact-panel-form">
        <p><input type="text" placeholder="<?php _e('Name', 'hbthemes'); ?>" name="hb_contact_name" id="hb_contact_name_id" class="required requiredField" tabindex="33"/></p>
        <p><input type="email" placeholder="<?php _e('Email', 'hbthemes'); ?>" name="hb_contact_email" id="hb_contact_email_id" class="required requiredField" tabindex="34"/></p>
        <p><input type="text" placeholder="<?php _e('Subject', 'hbthemes'); ?>" name="hb_contact_subject" id="hb_contact_subject_id"/></p>
        <p><textarea placeholder="<?php _e('Your message...', 'hbthemes'); ?>" name="hb_contact_message" id="hb_contact_message_id" class="required requiredField" tabindex="35"></textarea></p>
        <a href="#" id="hb-submit-contact-panel-form" class="hb-button no-three-d hb-push-button hb-asbestos hb-small-button">
            <span class="hb-push-button-icon">
                <i class="hb-moon-paper-plane"></i>
            </span>
            <span class="hb-push-button-text"><?php echo hb_options('hb_quick_contact_box_button_title'); ?></span>
        </a>
        <input type="hidden" id="success_text" value="<?php _e('Message Sent!', 'hbthemes'); ?>"/>
    </form>

</aside>
<!-- END #contact-panel -->

<!-- BEGIN #hb-contact-button -->
<a id="contact-button"><i class="hb-moon-envelop"></i></a>
<!-- END #hb-contact-button -->