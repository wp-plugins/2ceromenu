<?php
//namespace DosCero\Menu {

include_once(dirname(__FILE__).'/widget/DosCero.Menu.Widget.php');

class DosCeroMenuStyle {

  public static $name_CSSTHEME = 'csstheme';
  
  public function Render() {
    ?>
    <style type="text/css">
      .styleline {
        padding-top: 10px;
      }
      .dosceroContentDiv {
        padding: 10px;
        width: 95%;
      }
      .dosceroTitle {
        font-weight: bold;
        font-size: 1.4em;
      }
	  
	  
	  label {
        text-align: left;
        
      }
    </style>
    <div class="dosceroContentDiv">
      <p class="dosceroTitle"><?php _e('Shape your 2cero menu!', DosCeroMenuPlugin::DOMAIN) ?></p>
      <p>
        <?php _e("Now you can give the menu the style and design that you want.", DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
        <?php _e("In the following textarea you can edit the properties and, if you make something wrong, you can easily start again with the original by pressing the 'go back to original' button!", DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <div class="styleline">
        <?php _e('File', DosCeroMenuPlugin::DOMAIN) ?>: <b><?php echo DosCeroMenuMenuWidget::CSSTHEME ?></b>
      </div>
      <form action="" method="POST">
        <div class="styleline">
          <textarea rows="26" cols="118" name="<?php echo self::$name_CSSTHEME ?>"><?php
            if (file_exists(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEME))) {
              readfile(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEME));
            }
          ?></textarea>
        </div>
        <div class="styleline">
          <input type="submit" name="save" value="<?php _e('save', DosCeroMenuPlugin::DOMAIN) ?>" />
          <input type="submit" name="restore" value="<?php _e('go back to original', DosCeroMenuPlugin::DOMAIN) ?>" />
        </div>
      </form>
    </div>
    <?php
  }
  
  public function Save($content) {
    $file = DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEME);
    if (!is_writeable($file))
      throw new \Exception("File is not writeable, you probably don't have write permissions on it.");
    $fh = fopen($file, 'w');
    fwrite($fh, stripslashes($content));
    fclose($fh);
  }
  
  public function Restore() {
    $originalFile = DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEMEORIGINAL);
    if (file_exists(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEMEORIGINAL)))
      $this->Save(file_get_contents(DosCeroMenuMenuWidget::getFile(DosCeroMenuMenuWidget::CSSTHEMEORIGINAL)));
  }
}

//}
//namespace {
  //$obj = new \DosCero\Menu\Style();
  $obj = new DosCeroMenuStyle();
  if (isset($_POST['save']) && isset($_POST[DosCeroMenuStyle::$name_CSSTHEME])) {
    try {
      $obj->Save($_POST[DosCeroMenuStyle::$name_CSSTHEME]);
    } catch(\Exception $e) {
      echo $e->getMessage();
    }
  }
  if ($_POST['restore']) {
    try {
      $obj->Restore();
    } catch(\Exception $e) {
      echo $e->getMessage();
    }
  }
  $obj->Render();
//}
?>