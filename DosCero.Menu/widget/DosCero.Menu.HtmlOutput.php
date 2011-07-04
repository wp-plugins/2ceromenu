<?php

//namespace DosCero\Menu {
  class DosCeroMenuMenuBarOutput implements DosCeroMenuHtmlRender {
    public $menuBar;
    public function __construct($menuBar) {
      $this->menuBar = $menuBar;
    }

    public function Render() {
      $position = ($this->menuBar->vertical) ? 'vertical' : 'horizontal';
      $righttoleft = ($this->menuBar->vertical && $this->menuBar->righttoleft) ? ' dropdown-vertical-rtl' : '';
      ?><div style="width: 100%;"><ul id="<?php echo $this->menuBar->htmlid ?>" class="dropdown dropdown-<?php echo $position.$righttoleft ?>"><?php
      foreach($this->menuBar->items as $item) {
        $obj = new DosCeroMenuMenuItemOutput($item);
        $obj->Render();
      }
      ?></ul></div><?php
    }
  }
  
  class DosCeroMenuMenuItemOutput implements DosCeroMenuHtmlRender {
    public $menuItem;
    public function __construct($menuItem) {
      $this->menuItem = $menuItem;
    }  
    public function Render() {
      $isTop = empty($this->menuItem->parentValue);
      $isSubSubItem = (!$isTop && !empty($this->menuItem->parentItem->parentValue) );
      $class = '';
      $hasChilds = count($this->menuItem->childs)>0;
      ?>
      <li class="<?php echo ($hasChilds) ? 'dir' : '' ?>">
      <a class="<?php echo $class ?>" href="<?php echo DosCeroMenuMenuItemLinkProvider::getLinkFor($this->menuItem) ?>" style="display: block;"><?php echo $this->menuItem->title; ?>
      <?php if ($hasChilds): ?>
        <!--[if gte IE 7]><!--></a><!--<![endif]-->
      <?php else: ?>
        </a>
      <?php endif; ?>
      <?php
      if ($hasChilds):
        ?><!--[if lte IE 6]><table><tr><td><![endif]--><ul><?php
        foreach($this->menuItem->childs as $child) {
          $obj = new DosCeroMenuMenuItemOutput($child);
          $obj->Render();
        }
        ?></ul><!--[if lte IE 6]></td></tr></table></a><![endif]--><?php
      endif;
      ?></li><?php
    }
  }
//}