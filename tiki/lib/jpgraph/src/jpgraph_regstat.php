<?php 
/*=======================================================================
// File:	JPGRAPH_REGSTAT.PHP
// Description: Regression and statistical analysis helper classes
// Created: 	2002-12-01
// Author:	Johan Persson (johanp@aditus.nu)
// Ver:		$Id: jpgraph_regstat.php,v 1.1 2003-04-25 18:43:46 lrargerich Exp $
//
// License:	This code is released under QPL
// Copyright (C) 2002 Johan Persson
//========================================================================
*/

//------------------------------------------------------------------------
// CLASS Spline
// Create a new data array from an existing data array but with more points.
// The new points are interpolated using a cubic spline algorithm
//------------------------------------------------------------------------
class Spline {
    // 3:rd degree polynom approximation

    var $xdata,$ydata;   // Data vectors
    var $y2;		 // 2:nd derivate of ydata	
    var $n=0;

    function Spline($xdata,$ydata) {
	$this->y2 = array();
	$this->xdata = $xdata;
	$this->ydata = $ydata;

	$n = count($ydata);
	$this->n = $n;

	// Natural spline 2:derivate=0 at endpoints
	$this->y2[0]    = 0.0;
	$this->y2[$n-1] = 0.0;
	$delta[0] = 0.0;

	// Calculate 2:nd derivate
	for($i=1; $i < $n-1; ++$i) {
	    $s = ($xdata[$i]-$xdata[$i-1])/($xdata[$i+1]-$xdata[$i-1]);
	    $p = $s*$this->y2[$i-1]+2.0;
	    $this->y2[$i] = ($s-1.0)/$p;
	    $delta[$i] = ($ydata[$i+1]-$ydata[$i])/($xdata[$i+1]-$xdata[$i]) - 
		         ($ydata[$i]-$ydata[$i-1])/($xdata[$i]-$xdata[$i-1]);
	    $delta[$i] = (6.0*$delta[$i]/($xdata[$i+1]-$xdata[$i-1])-$s*$delta[$i-1])/$p;
	}
	// Backward substitution
	for( $j=$n-2; $j >= 0; --$j ) {
	    $this->y2[$j] = $this->y2[$j]*$this->y2[$j+1] + $delta[$j];
	}
    }

    // Return the two new data vectors
    function Get($num=50) {
	$n = $this->n ;
	$step = ($this->xdata[$n-1]-$this->xdata[0]) / ($num-1);
	$xnew=array();
	$ynew=array();
	$xnew[0] = $this->xdata[0];
	$ynew[0] = $this->ydata[0];
	for( $j=1; $j < $num; ++$j ) {
	    $xnew[$j] = $xnew[0]+$j*$step;
	    $ynew[$j] = $this->Interpolate($xnew[$j]);
	}
	return array($xnew,$ynew);
    }

    // Return a single interpolated Y-value from an x value
    function Interpolate($xpoint) {

	$max = $this->n-1;
	$min = 0;

	// Binary search to find interval
	while( $max-$min > 1 ) {
	    $k = ($max+$min) / 2;
	    if( $this->xdata[$k] > $xpoint ) 
		$max=$k;
	    else 
		$min=$k;
	}	

	// Each interval is interpolated by a 3:degree polynom function
	$h = $this->xdata[$max]-$this->xdata[$min];
	$a = ($this->xdata[$max]-$xpoint)/$h;
	$b = ($xpoint-$this->xdata[$min])/$h;
	return $a*$this->ydata[$min]+$b*$this->ydata[$max]+
	     (($a*$a*$a-$a)*$this->y2[$min]+($b*$b*$b-$b)*$this->y2[$max])*($h*$h)/6.0;
    }
}

// EOF
?>