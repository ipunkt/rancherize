#!/usr/bin/php
<?PHP

$outsideAutoload = __DIR__ . '/../autoload.php';
$insideAutoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists($outsideAutoload ) )
	require_once $outsideAutoload;
else
	require_once $insideAutoload;

use Symfony\Component\Yaml\Yaml;

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
			throw new InvalidArgumentException("Matching regex '$regex' failed.");

		if( $match == 0 )
			continue;

		$filteredContainers[$containerName] = $containerData;
	}

	return $filteredContainers;
}

$file = 'rancher-compose.yml';
$regex = $argv[1];

//$data = parseYaml($file);
//$data = Spyc::YAMLLoad($file);
$data = Yaml::parse(file_get_contents($file));
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
