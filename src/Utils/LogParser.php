<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/22/18
 * Time: 2:39 PM.
 */

namespace KgBot\LaravelDeploy\Utils;

use Carbon\Carbon;
use Psr\Log\LogLevel;
use Illuminate\Support\Str;

class LogParser
{
    /**
     * Parsed data.
     *
     * @var array
     */
    protected $parsed = [];

    /**
     * @var array All log levels to parse
     */
    protected $levels = [];

    /**
     * @var string This determines log heading
     */
    protected $heading_pattern = '/\['.REGEX_DATE_PATTERN.' '.REGEX_TIME_PATTERN.'\].*:/';

    /**
     * @var string Log date pattern
     */
    protected $date_patern = '/'.REGEX_DATE_PATTERN.' '.REGEX_TIME_PATTERN.'/';

    protected $extra_patter = '/{.*:".*".*}/D';

    public function __construct()
    {
        $log_levels_class = new \ReflectionClass(LogLevel::class);

        foreach ($log_levels_class->getConstants() as $level) {
            array_push($this->levels, $level);
        }
    }

    /**
     * Parse file content.
     *
     * @param  string $raw
     *
     * @return array
     */
    public function parse($raw)
    {
        $this->parsed = [];
        list($headings, $data) = $this->parseRawData($raw);
        // @codeCoverageIgnoreStart
        if (! is_array($headings)) {
            return $this->parsed;
        }
        // @codeCoverageIgnoreEnd
        foreach ($headings as $heading) {
            for ($i = 0, $j = count($heading); $i < $j; $i++) {
                $this->populateEntries($heading, $data, $i);
            }
        }
        unset($headings, $data);

        return array_reverse($this->parsed);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Parse raw data.
     *
     * @param  string $raw
     *
     * @return array
     */
    private function parseRawData($raw)
    {
        preg_match_all($this->heading_pattern, $raw, $headings);
        $data = preg_split($this->heading_pattern, $raw);
        if ($data[0] < 1) {
            $trash = array_shift($data);
            unset($trash);
        }

        return [$headings, $data];
    }

    /**
     * Populate entries.
     *
     * @param  array $heading
     * @param  array $data
     * @param  int   $key
     */
    private function populateEntries($heading, $data, $key)
    {
        foreach ($this->levels as $level) {
            if (self::hasLogLevel($heading[$key], $level)) {
                // We use this to get the "extra" part from Monolog logger and provide it to user
                preg_match($this->extra_patter, $data[$key], $extra, null, 0);

                // Here we just remove Monolog "extra" argument from string
                $without_extra = preg_split($this->extra_patter, $data[$key])[0];

                preg_match($this->date_patern, $heading[$key], $created_at);

                $this->parsed[] = [
                    'date'    => Carbon::parse($created_at[0]),
                    'level'   => strtoupper($level),
                    'header'  => $heading[$key],
                    'message' => $without_extra,
                    'extra'   => $extra ? json_decode($extra[0]) : [],
                ];
            }
        }
    }

    /**
     * Check if header has a log level.
     *
     * @param  string $heading
     * @param  string $level
     *
     * @return bool
     */
    private function hasLogLevel($heading, $level)
    {
        return Str::contains(strtolower($heading), strtolower('.'.$level));
    }
}
