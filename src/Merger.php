<?php

namespace NeonConfig;

use Nette\Neon\Neon;
use Nette\Utils\Strings;

class Merger
{
	/**
	 * @param string $fileName
	 * @return mixed[]
	 */
	public function loadConfiguration($fileName)
	{
		$data = Neon::decode(file_get_contents($fileName));

		// resolve extends
		if (isset($data['@extends'])) {
			$parent = $this->loadConfiguration(dirname($fileName) . '/' . $data['@extends']);
			$result = array_replace_recursive($parent, $data);
			unset($result['@extends']);
			return $result;
		}

		// resolve includes
		array_walk_recursive($data, function(&$item) use ($fileName) {
			if (!is_string($item) || !Strings::startsWith($item, '@') ) {
				return;
			}
			$pieces = explode(",", $item);
			if (count($pieces) > 1) {
				$item = [];
				foreach ($pieces as $one) {
					$item = array_merge(
						$item,
						$this->loadConfiguration(dirname($fileName) . '/' . ltrim(trim($one), '@'))
					);
				}
			} else {
				$item = $this->loadConfiguration(dirname($fileName) . '/' . ltrim($pieces[0], '@'));
			}
		});

		return $data;
	}
}
