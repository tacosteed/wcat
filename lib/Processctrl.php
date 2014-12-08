<?php
/**
 * @file
 * @date 2014-05-20
 */

declare(ticks = 1);

class Processctrl
{

	protected $_max_process = 3;

	protected $_timeout = 5;

	protected $_args;

	protected $_stack;

	static public function getInstace()
	{
		static $object;
		if (is_null($object)) {
			$object = new static;
		}

		return $object;
	}

	public function __construct()
	{
		$this->_args = array();
		$this->_stack = array();

		pcntl_signal(SIGALRM, function ($signal) {
			//Log::debug("Get signal [".$signal."]");
			switch ($signal) {
				case SIGALRM:
					echo ">>>> TIMEOUT!! <<<<\n";
					exit();
					break;
				default:
					break;
			}
		});
	}

	public static function setMaxProcess($max_process)
	{
		static::getInstace()->_max_process = $max_process;
	}

	public static function setTimeout($sec)
	{
		static::getInstace()->_timeout = $sec;
	}

	public function addArgs($args)
	{
		$this->_args[] = $args;
	}

	public function clearArgs()
	{
		$this->_args = array();
	}

	public function run_all($callback)
	{
		if (!is_callable($callback)) {
			throw new \Exception('Not callable['.$callback.']');
		}

		// 子プロセスを生成し処理を実行する
		foreach ($this->_args as $args) {
			$pid = pcntl_fork();
			if (-1 === $pid) {
				throw new \Exception('False fork process ['.pcntl_get_last_error().']');
			}

			if ($pid) {
				//Log::debug("[" . __METHOD__ . "]: Parent process PID[".$pid."].");
				$this->_stack[$pid] = true;
				if (count($this->_stack) >= $this->_max_process) {
					//Log::debug("[" . __METHOD__ . "]: Stacked process is max ...waiting...[".count($this->_stack)."].");
					unset($this->_stack[pcntl_waitpid(-1, $status, WUNTRACED)]);
				}
			} else {
				//Log::debug("[" . __METHOD__ . "]: Child process running.");
				pcntl_alarm($this->_timeout);
				call_user_func_array($callback, $args);
				exit();
			}
		}

		// すべての子プロセスの終了を待つ
		while (count($this->_stack) > 0) {
			//Log::debug("[" . __METHOD__ . "]: Waiting process all end...[".count($this->_stack)."].");
			unset($this->_stack[pcntl_waitpid(-1, $status, WUNTRACED)]);
		}
	}

}

