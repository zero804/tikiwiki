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
		$keyId = $input->keyId->int();

		if (empty($keyId)) {
			$data = [
				'name' => $input->name->text(),
				'description' => $input->description->text(),
				'algo' => $input->algo->text(),
				'shares' => $input->shares->int(),
			];
			$keylen = openssl_cipher_iv_length($data['algo']);
			$key = openssl_random_pseudo_bytes($keylen);
			$shares = Secret::share($key, $data['shares']+1, 2);
			$data['secret'] = $shares[0];
			$shares = array_slice($shares, 1, count($shares)-1);
		} else {
			$data = [
				'name' => $input->name->text(),
				'description' => $input->description->text(),
			];
			if ($input->regenerate->int()) {
				$data['algo'] = $input->algo->text();
				$data['shares'] = $input->shares->int();
				$encryption_key = $this->encryptionlib->get_key($keyId);
				$existing = $input->old_share->text();
				try {
					$key = Secret::recover([$encryption_key['secret'], $existing]);
				} catch (RuntimeException $e) {
					throw new Services_Exception_Denied($e->getMessage());
				}
				$shares = Secret::share($key, $data['shares']+1, 2);
				$data['secret'] = $shares[0];
				$shares = array_slice($shares, 1, count($shares)-1);
			} else {
				$shares = null;
			}
		}

		$keyId = $this->encryptionlib->set_key($keyId, $data);

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
		$this->encryptionlib->delete_key($input->keyId->int());

		return true;
	}
}
