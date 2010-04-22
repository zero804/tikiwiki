<?php

class BigBlueButtonLib
{
	public function getMeetings() {
		global $cachelib;

		if( ! $meetings = $cachelib->getSerialized( 'bbb_meetinglist' ) ) {
			$meetings = array();

			if( $dom = $this->performRequest( 'getMeetings', array( 'random' => 1 ) ) ) {
				foreach( $dom->getElementsByTagName( 'meeting' ) as $node ) {
					$meetings[] = $this->grabValues( $node );
				}
			}

			$cachelib->cacheItem( 'bbb_meetinglist', serialize( $meetings ) );
		}

		return $meetings;
	}

	public function getAttendees( $room ) {
		if( $meeting = $this->getMeeting( $room ) ) {
			if( $dom = $this->performRequest( 'getMeetingInfo', array( 'meetingID' => $room, 'password' => $meeting['moderatorPW'] ) ) ) {

				$attendees = array();

				foreach( $dom->getElementsByTagName( 'attendee' ) as $node ) {
					$attendees[] = $this->grabValues( $node );
				}

				return $attendees;
			}
		}
	}

	private function grabValues( $node ) {
		$values = array();

		foreach( $node->childNodes as $n ) {
			if( $n instanceof DOMElement ) {
				$values[$n->tagName] = $n->textContent;
			}
		}

		return $values;
	}

	public function roomExists( $room ) {
		foreach( $this->getMeetings() as $meeting ) {
			if( $meeting['meetingID'] == $room ) {
				return true;
			}
		}

		return false;
	}

	public function createRoom( $room, array $params = array() ) {
		global $tikilib, $cachelib;

		$params = array_merge( array(
			'logout' => $tikilib->tikiUrl(''),
		), $params );

		$request = array(
			'name' => $room,
			'meetingID' => $room,
			'logoutURL' => $params['logout'],
		);

		if( isset( $params['welcome'] ) ) {
			$request['welcome'] = $params['welcome'];
		}
		if( isset( $params['number'] ) ) {
			$request['dialNumber'] = $params['number'];
		}
		if( isset( $params['voicebridge'] ) ) {
			$request['voiceBridge'] = $params['voicebridge'];
		}
		if( isset( $params['logout'] ) ) {
			$request['logoutURL'] = $params['logout'];
		}
		if( isset( $params['max'] ) ) {
			$request['maxParticipants'] = $params['max'];
		}

		$this->performRequest( 'create', $request );
		$cachelib->invalidate( 'bbb_meetinglist' );
	}

	public function joinMeeting( $room ) {
		$name = $this->getAttendeeName();
		$password = $this->getAttendeePassword( $room );

		if( $name && $password ) {
			$this->joinRawMeeting( $room, $name, $password );
		}
	}

	private function getAttendeeName() {
		global $u_info;

		if( isset( $u_info['prefs']['realName'] ) ) {
			return $u_info['prefs']['realName'];
		} elseif( $u_info['login'] ) {
			return $u_info['login'];
		} else {
			return tra('anonymous');
		}
	}

	private function getAttendeePassword( $room ) {
		if( $meeting = $this->getMeeting( $room ) ) {
			$perms = Perms::get( 'bigbluebutton', $room );

			if( $perms->bigbluebutton_moderate ) {
				return $meeting['moderatorPW'];
			} else {
				return $meeting['attendeePW'];
			}
		}
	}

	private function getMeeting( $room ) {
		$meetings = $this->getMeetings();

		foreach( $meetings as $meeting ) {
			if( $meeting['meetingID'] == $room ) {
				return $meeting;
			}
		}
	}

	public function joinRawMeeting( $room, $name, $password ) {
		$url = $this->buildUrl( 'join', array(
			'meetingID' => $room,
			'fullName' => $name,
			'password' => $password,
		) );

		header( 'Location: ' . $url );
		exit;
	}

	private function performRequest( $action, array $parameters ) {
		global $tikilib;

		$url = $this->buildUrl( $action, $parameters );

		if( $result = $tikilib->httprequest( $url ) ) {
			$dom = new DOMDocument;
			if( $dom->loadXML( $result ) ) {
				$nodes = $dom->getElementsByTagName( 'returncode' );

				if( $nodes->length > 0 && ($returnCode = $nodes->item(0)) && $returnCode->textContent == 'SUCCESS' ) {
					return $dom;
				}
			}
		}
	}

	private function buildUrl( $action, array $parameters ) {
		global $prefs;
		if( $checksum = $this->generateChecksum( $parameters ) ) {
			$parameters['checksum'] = $checksum;
		}

		$base = rtrim( $prefs['bigbluebutton_server_location'], '/' );

		return "$base/bigbluebutton/api/$action?" . http_build_query( $parameters, '', '&' );
	}

	private function generateChecksum( array $parameters ) {
		global $prefs;

		if( $prefs['bigbluebutton_server_salt'] ) {
			$query = http_build_query( $parameters, '', '&' );
			return sha1( $query . $prefs['bigbluebutton_server_salt'] );
		}
	}
}

global $bigbluebuttonlib;
$bigbluebuttonlib = new BigBlueButtonLib;

