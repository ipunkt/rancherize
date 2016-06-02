#!/usr/bin/php

<?PHP

function parseYaml($file) {
	$fileContents = file_get_contents($file);
	$data = yaml_parse($fileContents);
	return $data;
}

function filterScale($input, $smallerThan = null) {

	if($smallerThan === null)
		$smallerThan = 1;
	
	$containers = [];
	foreach($input as $containerName => $containerData) {
		if ( !array_key_exists('scale', $containerData))
			continue;

		$scale = intval($containerData['scale']);
		if( $scale < $smallerThan )
			continue;

		$containers[$containerName] = $containerData;
	}

	return $containers;
}

function filterByRegex($input, $regex) {
	$filteredContainers = [];

	foreach($input as $containerName => $containerData) {
		$match = preg_match('~'.$regex.'~', $containerName);
		if( $match === false )
			throw new InvalidArgumentException( "Beim matchen der Regex '$regex' ist ein Fehler aufgetreten." );

		if( $match == 0 )
			continue;

		$filteredContainers[$containerName] = $containerData;
	}

	return $filteredContainers;
}

$file = 'rancher-compose.yml';
$regex = $argv[1];

$data = parseYaml($file);
$containers = filterScale($data);

if( ! empty($regex) ) {

	try {
		$filteredContainers = filterByRegex($containers, $regex);
	} catch(InvalidArgumentException $e) {
		echo $e->getMessage();
		exit(1);
	}
	echo implode("\n", array_keys($filteredContainers) );
	exit(0);

}

echo implode("\n", array_keys($containers) );
