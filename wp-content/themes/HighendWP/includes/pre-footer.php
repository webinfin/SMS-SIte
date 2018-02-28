<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<?php
    $button_icon="";
    $button_target="";
    if ( hb_options('hb_pre_footer_button_icon') ) {
        $button_icon = '<i class="' . hb_options("hb_pre_footer_button_icon") . '"></i>';
    }

    if (hb_options('hb_pre_footer_button_target') == '_blank'){
        $button_target = ' target="_blank"';
    }
?>
<!-- BEGIN #pre-footer-area -->
<div id="pre-footer-area">
    <div class="container">
        <span class="pre-footer-text"><?php echo hb_options('hb_pre_footer_text'); ?></span>
        <?php if (hb_options('hb_pre_footer_button_text')) { ?><a href="<?php echo hb_options('hb_pre_footer_button_link'); ?>"<?php echo $button_target; ?> class="hb-button hb-large-button"><?php echo $button_icon; echo hb_options('hb_pre_footer_button_text'); ?></a><?php } ?>
    </div>
</div>
<!-- END #pre-footer-area -->