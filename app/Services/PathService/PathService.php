<?php namespace Rancherize\Services\PathService;
use Rancherize\Services\PathService\Exceptions\EmptyPathException;

/**
 * Class PathService
 * @package Rancherize\Services\PathService
 */
class PathService {

	/**
	 * @param $path
	 * @return Path
	 */
	public function parsePath( $path ) {

		if(empty($path) )
			throw new EmptyPathException();

		$pathObject = new Path;

		$filename = basename($path);
		$pathObject->setFilename($filename);

		if($path[0] !== DIRECTORY_SEPARATOR)
			$path = DIRECTORY_SEPARATOR.$path;
		$pathObject->setPath($path);

		return $pathObject;
	}
}