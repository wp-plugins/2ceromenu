<?php
//namespace DosCero\Menu {

include_once(dirname(__FILE__).'/widget/DosCero.Menu.Widget.php');

class DosCeroMenuHelp {

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
    </style>
    <div class="dosceroContentDiv">
      <p class="dosceroTitle">
        <?php _e('Help 2Cero to improve!', DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
        <?php _e("We hope that this plugin suits you as it did for us and our clients. That is why the only thing that we ask from you is to give us some feedback about it.", DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
        <?php _e("Here are some questions that we make to ourselves when working with plugins:", DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
        <b>
        <?php _e("Did you like it? Did it work on the first try? Could you adjust the design easily? Did you have any compability trouble with other plugins? Do you believe in extraterrestrial life?", DosCeroMenuPlugin::DOMAIN) ?>
        </b>
      </p>
      <p>
        <b>
        <?php _e("email: pluginmenu@2cero.com", DosCeroMenuPlugin::DOMAIN) ?>
        </b>
      </p>
    </div>
  <?php
  }
}

//}

//namespace {
  //$obj = new \DosCero\Menu\Help();
  $obj = new DosCeroMenuHelp();
  $obj->Render();
//}
?>