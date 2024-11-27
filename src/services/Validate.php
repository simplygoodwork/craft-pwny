<?php

namespace simplygoodwork\pwny\services;

use Craft;
use craft\base\Component;
use GuzzleHttp\Exception\GuzzleException;
use simplygoodwork\pwny\Pwny;

class Validate extends Component
{
	public function apiCheck(string $password): bool|string
	{
		if (!Pwny::$plugin->settings['enabled']) {
			return false;
		}

		$result = false;
		$passwordHash = strtoupper(sha1($password));
		$passwordPrefix = substr($passwordHash, 0, 5);

		$endpoint = 'https://api.pwnedpasswords.com/range/' . $passwordPrefix;

		$client = Craft::createGuzzleClient();
		try {
			$response = $client->request('GET', $endpoint);
			$data = $response->getBody()->getContents();

			// All being well the body will contain a bunch of hash suffixes, one per line
			$passwords = explode("\r\n", $data);
			foreach ($passwords as $password) {
				// Each line is of the form "05CC02592061A8BBB67A6B352778D0B0C4F:1".
				// The first part is the hash suffix, the second is the number of
				// times it appears in the list.
				$line = explode(':', $password);
				$pwnedHash = $passwordPrefix . $line[0];

				// Compare the user password with the HIBP one
				if ($pwnedHash === $passwordHash) {
					$result = Pwny::$plugin->settings['message'];
					break;
				}
			}
		} catch(GuzzleException $e){
			Craft::error($e->getMessage(), __METHOD__);
			return false;
		}

		return $result;
	}
}
