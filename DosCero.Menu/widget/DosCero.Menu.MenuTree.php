<?php

//namespace DosCero\Menu {
  class DosCeroMenuMenuBar {
    public $items = array();
    public $vertical = false;
    public $righttoleft = false;
    public $htmlid;
    
    private function sort($a, $b) {
      if ($a->order == $b->order)
        return 0;
      return ($a->order < $b->order) ? -1 : 1;
    }
    
    private function sortItems($items) {
      uasort($items,array($this,'sort'));
      foreach($items as $item)
        $item->childs = $this->sortItems($item->childs);
      return $items;
    }
    
    public function __construct($instance) {
      $keys = array_keys($instance['title']);
      foreach($keys as $k) { 
        $this->items[$instance['mid'][$k]]= new DosCeroMenuMenuItem(
          $instance['title'][$k]
          ,$instance['link'][$k]
          ,$instance['type'][$k]
          ,$instance['typeId'][$k]
          ,$instance['parent'][$k]
          ,$instance['order'][$k]
          ,$instance['mid'][$k]
        );
      }
      if ($instance['position'])
        $this->vertical = true;
      if ($instance['righttoleft'])
        $this->righttoleft = true;
      $this->htmlid = $instance['htmlid'];
      
      $parents = array();
      foreach($this->items as $item) {
        if (empty($item->parentValue)) {
          $parents[$item->mid] = $item;
        } else {
          $item->parentItem = $this->items[$item->parentValue];
          $this->items[$item->parentValue]->childs[$item->mid] = $item;
        }
      }
      $this->items = $this->sortItems($parents);
    }
  }
  
  class DosCeroMenuMenuItem {
    public $title;
    public $link = '';
    public $type;
    public $typeId;
    public $order;
    public $mid;
    public $parentValue = null;
    public $parentItem = null;
    public $childs = array();
    public $instanceKey;
    
    public function __construct($title, $link, $type, $typeId, $parentValue, $order, $mid) {
      $this->title = $title;
      $this->link = $link;
      $this->type = $type;
      $this->typeId = $typeId;
      $this->parentValue = $parentValue;
      $this->order = $order;
      $this->mid = $mid;
      $this->instanceKey = $mid;
    }
    
    public function getDeep() {
      $deep = 0;
      $par = $this->parentItem;
      while ($par != null)
      {
        $par = $par->parentItem;
        $deep++;
      }
      return $deep;
    }
    
    public function getIdsAndTitles() {
      $ret = array($this->instanceKey => str_repeat('>',$this->getDeep()).' '.$this->title);
      if (count($this->childs) > 0)
        foreach($this->childs as $child)
          $ret = $ret + $child->getIdsAndTitles();
      return $ret;
    }
    
    public function getIdsAndItems() {
      $ret = array($this->instanceKey => $this);
      if (count($this->childs) > 0)
        foreach($this->childs as $child)
          $ret = $ret + $child->getIdsAndItems();
      return $ret;
    }
  }
  
  class DosCeroMenuMenuItemLinkProvider {
    public static function getLinkFor($mi) {
      if (!empty($mi->link))
        return $mi->link;
      switch($mi->type) {
        case DosCeroMenuMenuWidget::CATEGORY:
          echo get_category_link($mi->typeId);
          break;
        case DosCeroMenuMenuWidget::PAGE:
        case DosCeroMenuMenuWidget::POST:
          return get_permalink($mi->typeId);
          break;
        case DosCeroMenuMenuWidget::EMPTYCONTENT:
        default:
          return '#';
      }
    }
  }
//}