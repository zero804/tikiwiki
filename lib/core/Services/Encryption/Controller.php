<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

use TQ\Shamir\Secret;

class Services_Encryption_Controller
{
	private $encryptionlib;

	function setUp()
	{
		$this->encryptionlib = TikiLib::lib('encryption');
	}

	/**
	 * Returns the section for use with certain features like banning
	 * @return string
	 */
	function getSection()
	{
		return 'security';
	}

	function action_save_key($input)
	{
		global $user, $prefs;

		$keyId = $input->keyId->int();
		$users = TikiLib::lib('user')->extract_users($input->users->text(), $prefs['user_show_realnames'] == 'y');

		if (empty($keyId)) {
			$data = [
				'name' => $input->name->text(),
				'description' => $input->description->text(),
				'algo' => $input->algo->text(),
				'shares' => $input->shares->int(),
				'users' => TikiLib::lib('tiki')->str_putcsv($users),
			];
			if ($users) {
				$data['shares'] = count($users);
			}
			$keylen = openssl_cipher_iv_length($data['algo']);
			$key = openssl_random_pseudo_bytes($keylen);
			$shares = $this->share($key, $data);
		} else {
			$data = [
				'name' => $input->name->text(),
				'description' => $input->description->text(),
			];
			if ($input->regenerate->int()) {
				$data['algo'] = $input->algo->text();
				$data['shares'] = $input->shares->int();
				$data['users'] = TikiLib::lib('tiki')->str_putcsv($users);
				if ($users) {
					$data['shares'] = count($users);
				}
				$key = $this->action_decrypt_key(new JitFilter(['keyId' => $keyId, 'existing' => $input->old_share->text()]));
				$shares = $this->share($key, $data);
			} else {
				$shares = null;
			}
		}

		$keyId = $this->encryptionlib->set_key($keyId, $data);

		foreach ($users as $i => $auser) {
			if ($auser == $user) {
				TikiLib::lib('crypt')->init();
				TikiLib::lib('crypt')->setUserData('sk', $shares[$i], $keyId);
			} else {
				TikiLib::lib('tiki')->set_user_preference($auser, 'pe.sk.'.$keyId, $shares[$i]);
			}
		}

		return [
			'keyId' => $keyId,
			'shares' => $shares,
		];
	}

	function action_get_key($input)
	{
		$keyId = $input->keyId->int();

		$encryption_key = $this->encryptionlib->get_key($keyId);

		return [
			'key' => $encryption_key,
		];
	}

	function action_get_keys()
	{
		$encryption_keys = $this->encryptionlib->get_keys();

		return $encryption_keys;
	}

	function action_delete_key($input)
	{
		return $this->encryptionlib->delete_key($input->keyId->int());
	}

	function action_get_share_for_key($input)
	{
		$crypt = TikiLib::lib('crypt');
		$crypt->init();
		$share = $crypt->getUserData('sk.'.$input->keyId->int());
		return $share;
	}

	function action_decrypt_key($input)
	{
		$encryption_key = $this->encryptionlib->get_key($input->keyId->int());
		$existing = $input->existing->text();
		if (! $existing) {
			$existing = $this->action_get_share_for_key(new JitFilter(['keyId' => $encryption_key['keyId']]));
		}
		try {
			$key = Secret::recover([$encryption_key['secret'], $existing]);
		} catch (RuntimeException $e) {
			throw new Services_Exception_Denied($e->getMessage());
		}
		return $key;
	}

	private function share($key, &$data)
	{
		$shares = Secret::share($key, $data['shares']+1, 2);
		$data['secret'] = $shares[0];
		return array_slice($shares, 1, count($shares)-1);
	}
}
