<?php
//namespace DosCero\Menu {

include_once(dirname(__FILE__).'/widget/DosCero.Menu.Widget.php');

class DosCeroMenuConfig {

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
        <?php _e('Welcome to 2cero menu!', DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
      <?php _e('This plugin allows you to create and administrate menu bars in a website.', DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
      <?php _e('With it you will be able to create different menues and allow the content to be associated to fit your needs.', DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
      </p>
      <p class="dosceroSubtitle">
        <?php _e('Basic configuration', DosCeroMenuPlugin::DOMAIN) ?>
      </p>
      <p>
        <ol>
          <li>
            <p>
            <?php _e("<b>Create site content:</b> the posts, articles, categories, pages, etc. that will be later used to associate with this plugin", DosCeroMenuPlugin::DOMAIN) ?>
            </p>
          </li>
          <li>
            <p>
            <?php _e("<b>Create the menu:</b> you can do this by using the <b>'ADD NEW'</b> button, there you will be able to start to relate the content previously created", DosCeroMenuPlugin::DOMAIN) ?>
            </p>
          </li>
          <li>
            <p>
            <?php _e("<b>Insert the code:</b> insert the following PHP code where you want to show the menu (probably replacing the call to 'wp_nav_menu' in header.php):", DosCeroMenuPlugin::DOMAIN) ?>
            <pre>&lt;?php DosCeroMenuPlugin::Render(ID); ?&gt;</pre>
            </p>
            <p>
            <?php _e("Where it says <b>'Render(ID)'</b> you must use the number that is displayed in the <b>'MENU ID'</b> item in each created menu.", DosCeroMenuPlugin::DOMAIN) ?>
            </p>
            <p>
            <?php _e("<b>Another way to create a menu (using WIDGETS):</b> if you use the widget provided by this plugin <b>(2cero menu widget)</b>, it's not necessary to write any special code. The menubar will be shown where the widget was dropped.", DosCeroMenuPlugin::DOMAIN) ?>
            </p>
          </li>
          <li>
            <p>
            <?php _e("<b>Style & design:</b> once you followed the previous steps you can give a different style to our menubar. To do that, you can go to the 'Styles' page and modify all the property that you need to make it look as you want. Each property is explained to make this easier.", DosCeroMenuPlugin::DOMAIN) ?>
            </p>
          </li>
        </ol>
      <p>
    </div>
  <?php
  }
}

//}

//namespace {
  //$obj = new \DosCero\Menu\Config();
  $obj = new DosCeroMenuConfig();
  $obj->Render();
//}
?>