<?php

//namespace DosCero\Menu {
  class DosCeroMenuHiddenField implements DosCeroMenuHtmlRender {
    public $value;
    public $id;
    public $name;
    public $isArray;
    public $divStyle = '';
    public $divCssClass = '';
    public $style = '';
    public $cssClass = '';
    
    public function __construct($value, $id, $name, $isArray = false) {
      $this->value = $value;
      $this->id = $id;
      $this->name = $name;
      $this->isArray = $isArray;
      $this->style = 'width:60%;';
      $this->cssClass ='';
    }
    
    public function Render() {
      $isArr = ($this->isArray) ? '[]' : '';
      ?><input type="hidden" class="<?php echo $this->cssClass; ?>" id="<?php echo $this->id; ?>" name="<?php echo $this->name.$isArr; ?>" value="<?php echo $this->value; ?>" /><?php
    }
  }
  
  class DosCeroMenuInputField extends DosCeroMenuHiddenField {
    public $title;
    
    public function __construct($title, $value, $id, $name, $isArray = false) {
      $this->title = $title;
      parent::__construct($value, $id, $name, $isArray);
      
      $this->divStyle .= 'width:90%; display:inline-block; text-align:left; padding-left:10px; padding-top:4px; padding-bottom:4px;';
    }
    
    public function Render() {
      $isArr = ($this->isArray) ? '[]' : '';
      ?>
          <div style="<?php echo $this->divStyle ?>">
            <label for="<?php echo $this->id; ?>"><?php echo $this->title; ?></label>
            <input style="<?php echo $this->style; ?>" id="<?php echo $this->id; ?>" class="<?php echo $this->cssClass; ?>" name="<?php echo $this->name.$isArr; ?>" value="<?php echo $this->value; ?>" />
          </div>
      <?php
    }
  }
  
  class DosCeroMenuSelectField extends DosCeroMenuInputField {
    public $options;
    
    public function __construct($title, $options, $value, $id, $name, $isArray = false) {
      $this->options = $options;
      parent::__construct($title, $value, $id, $name, $isArray);
    }
    
    public function Render() {
      $isArr = ($this->isArray) ? '[]' : '';
      ?>
          <div style="<?php echo $this->divStyle ?>">
            <label for="<?php echo $this->id; ?>"><?php echo $this->title; ?></label>
            <select class="<?php echo $this->cssClass; ?>" style="<?php echo $this->style; ?>" id="<?php echo $this->id; ?>" name="<?php echo $this->name.$isArr; ?>">
              <?php  $obj = new DosCeroMenuSelectOptions($this->options, $this->value); $obj->Render(); ?>
            </select>
          </div>
      <?php
    }
  }
  
  class DosCeroMenuSelectOptions implements DosCeroMenuHtmlRender {
    public $options;
    public $selectedValue;
    
    public function __construct($options, $selectedValue = null) {
      $this->options = $options;
      $this->selectedValue = $selectedValue;
    }
    
    public function Render() {
      foreach($this->options as $optk => $optv) {
          ?><option value="<?php echo $optk ?>" <?php echo ($optk == $this->selectedValue) ? 'selected' : ''; ?>><?php echo $optv ?></option><?php 
      }
    }
  }
//}