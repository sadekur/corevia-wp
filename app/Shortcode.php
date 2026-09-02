<?php
namespace Corevia\Base\App;

use Corevia\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @package Plugin
 * @subpackage Shortcode
 * @author Sadekur Rahman <shadekur.rahman60@gmail.com>
 */
class Shortcode extends Base {

    public $plugin;
    
    public $slug;

    public $name;

    public $version;

    /**
     * Constructor function
     */
    public function __construct() {
        $this->plugin   = BASE;
        $this->slug     = $this->plugin['TextDomain'];
        $this->name     = $this->plugin['Name'];
        $this->version  = $this->plugin['Version'];
    }

    public function my_shortcode() {
        return __( 'My Shortcode', 'base' );
    }
}