<?php

//namespace DosCero\Menu {

  interface DosCeroMenuHtmlRender {
    public function Render();
  }
  
  include_once dirname( __FILE__ ) . '/DosCero.Menu.MenuTree.php';
  
  class DosCeroMenuMenuWidget extends WP_Widget {
  
    const POST = 'post';
    const PAGE = 'page';
    const CATEGORY = 'category';
    const EMPTYCONTENT = '';
    
    const CSS = '/DosCero.Menu/widget/css/dropdown.css';
    const CSSTHEME = '/DosCero.Menu/widget/css/theme.css';
    const CSSTHEMEORIGINAL = '/DosCero.Menu/widget/css/theme.original.css';
    const JS = '/DosCero.Menu/widget/js/dropdown.js';
  
    function __construct() {
      $widget_ops = array('classname' => '2Cero Menu Widget','description' => 'Menu widget.');
      $control_ops = array('width' => 700,'height' => 350,'id_base' => 'doscero_menu_widget');
      parent::__construct('doscero_menu_widget', '2Cero Menu Widget', $widget_ops, $control_ops);
      add_action('wp_print_styles', array($this,'renderWidgetExternalFiles'));
    }
    
    public static function getUrl($url) {
      return WP_PLUGIN_URL . $url;
    }
    
    public static function getFile($file) {
      return WP_PLUGIN_DIR . $file;
    }
    
    function renderWidgetExternalFiles() {
      if ( file_exists(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSS)) ) {
          wp_register_style('dosceroMenuWidgetCSS', DosCeroMenuMenuWidget::getUrl(DosCeroMenuMenuWidget::CSS));
          wp_enqueue_style( 'dosceroMenuWidgetCSS');
      }
      if ( file_exists(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEME)) ) {
          wp_register_style('dosceroMenuWidgetCSSTheme', DosCeroMenuMenuWidget::getUrl(DosCeroMenuMenuWidget::CSSTHEME));
          wp_enqueue_style( 'dosceroMenuWidgetCSSTheme');
      }
      if ( file_exists(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::JS)) ) {
        wp_enqueue_script('dosceroMenuWidgetJS', DosCeroMenuMenuWidget::getUrl(DosCeroMenuMenuWidget::JS), array('jquery'));
      }
    }

    
    function widget( $args, $instance ) {
      extract( $args );
      include_once dirname( __FILE__ ) . '/DosCero.Menu.HtmlOutput.php';
      $i = new DosCeroMenuMenuBarOutput(new DosCeroMenuMenuBar($instance));
      $i->Render();
    }
    
    private function filterEmptyValues($val) {
      return (!empty($val) || is_numeric($val));
    }
    
    function update( $new_instance, $old_instance ) {
      $instance = $new_instance;
      $keysToCheck = array('title');
      // Get only used values
      foreach($instance as $key => $subarr)
        if (is_array($subarr))
          $instance[$key] = array_filter($subarr, array($this,"filterEmptyValues"));
          
      // Get used keys
      $usedKeys = array();
      foreach($instance as $subarrKey => $subarr) {
        if (is_array($subarr) && in_array($subarrKey, $keysToCheck)) {
          foreach($subarr as $k => $v)
            if (!array_key_exists($k, $usedKeys))
              $usedKeys[] = $k;
        }
      }
      // Insert used keys where they don't exist
      foreach($instance as $arrKey => $subarr) {
        if (is_array($subarr)) {
          foreach($usedKeys as $k)
            if (!array_key_exists($k, $subarr))
              $instance[$arrKey][$k] = null;
        }
      }
      $instance['lastId'] = $instance['lastId']+1;
      return $instance;
    }

    function getFilteredParents($parents, $itm) {
      $ret = array();
      foreach($parents as $k => $v) {
        if ($k != $itm->mid)
          $ret[$k] = $v;
      }
      return $ret;
    }
    
    function getDefaultArgs() {
      return array( 'title' => array(), 'mid' => array(), 'lastId' => 1, 'htmlid' => 'dos0_m'.date('_dis'));
    }
    
    function form( $instance ) {
      include_once dirname( __FILE__ ) . '/DosCero.Menu.HtmlInput.php';
      $instance = wp_parse_args( (array) $instance, $this->getDefaultArgs() );
      
      $menubar = new DosCeroMenuMenuBar($instance);
      
      $parents = array(0 => '');
      foreach($menubar->items as $item) {
        $parents = $parents + $item->getIdsAndTitles();
      }
      
      $menubarItems = array();
      foreach($menubar->items as $item) {
        $menubarItems = $menubarItems + $item->getIdsAndItems();
      }
      $menubarItems['wontexistever'] = null;
      
      $contentTypes = array(DosCeroMenuMenuWidget::EMPTYCONTENT => ''
      , DosCeroMenuMenuWidget::PAGE => __('Page', DosCeroMenuPlugin::DOMAIN)
      , DosCeroMenuMenuWidget::POST => __('Post', DosCeroMenuPlugin::DOMAIN)
      , DosCeroMenuMenuWidget::CATEGORY => __('Category', DosCeroMenuPlugin::DOMAIN));
      
      $pageslist = get_pages();
      $pages = new DosCeroMenuSelectOptions(array());
      foreach($pageslist as $page)
        $pages->options[$page->ID]=$page->post_title;
      
      $postslist = get_posts(array('order'=> 'ASC', 'orderby' => 'title' ));
      $posts = new DosCeroMenuSelectOptions(array());
      foreach ($postslist as $post)
        $posts->options[$post->ID]=get_the_title($post->ID);
      
      $categorieslist = get_categories();
      $categories = new DosCeroMenuSelectOptions(array());
      foreach ($categorieslist as $cat)
        $categories->options[$cat->cat_ID]=$cat->cat_name;

      $empty = new DosCeroMenuSelectOptions(array(''=>''));
      
      $positionOptions = array('0' => 'Horizontal', '1' => 'Vertical');
      $righttoleftOptions = array('0' => __('Left to right', DosCeroMenuPlugin::DOMAIN), '1' => __('Right to left', DosCeroMenuPlugin::DOMAIN));
      
      $lastOrder = 0;
      ?>
        <div class="DosCero_MenuWidgetscontainer" style="width: 49%; float: left;">
        <?php foreach($menubarItems as $i => $itm): ?>
          <?php
          $new = (!in_array($i,$instance['mid']));
          $expanded = ($new) ? '-' : '+';
          $selectedContentIdList = $empty->options;
          switch($itm->type) {
            case DosCeroMenuMenuWidget::PAGE:
              $selectedContentIdList = $pages->options; break;
            case DosCeroMenuMenuWidget::POST:
              $selectedContentIdList = $posts->options; break;
            case DosCeroMenuMenuWidget::CATEGORY:
              $selectedContentIdList = $categories->options; break;
          }
          $pad = ($new) ? 0 : $itm->getDeep()*25;
          if (!$new) {
            $red = dechex(($itm->getDeep()*12)+102);
            $red = (strlen($red) == 1) ? '0'.$red : $red;
            $green = dechex(($itm->getDeep()*12)+143);
            $green = (strlen($green) == 1) ? '0'.$green : $green;
            $blue = dechex(($itm->getDeep()*12)+175);
            $blue = (strlen($blue) == 1) ? '0'.$blue : $blue;
            $color = '#'.$red.$green.$blue;
          }
          $thisOrder = ($new) ? $lastOrder+1 : ((empty($itm->order) ? $lastOrder+1 : $itm->order));
          if ($thisOrder > $lastOrder) $lastOrder = $thisOrder;
          ?>
          <div class="DosCero_MenuWidgetcontainer <?php echo ($new) ? 'DosCero_MenuWidgetcontainernew' : ''; ?>" style="background-color:<?php echo ($new) ? '#bdd0e2' : $color; ?>;<?php echo ($new) ? 'padding-top: 10px; padding-bottom: 5px;' : ''; ?>>">
            <div class="DosCero_MenuWidgettitle" style="padding-left: <?php echo $pad ?>px; padding-top: 5px; padding-bottom: 5px;">
              <?php
              $obj = new DosCeroMenuHiddenField(($new) ? $instance['lastId'] : $itm->mid, $this->get_field_id('mid').$i, $this->get_field_name('mid'), true); $obj->Render();
              $obj = new DosCeroMenuInputField('<a class="DosCero_MenuWidgetexpand" style="font-weight: bold; font-size: 1em; border: 1px solid; background-color: #ebebe9; padding: 1px 3px 1px 3px">'.$expanded.'</a> '.__('Title', DosCeroMenuPlugin::DOMAIN), $itm->title, $this->get_field_id('title').$i, $this->get_field_name('title'), true); $obj->Render();
              ?>
            </div>
            <div class="DosCero_MenuWidgetcontent" style="padding-left: <?php echo $pad ?>px;<?php echo ($new) ? '' : 'display: none;'; ?>">
              <?php
              $obj = new DosCeroMenuInputField(__('External link', DosCeroMenuPlugin::DOMAIN), $itm->link, $this->get_field_id('link').$i, $this->get_field_name('link'), true); $obj->Render(); 
              $obj = new DosCeroMenuSelectField(__('Content type', DosCeroMenuPlugin::DOMAIN), $contentTypes, $itm->type, $this->get_field_id('type').$i, $this->get_field_name('type'), true); 
                $obj->cssClass = 'DosCero_ContentType'; $obj->Render();
              $obj = new DosCeroMenuSelectField(__('Content', DosCeroMenuPlugin::DOMAIN), $selectedContentIdList, $itm->typeId, $this->get_field_id('typeId').$i, $this->get_field_name('typeId'), true); 
                $obj->cssClass = 'DosCero_ContentId'; $obj->Render();
              $obj = new DosCeroMenuSelectField(__('Parent', DosCeroMenuPlugin::DOMAIN), ($new) ? $parents : $this->getFilteredParents($parents,$itm), $itm->parentValue, $this->get_field_id('parent').$i, $this->get_field_name('parent'), true); $obj->Render();
              $obj = new DosCeroMenuInputField(__('Order', DosCeroMenuPlugin::DOMAIN), $thisOrder, $this->get_field_id('order').$i, $this->get_field_name('order'), true);
                $obj->style = 'width:15%;'; $obj->Render();
              ?>
              <div style="width: 90%; text-align:right; padding: 3px; padding-top: 5px; padding-bottom: 5px;">
                <a class="DosCero_MenuWidgetempty"><?php _e('Clear', DosCeroMenuPlugin::DOMAIN) ?></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
        <div style="width: 40%; float: left; text-align: left; margin-left: 40px; padding-left: 30px; border-left: solid 1px #CCC; ">
        <span style="font-size: 1em; font-family: Verdana, Geneva, sans-serif; color:#15759b; height: 200px;"><i><?php _e('Html Id (used for CSS or JS)', DosCeroMenuPlugin::DOMAIN) ?></i><br/><?php echo $instance['htmlid']; ?></span>  <br/><br/>
        <?php
        $obj = new DosCeroMenuSelectField(__('Position', DosCeroMenuPlugin::DOMAIN).'<br/>', $positionOptions, $instance['position'], $this->get_field_id('position'), $this->get_field_name('position')); $obj->Render();
        $obj = new DosCeroMenuSelectField(__('Expand mode (vertical)', DosCeroMenuPlugin::DOMAIN), $righttoleftOptions, $instance['righttoleft'], $this->get_field_id('righttoleft'), $this->get_field_name('righttoleft')); $obj->Render();
        $obj = new DosCeroMenuHiddenField($instance['htmlid'], $this->get_field_id('htmlid'), $this->get_field_name('htmlid')); $obj->Render();
        $obj = new DosCeroMenuHiddenField($instance['lastId'], $this->get_field_id('lastId'), $this->get_field_name('lastId')); $obj->Render();
        ?>
        </div>
      <script type="text/javascript">
        var DosCero_Selects = new Array(); 
        DosCero_Selects[0] = ['page', jQuery('<?php $pages->Render(); ?>')];
        DosCero_Selects[1] = ['post', jQuery('<?php $posts->Render();  ?>')];
        DosCero_Selects[2] = ['category', jQuery('<?php $categories->Render();  ?>')];
        DosCero_Selects[3] = ['', jQuery('<?php $empty->Render();  ?>')];
        
        function dosCeroContentTypeChange() {
          var ele = jQuery(this);
          var eleVal = ele.val();
          for(var i=0;i<DosCero_Selects.length;i++) {
            if (DosCero_Selects[i][0] == eleVal)
              ele.parents('.DosCero_MenuWidgetcontent').find('.DosCero_ContentId').html(DosCero_Selects[i][1]);
          }
        }
        jQuery(function() {
          jQuery('.DosCero_MenuWidgetempty').click(function() {
            jQuery(this).parents('.DosCero_MenuWidgetcontainer').find(':input').val('');
          });
          jQuery('.DosCero_MenuWidgetexpand').unbind('click').click(function() {
            var exp = jQuery(this);
            var txt = exp.text();
            exp
              .text((txt == '+') ? '-' : '+')
              .parents('.DosCero_MenuWidgetcontainer')
              .children('.DosCero_MenuWidgetcontent')
              .toggle();
          });
          jQuery('.DosCero_ContentType')
            .unbind('change').bind("change", { }, dosCeroContentTypeChange)
            .unbind('keyup').bind("keyup", { }, dosCeroContentTypeChange)
        });
      </script>
      <?php
    }
  }
//}