<?php

class ImageAbstract {
  var $data = NULL;
  var $format = 'jpeg';
  var $height = NULL;
  var $width = NULL;
  var $classname = 'ImageAbstract';
  var $thumb_max_size = 120;

  function __construct($image, $isfile = false) {
    $this->classname = get_class($this);
    $this->data = $isfile ? $this->get_from_file($image) : $image;
  }

  function get_from_file($filename) {
    $content = '';
    if ( file_exists($filename) ) {
      $f = fopen($filename, 'rb');
      $size = filesize($filename);
      $content = fread($f, $size);
      fclose($f);
    }
    return $content;
  }

  function _resize($x, $y) { }

  function resize($x = 0, $y = 0) {
    $x0 = $this->get_width();
    $y0 = $this->get_height();

    if ( $x > 0 || $y > 0 ) {
      if ( $x <= 0 ) {
        $x = $x0 * ( $y / $y0 );
      }
      if ( $y <= 0 ) {
        $y = $y0 * ( $x / $x0 );
      }
      $this->_resize($x+0, $y+0);
    }
  }

  function resizemax($max) {
    $x0 = $this->get_width();
    $y0 = $this->get_height();
    if ( $x0 <= 0 || $y0 <= 0 || $max <= 0 ) return;
    if ( $x0 > $max || $y0 > $max ) {
      $r = $max / ( ( $x0 > $y0 ) ? $x0 : $y0 );
      $this->scale($r);
    }
  }

  function resizethumb() {
    $this->resizemax($this->thumb_max_size);
  }

  function scale($r) {
    $x0 = $this->get_width();
    $y0 = $this->get_height();
    if ( $x0 <= 0 || $y0 <= 0 || $r <= 0 ) return;
    $this->_resize($x0 * $r, $y0 * $r);
  }

  function get_mimetype() {
    return 'image/'.strtolower($this->get_format());
  }

  function set_format($format) {
    $this->format = $format;
  }

  function get_format() {
    if ( $this->format == '' ) {
      $this->set_format('jpeg');
      return 'jpeg';
    } else {
      return $this->format;
    }
  }

  function display() {
    return $this->data;
  }

  function convert($format) {
    if ( $this->is_supported($format) ) {
      $this->set_format($format);
      return true;
    } else {
      return false;
    }
  }
 
  function rotate() { }

  function is_supported($format) {
    return false;
  }

  function get_icon_default_format() {
    return 'png';
  }

  function get_icon_default_x() {
    return 16;
  }

  function get_icon_default_y() {
    return 16;
  }

  function icon($extension, $x = 0, $y = 0) {
    $keep_original = ( $x == 0 && $y == 0 );

    // This method is not necessarely called through an instance
    $class = isset($this) ? $this->classname : 'Image';
    $format = call_user_func(array($class, 'get_icon_default_format'));

    if ( ! $keep_original && class_exists($class) ) {
  
      $icon_format = $format;
      $class = 'Image';
  
      if ( call_user_func(array($class, 'is_supported'), 'svg') ) {
        $format = 'svg';
      } elseif ( call_user_func(array($class, 'is_supported'), 'png') ) {
        $format = 'png';
      } else {
        return false;
      }
  
    }

    $name = "lib/images/icons/$extension.$format";
    if ( ! file_exists($name) ) {
      $name = "lib/images/icons/unknown.$format";
    }

    if ( ! $keep_original ) {

      $icon = new $class($name, true);
      if ( $format != $icon_format ) {
        $icon->convert($icon_format);
      }
      $icon->resize($x, $y);

      return $icon->display();

    } else {

      return ImageAbstract::get_from_file($name);

    }

  } 

  function _get_height() { return NULL; }

  function _get_width() { return NULL; }

  function get_height() {
    if ( $this->height === NULL ) {
      $this->height = $this->_get_height();
    }
    return $this->height;
  }

  function get_width() {
    if ( $this->width === NULL ) {
      $this->width = $this->_get_width();
    }
    return $this->width;
  }
}
?>
