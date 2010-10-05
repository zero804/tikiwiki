<?php

class WikiParser_OutputLink
{
	private $description;
	private $identifier;
	private $language;
	private $qualifier;

	private $externals = array();
	private $handlePlurals = false;

	private $wikiLookup;
	private $wikiBuilder = 'trim';

	function setIdentifier( $identifier ) {
		$this->identifier = $identifier;
	}

	function setDescription( $description ) {
		$this->description = $description;
	}

	function setQualifier( $qualifier ) {
		$this->qualifier = $qualifier;
	}

	function setLanguage( $lang ) {
		$this->language = $lang;
	}

	function setWikiLookup( $lookup ) {
		$this->wikiLookup = $lookup;
	}

	function setWikiLinkBuilder( $builder ) {
		$this->wikiBuilder = $builder;
	}

	function setExternals( array $externals ) {
		$this->externals = $externals;
	}

	function setHandlePlurals( $handle ) {
		$this->handlePlurals = (bool) $handle;
	}

	function getHtml() {
		$page = $this->identifier;
		$description = $this->identifier;
		if( $this->description ) {
			$description = $this->description;
		}

		if( $link = $this->handleExternal( $page, $description ) ) {
			return $this->outputLink( $description, array(
				'href' => $link,
				'class' => 'wiki external',
			) );
		} elseif( $info = $this->findWikiPage( $page ) ) {
			if ($info['pageName']) {
				$page = $info['pageName'];
			}
			$title = $page;
			if( $info['description'] ) {
				$title = $info['description'];
			}

			return $this->outputLink( $description, array(
				'href' => call_user_func( $this->wikiBuilder, $page ),
				'title' => $title,
				'class' => 'wiki',
			) );
		} else {
			return $description . $this->outputLink( '?', array(
				'href' => $this->getEditLink( $page ),
				'title' => tra('Create page:') . ' ' . $page,
				'class' => 'wiki wikinew',
			) );
		}
	}

	private function outputLink( $text, array $attributes ) {
		if( $this->qualifier ) {
			$attributes['class'] .= ' ' . $this->qualifier;
		}

		$string = '<a';
		foreach( $attributes as $attr => $val ) {
			$string .= " $attr=\"" . htmlentities( $val, ENT_QUOTES, 'UTF-8' ) . '"';
		}
		
		$string .= '>' . htmlentities( $text, ENT_QUOTES, 'UTF-8' ) . '</a>';

		return $string;
	}

	private function getEditLink( $page ) {
		$url = 'tiki-editpage.php?page=' . urlencode($page);

		if( $this->language ) {
			$url .= '&lang=' . urlencode( $this->language );
		}

		return $url;
	}

	private function handleExternal( & $page, & $description ) {
		$parts = explode( ':', $page );

		if( count( $parts ) == 2 ) {
			list( $token, $remotePage ) = $parts;

			if( isset( $this->externals[$token] ) ) {
				if( $page == $description ) {
					$description = $remotePage;
				}

				$page = $remotePage;
				$pattern = $this->externals[$token];
				return str_replace( '$page', urlencode( $page ), $pattern );
			}
		}
	}

	private function findWikiPage( $page ) {
		if( ! $this->wikiLookup ) {
			return;
		}

		if( $info = call_user_func( $this->wikiLookup, $page ) ) {
			return $info;
		} elseif( $alternate = $this->handlePlurals( $page ) ) {
			return call_user_func( $this->wikiLookup, $alternate );
		}
	}

	private function handlePlurals( $page ) {
		if( ! $this->handlePlurals ) {
			return;
		}

		$alternate = $page;
		// Plurals like policy / policies
		$alternate = preg_replace("/ies$/", "y", $alternate);
		// Plurals like address / addresses
		$alternate = preg_replace("/sses$/", "ss", $alternate);
		// Plurals like box / boxes
		$alternate = preg_replace("/([Xx])es$/", "$1", $alternate);
		// Others, excluding ending ss like address(es)
		$alternate = preg_replace("/([A-Za-rt-z])s$/", "$1", $alternate);

		if( $alternate != $page ) {
			return $alternate;
		}
	}
}

