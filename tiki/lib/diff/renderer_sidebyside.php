<?php
// $Header: /cvsroot/tikiwiki/tiki/lib/diff/renderer_sidebyside.php,v 1.1 2004-07-01 22:33:42 teedog Exp $

/**
 * "Side-by-Side" diff renderer.
 *
 * This class renders the diff in "side-by-side" format, like Wikipedia.
 *
 * @package Text_Diff
 */
class Text_Diff_Renderer_sidebyside extends Text_Diff_Renderer {

    function Text_Diff_Renderer_sidebyside($context_lines = 4)
    {
        $this->_leading_context_lines = $context_lines;
        $this->_trailing_context_lines = $context_lines;
    }
    
    function _startDiff()
    {
        ob_start();
        echo '<table class="normal">';
    }

    function _endDiff()
    {
        echo '</table>';
        $val = ob_get_contents();
        ob_end_clean();
        return $val;
    }

    function _blockHeader($xbeg, $xlen, $ybeg, $ylen)
    {
        return "Line: $xbeg";
    }

    function _startBlock($header)
    {
        echo '<tr class="diffheader"><td></td><td>'.$header.'</td><td></td><td>'.$header.'</tr>';
    }

    function _endBlock()
    {
    }

    function _lines($type, $lines, $prefix = '')
    {
    	if ($type == 'context') {
	        foreach ($lines as $line) {
	            echo "<tr class='diffbody'><td></td><td>$line</td><td></td><td>$line</td></tr>\n";
	        }
    	} elseif ($type == 'added') {
	        foreach ($lines as $line) {
	            echo "<tr class='diffadded'><td colspan='2'></td><td>$prefix</td><td>$line</td></tr>\n";
	        }
    	} elseif ($type == 'deleted') {
	        foreach ($lines as $line) {
	            echo "<tr class='diffdeleted'><td>$prefix</td><td>$line</td><td colspan='2'></td></tr>\n";
	        }
    	}
    }

    function _context($lines)
    {
        $this->_lines('context', $lines);
    }

    function _added($lines)
    {
        $this->_lines('added', $lines, '+');
    }

    function _deleted($lines)
    {
        $this->_lines('deleted', $lines, '-');
    }

    function _changed($orig, $final)
    {
        $this->_deleted($orig);
        $this->_added($final);
    }

}
