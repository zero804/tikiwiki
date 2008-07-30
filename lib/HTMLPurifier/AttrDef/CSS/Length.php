<?php

require_once 'HTMLPurifier/Length.php';
require_once 'HTMLPurifier/UnitConverter.php';

/**
 * Represents a Length as defined by CSS.
 */
class HTMLPurifier_AttrDef_CSS_Length extends HTMLPurifier_AttrDef
{
    
    var $min, $max;
    
    /**
     * @param HTMLPurifier_Length $max Minimum length, or null for no bound. String is also acceptable.
     * @param HTMLPurifier_Length $max Maximum length, or null for no bound. String is also acceptable.
     */
    function HTMLPurifier_AttrDef_CSS_Length($min = null, $max = null) {
        $this->min = $min !== null ? HTMLPurifier_Length::make($min) : null;
        $this->max = $max !== null ? HTMLPurifier_Length::make($max) : null;
    }
    
    function validate($string, $config, $context) {
        $string = $this->parseCDATA($string);
        
        // Optimizations
        if ($string === '') return false;
        if ($string === '0') return '0';
        if (strlen($string) === 1) return false;
        
        $length = HTMLPurifier_Length::make($string);
        if (!$length->isValid()) return false;
        
        if ($this->min) {
            $c = $length->compareTo($this->min);
            if ($c === false) return false;
            if ($c < 0) return false;
        }
        if ($this->max) {
            $c = $length->compareTo($this->max);
            if ($c === false) return false;
            if ($c > 0) return false;
        }
        
        return $length->toString();
    }
    
}

