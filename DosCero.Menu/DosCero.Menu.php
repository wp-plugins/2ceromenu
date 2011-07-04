<?php
/*
Plugin Name: 2Cero Menu
Plugin URI: http://www.2cero.com/blog/wordpress/plugin-de-wordpress-para-barra-de-navegacion
Description: A plugin to create a custom menu bar. Once you create the content, you can associate it with the menu. It's simple and easy to change the styles!
Version: 1.0
Author: 2Cero
Author URI: http://www.2cero.com/
License: 
*/

//namespace DosCero\Menu {

  include_once(dirname(__FILE__).'/widget/DosCero.Menu.Widget.php');
  
  class DosCeroMenuPlugin {
  
    const OPTION = 'dosCeroMenuPluginOption';
    const DOMAIN = 'dosCeroMenuDomain';
    private $addNew = 'Addnew';
    private $save = 'Save';
    private $remove = 'Remove';
    private $removeAll = 'Removeall';
  
    function __construct() {
      add_action('admin_menu',array($this,'admin_menu'));
      add_action('widgets_init', array($this,'widget_init') );
      add_action("plugins_loaded", array($this,"plugins_loaded"));
      //add_filter('the_content', array($this,'menu_render'),10);
      load_plugin_textdomain(DosCeroMenuPlugin::DOMAIN,false,dirname( plugin_basename( __FILE__ )) . '/lang/');
    }
    
    function DosCeroMenu($name = '') {
    }
    function renderAppareancePage() {
      if (isset($_POST) && count($_POST)) {
        $options = $this->update_options();
        if (isset($_POST['menuId'])) {
          ?>
          <script type="text/javascript">
          jQuery(document).ready(function(){
            var jwindow = jQuery(window);
            var jwindowOffsetTop = jwindow.offset();
            if (jwindowOffsetTop == null) jwindowOffsetTop = 0;
            else jwindowOffsetTop = jwindowOffsetTop.top;
            var jitemoffsettop = jQuery("input[name=menuId][value=<?php echo $_POST['menuId'] ?>]").parents('form').find('.DosCero_MenuWidgetcontainernew').find('input:visible').first()
              .focus().offset().top;
            if ((jitemoffsettop - jwindowOffsetTop - 50) > 0) jwindow.scrollTop(jitemoffsettop - jwindowOffsetTop - 50);
            else jwindow.scrollTop(jitemoffsettop - jwindowOffsetTop);
          });
          </script>
          <?php
        }
        if (isset($_POST[$this->addNew])) {
          ?>
          <script type="text/javascript">
          jQuery(document).ready(function(){
            var jwindow = jQuery(window);
            var jwindowOffsetTop = jwindow.offset();
            if (jwindowOffsetTop == null) jwindowOffsetTop = 0;
            else jwindowOffsetTop = jwindowOffsetTop.top;
            var jitemoffsettop = jQuery('.DosCero_MenuWidgetcontainernew').last().find('input:visible')
              .first().focus().offset().top;
            if ((jitemoffsettop - jwindowOffsetTop - 50) > 0) jwindow.scrollTop(jitemoffsettop - jwindowOffsetTop - 50);
            else jwindow.scrollTop(jitemoffsettop - jwindowOffsetTop);
          });
          </script>
          <?php
        }
      } else
        $options = self::get_options();
      
      $instances = $options['instances'];
      $lastId = $options['lastId'];
      ?>
      <style type="text/css">
        .styleline {
          padding-top: 10px;
        }
        .dosceroContentDiv {
          padding: 10px;
        }
        .dosceroTitle {
          font-weight: bold;
          font-size: 1.4em;
        }
      </style>
      <p class="dosceroTitle"><?php _e('Create or edit your 2cero menu!', DosCeroMenuPlugin::DOMAIN)?></p>
      <?php $this->renderInstructions() ?>
      <form action="" method="post" style="margin: auto; width: 100%; ">
        <input type="hidden" name="lastId" value="<?php echo $lastId ?>" />
        <input type="submit" name="<?php echo $this->addNew ?>" value="<?php _e('Add new', DosCeroMenuPlugin::DOMAIN) ?>" />
        <input type="submit" name="<?php echo $this->removeAll ?>" value="<?php _e('Remove all', DosCeroMenuPlugin::DOMAIN) ?>" />
      </form>
      <?php
      foreach($instances as $k => $v):
        ?>
        <div style="border: 1px solid #e3e3e3; margin: 5px; padding: 15px; width: 80%; background-color: #f1f1f1">
          <form action="" method="post" style="margin: auto; width: 100%; ">
          <?php
          $new = ('new' == $k);
          $menuId = ($new) ? $lastId : $k;
          ?><div style="clear: both; font-family:Georgia, 'Times New Roman', Times, serif; color:#2175b9; font-size: 17px; padding-bottom: 15px;"><b>Menu ID: <?php echo $menuId ?></b></div><?php
          $wid = new DosCeroMenuMenuWidget();
          $wid->_set($menuId);
          $wid->form($v);
          ?>
          <div style="float: right; padding: 10px 40px; text-align: middle;">
          <input type="hidden" name="menuId" value="<?php echo $menuId ?>" />
          <input type="hidden" name="lastId" value="<?php echo $lastId ?>" />
          <input type="submit" name="<?php echo $this->save ?>" value="<?php _e('Save', DosCeroMenuPlugin::DOMAIN) ?>" />
          <input type="submit" name="<?php echo $this->remove ?>" value="<?php _e('Remove', DosCeroMenuPlugin::DOMAIN) ?>" />
          </div>
          <div style="clear: both"></div>
          </form>
        </div>
        <?php
      endforeach;
    }
    
    function renderInstructions() {
      ?>
      <div id="DosCero_Menu_Instructions">
          <p>
            <?php _e("click on <b>'ADD NEW'</b> and you're ready!", DosCeroMenuPlugin::DOMAIN) ?>
          </p>
          <p>
            <?php _e("remember that <b>'MENU ID'</b> is the number that you will later use in the PHP code to show the menu on your site", DosCeroMenuPlugin::DOMAIN) ?>
          </p>
          <p>
            <?php _e("<b>HTML ID:</b> is the html unique id given to your menu. That code will allow you to apply css style or javascript to that particular menu", DosCeroMenuPlugin::DOMAIN) ?>
          </p>
          <p>
            <?php _e("<b>Layout:</b> show the menu either on a horizontal (default) or vertical fasion", DosCeroMenuPlugin::DOMAIN) ?>
          </p>
          <p>
            <?php _e("<b>Expansion mode (vertical):</b> the subitems/buttons that are expanded can be configured to be shown from right to left or left to right. This is only valid for vertical menues.", DosCeroMenuPlugin::DOMAIN) ?>
          </p>
      </div>
      <?php
    }
    
    private static function get_options() {
      return get_option(DosCeroMenuPlugin::OPTION
        , array(
          'instances' => array()
          ,'lastId' => 1
        )
      );
    }
    
    function update_options() {
      $options = self::get_options();
      if (isset($_POST[$this->addNew])):
        $wid = new DosCeroMenuMenuWidget();
        $options['instances']['new'] = $wid->getDefaultArgs();
      endif;
      if (isset($_POST[$this->save])):
        $menuId = $_POST['menuId'];
        $lastId = $_POST['lastId'];
        $widgetDosCeroMenuWidget = $_POST['widget-doscero_menu_widget'];
        $wid = new DosCeroMenuMenuWidget();
        $options['instances'][$menuId] = $wid->update($widgetDosCeroMenuWidget[$menuId], array());
        if ($lastId == $menuId)
          $options['lastId']++;
        update_option(DosCeroMenuPlugin::OPTION, $options);
      endif;
      if (isset($_POST[$this->remove])):
        $menuId = $_POST['menuId'];
        unset($options['instances'][$menuId]);
        update_option(DosCeroMenuPlugin::OPTION, $options);
      endif;
      if (isset($_POST[$this->removeAll])):
        delete_option(DosCeroMenuPlugin::OPTION);
        $options = self::get_options();
      endif;
      return $options;
    }
    
    function renderConfigurationPage() {
      include_once(dirname(__FILE__).'/DosCero.Menu.Config.php');
    }
    
    function renderStylePage() {
      include_once(dirname(__FILE__).'/DosCero.Menu.Style.php');
    }
    
    function renderHelpPage() {
      include_once(dirname(__FILE__).'/DosCero.Menu.Help.php');
    }
    
    function admin_menu() {
      add_submenu_page('themes.php', __('2cero menu', DosCeroMenuPlugin::DOMAIN), __('2cero menu', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_ap_menu', array($this, 'renderAppareancePage'));
      add_menu_page(__('2cero menu', DosCeroMenuPlugin::DOMAIN), __('2cero menu', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_ap', array($this, 'renderConfigurationPage'), WP_PLUGIN_URL . '/DosCero.Menu/img/2ceroicon.png');
      add_submenu_page('DosCeroMenu_ap', __('2cero menu configuration', DosCeroMenuPlugin::DOMAIN), __('Configuration', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_ap', array($this, 'renderConfigurationPage'));
      add_submenu_page('DosCeroMenu_ap', __('2cero menu create or edit', DosCeroMenuPlugin::DOMAIN), __('Create or edit', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_createEdit', array($this, 'renderAppareancePage'));
      add_submenu_page('DosCeroMenu_ap', __('2cero menu styles', DosCeroMenuPlugin::DOMAIN), __('Styles', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_style', array($this, 'renderStylePage'));
      add_submenu_page('DosCeroMenu_ap', __('2cero menu help', DosCeroMenuPlugin::DOMAIN), __('Help', DosCeroMenuPlugin::DOMAIN), 'manage_options', 'DosCeroMenu_help', array($this, 'renderHelpPage'));
    }
    
    function widget_init() {
      //register_widget( '\DosCero\Menu\MenuWidget' );
      register_widget( 'DosCeroMenuMenuWidget' );
    }
    
    function plugins_loaded() {
      //register_sidebar_widget(__('2Cero Menu', DosCeroMenuPlugin::DOMAIN), '\DosCero\Menu\MenuWidget');
      register_sidebar_widget(__('2Cero Menu', DosCeroMenuPlugin::DOMAIN), 'DosCeroMenuMenuWidget');
    }
    
    function menu_render($content) {
      $regStart = '<\!--2CeroMenu\(';
      $regEnd = '\)-->';
      $regSearch = '/'.$regStart.'(\d+)'.$regEnd.'/';
      if (!preg_match_all($regSearch, $content, $matches)) return;
      foreach($matches as $k => $match) {
        if ($k == 0) continue;
        $matchNumber = $match[0];
        $widcontent = self::Render($matchNumber, true);
        if (strlen($widcontent)>0)
          $content = preg_replace('/'.$regStart.$matchNumber.$regEnd.'/',$widcontent,$content);
      }
      return $content;
    }
    
    public static function Render($menuId, $returnContent = false) {
      $options = self::get_options();
      if (!isset($options['instances'][$menuId])) return '';
      $wid = new DosCeroMenuMenuWidget();
      $widcontent = '';
      ob_start();
      $wid->widget(array(),$options['instances'][$menuId]);
      $widcontent = ob_get_contents();
      ob_end_clean();
      if ($returnContent)
        return $widcontent;
      echo $widcontent;
    }
  }
  
//}

//namespace {
  //$dosCeroPlugin = new \DosCero\Menu\Plugin;
  $dosCeroPlugin = new DosCeroMenuPlugin;
//}
?>