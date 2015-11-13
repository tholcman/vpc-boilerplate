<?php

namespace NeonConfig\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Convert extends Command {
	/**
	 * @var \NeonConfig\Merger
	 */
	private $neonMerger;

	public function __construct() {
		$this->neonMerger = new \NeonConfig\Merger();
		parent::__construct();
	}

	protected function configure() {
		$this
			->setName('convert')
			->setDescription('Converts neon to json, with @include(s), @extends(s)')
/*			->addArgument(
				"stage",
				\Symfony\Component\Console\Input\InputArgument::OPTIONAL,
				"Stage of generated cloud formation dev|release|prod")
*/			->addArgument(
				"fromFile",
				\Symfony\Component\Console\Input\InputArgument::OPTIONAL,
				"Source file.",
				'configs/config.neon')
			->addArgument(
				"outputFile",
				\Symfony\Component\Console\Input\InputArgument::OPTIONAL,
				"Output file.",
				'outputs/ouptut.json')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$in = $input->getArgument("fromFile");
		$out = $input->getArgument("outputFile");

		if (!file_exists($in)) {
			throw new \RuntimeException("Input file '$in' does not exists.");
		}

		$config = $this->neonMerger->loadConfiguration($in);

		// on dev stages add out route table assosiation to all networks
/*		if ($input->getArgument("stage") === "dev") {
			$config["Resources"]["AppSubnetRouteTableAssociation"] = [
				"Type" => "AWS::EC2::SubnetRouteTableAssociation",
				"Properties" => [
					"RouteTableId" => [ "Ref" => "OutRouteTable" ],
					"SubnetId" => [ "Ref" => "AppSubnet" ]
				]
			];
			$config["Resources"]["LogSubnetRouteTableAssociation"] = [
				"Type" => "AWS::EC2::SubnetRouteTableAssociation",
				"Properties" => [
					"RouteTableId" => [ "Ref" => "OutRouteTable" ],
					"SubnetId" => [ "Ref" => "LogSubnet" ]
				]
			];
		}

*/		file_put_contents($out, json_encode($config, JSON_PRETTY_PRINT));
	}
}