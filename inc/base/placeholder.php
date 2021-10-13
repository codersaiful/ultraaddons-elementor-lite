<?php

/**
 * Has removed this class's functionality
 * 
 * @deprecated since version 1.0.9.4
 * 
 */
namespace UltraAddons\Base;

use Elementor\Widget_Base;

/**
 * Placeholder for Pro Widget
 * we will register all pro widget
 * in Screen Area widget list
 * 
 * @param String $widget_key 
 * @param Array $widget 
 * 
 * @since 1.0.9.3
 * @by Saiful Islam
 * 
 * Has removed this class's functionality
 * 
 * @deprecated since version 1.0.9.4
 */
class Placeholder extends Widget_Base{
    public $name;
    public $icon;
    public $title;
    public function __construct( String $widget_key, Array $widget = null ) {
        $this->name = $widget_key;
        $this->icon = isset($widget['icon']) ? 'ultraaddons ua-pro ' . $widget['icon'] : 'uicon-ultraaddons';
        $this->title = isset($widget['name']) ? $widget['name'] : 'UA PRO';
        
    }
    
    public function get_name() {
        return $this->name;
    }
    
    public function get_icon() {
        return $this->icon;
    }
    
    public function get_title() {
        return $this->title;
    }
    
    
    public function get_categories() {
        return [ 'ultraaddons-pro-placeholder' ];
    }
    
    public function render_backup(){
        //Use Posts widget and dozens more pro features to extend your toolbox and build sites faster and better.
        ?>
            
<div class="ua-pro-widger-placeholder ua-placeholder-<?php echo esc_attr( $this->name ); ?>">
    <div class="ua-inner-placeholder">
        <h3><?php echo esc_html( $this->title ); ?></h3>
        
        <div class="ua-plac-content">
            <p><?php
            echo sprintf( esc_html__( 'Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'ultraaddons' ), $this->title );
            ?></p>
            <p>
                <a href="<?php echo esc_url( ultraaddons_help_url( $this->name ) ); ?>" class="button button-readmore">
                    <?php echo esc_html__( 'Read More', 'ultraaddons' ); ?>
                </a>
            </p>
        </div>
    </div>
</div> 
        <?php
    }
    
}

