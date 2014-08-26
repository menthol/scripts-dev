<?php namespace Menthol\ScriptsDev;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Illuminate\Config\Repository as ConfigRepository;

class ScriptsDevCommand extends Command {

	/** @var \Illuminate\Config\Repository */
	protected $config;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scripts-dev';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Execute scripts-dev composer commands on dev environment';

	/**
	 * Create a new dev-post-update command instance.
	 *
	 * @return void
	 */
	public function __construct(ConfigRepository $config)
	{
		$this->config = $config;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Execure commands only on dev environment.
		if (!$this->config->get('scripts-dev::dev', false)) {
			return;
		}
		$composer_json = $this->laravel['path.base'] . DIRECTORY_SEPARATOR . '/composer.json';
		if (file_exists($composer_json)) {
			$composer_content = json_decode(file_get_contents($composer_json));
			if (empty($composer_content)) {
				$this->error('the file composer.json isn\'t readable.');
				return;
			}

			if (empty($composer_content->{'scripts-dev'})) {
				return;
			}

			$event = $this->argument('event');

			if (empty($composer_content->{'scripts-dev'}->$event)) {
				return;
			}

			$commands = $composer_content->{'scripts-dev'}->$event;

			$process = null;

			foreach ($commands as $command) {
				if (is_null($process)) {
					$process = (new Process('', $this->laravel['path.base']))->setTimeout(null);
				}
				$process->setCommandLine(trim($command));
				$return = $process->run();
				$output = trim($process->getOutput());
				if (!empty($output)) {
					$this->info($output);
				}
				if ($return !== 0) {
					$error_output = trim($process->getErrorOutput());
					if (!empty($error_output)) {
						$return = $error_output;
					}
					$this->error($return);
				}
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('event', InputArgument::REQUIRED, 'Current composer\'s script cmd event'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
