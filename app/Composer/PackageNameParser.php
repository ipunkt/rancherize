<?php namespace Rancherize\Composer;

/**
 * Class PackageNameParser
 * @package Rancherize\Composer
 */
class PackageNameParser {

	/**
	 * @param $name
	 * @return PackageName
	 */
	public function parseName( string $name ) {

		$packageNameVersion = explode(':', $name, 2);
		// add empty version constraint if none was given
		if( count($packageNameVersion) < 2 )
			$packageNameVersion[] = '';

		list($packageName, $versionConstraint) = $packageNameVersion;

		// if no separator is found then it is only the package name, to prepend an empty provider
		$providerPackageName = explode('/', $packageName, 2);
		if( count($providerPackageName) < 2 )
			array_unshift($providerPackageName, '');

		list($provider, $name) = $providerPackageName;

		$nameObject = new PackageName();
		$nameObject->setPackageName($name);
		$nameObject->setProvider($provider);
		$nameObject->setVersionConstraint($versionConstraint);
		return $nameObject;
	}

}