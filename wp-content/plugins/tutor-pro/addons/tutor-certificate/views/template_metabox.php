<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

    function load_template_cert($templates, $mode, $template_field_name, $selected_template) {
        if (tutor_utils()->count($templates)){
            $added = 0;
            $current_user_id = get_current_user_id();

            foreach ($templates as $template_key => $template){
                if ( $template['orientation'] !== $mode || (isset($template['author_id']) && $template['author_id']!=$current_user_id)){
                    continue;
                }

                $added++;
                $id_key = 'tutor-certificate-' . $template_key;

                ?>
                <div class="template-item <?php /* echo $added>9 ? 'tutor-certificate-collapsible' : ''; */ ?>">
                    <label class="template-radio-field" for="<?php echo $id_key; ?>">
                        <input type="radio" name="<?php echo $template_field_name; ?>" value="<?php echo $template_key; ?>" id="<?php echo $id_key; ?>" <?php checked($template_key, $selected_template) ?>/>
                        <span class="icon-wrapper">
                            <img src="<?php echo $template['preview_src']; ?>" />
                        </span>
                        <div class="template-item-overlay">
                            <span class="tutor-btn tutor-is-xs"><?php _e('Use This', 'tutor-pro'); ?></span>
                            <a href="<?php echo $template['preview_src']; ?>" target="_blank" class="tutor-btn tutor-is-xs tutor-is-outline">
                                <?php _e('Preview', 'tutor-pro'); ?>
                            </a>
                        </div>
                    </label>
                </div>
                <?php
            }

            if($added>6) {
                ?>
                <!-- <div class="template-load-more">
                    <button class="load-more-btn color-text-primary text-medium-body">
                        <span class="ttr-plus-filled color-design-brand"></span> <?php _e('Show More', 'tutor-pro'); ?> 
                    </button>
                </div> -->
                <?php
            }
        }
    }
?>

<div class="tutor-default-tab tutor-certificate-template-tab">
    <div class="tab-header d-flex justify-content-center">
        <div class="tab-header-item is-active" data-tutor-tab-target="tab-target-certificate-1">
            <div class="item-wrapper color-black-30 d-flex align-items-center">
                <img src="<?php echo TUTOR_CERT()->url; ?>assets/images/certificate-landscape.svg" />
                <span class="title text-btn-medium"><?php _e('Landscape', 'tutor-pro'); ?></span>
            </div>
        </div>
        <div class="tab-header-item" data-tutor-tab-target="tab-target-certificate-2">
            <div class="item-wrapper color-black-30 d-flex align-items-center">
                <img src="<?php echo TUTOR_CERT()->url; ?>assets/images/certificate-portrait.svg" />
                <span class="title text-btn-medium"><?php _e('Portrait', 'tutor-pro'); ?></span>
            </div>
        </div>
    </div>
    <div class="tab-body">
        <div class="tab-body-item is-active" id="tab-target-certificate-1">
            <div class="tutor-certificate-template template-landscape">
                <?php load_template_cert($templates, 'landscape', $template_field_name, $selected_template); ?>
            </div>
        </div>
        
        <div class="tab-body-item" id="tab-target-certificate-2" style="display:none">
            <div class="tutor-certificate-template template-portrait">
            <?php load_template_cert($templates, 'portrait', $template_field_name, $selected_template); ?>
            </div>
        </div>
    </div>
</div>
