<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\AddressParameter;
use RedisClient\Command\Parameter\CommandParameter;
use RedisClient\Command\Parameter\EnumParameter;
use RedisClient\Command\Parameter\IntegerParameter;
use RedisClient\Command\Parameter\KeyParameter;
use RedisClient\Command\Parameter\StringParameter;
use RedisClient\Command\Parameter\StringsParameter;
use RedisClient\Command\Response\AssocArrayResponseParser;
use RedisClient\Command\Response\TimeResponseParser;

trait RedisServerCommandsTrait {

    /**
     * BGREWRITEAOF
     * Available since 1.0.0.
     *
     * @return boolean Always true
     */
    public function bgrewriteaof() {
        return $this->returnCommand(
            new Command('BGREWRITEAOF')
        );
    }

    /**
     * BGSAVE
     * Available since 1.0.0.
     *
     * @return string
     */
    public function bgsave() {
        return $this->returnCommand(
            new Command('BGSAVE')
        );
    }

    /**
     * CLIENT GETNAME
     * Available since 2.6.9.
     * Time complexity: O(1)
     *
     * @return string|null The connection name, or a null bulk reply if no name is set.
     */
    public function clientGetname() {
        return $this->returnCommand(
            new Command('CLIENT GETNAME', [])
        );
    }

    /**
     * CLIENT KILL [ip:port] [ID client-id] [TYPE normal|slave|pubsub] [ADDR ip:port] [SKIPME yes/no]
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     *
     * @param string|array|null $addr
     * @param int|null $clientId
     * @param string|null $type
     * @param string|array|null $addr2
     * @param boolean|null $skipme
     * @return boolean|int
     * When called with the three arguments format:
     * Simple string reply: TRUE if the connection exists and has been closed
     * When called with the filter / value format:
     * Integer reply: the number of clients killed.
     */
    public function clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null) {
        $params = [];
        if ($addr) {
            $params[] = new AddressParameter($addr);
        }
        if ($clientId) {
            $params[] = new StringParameter('ID');
            $params[] = new IntegerParameter($clientId);
        }
        if ($type && preg_match('/^(normal|slave|pubsub)$/i', $type)) {
            $params[] = new StringParameter('TYPE');
            $params[] = new StringParameter(strtolower(trim($type)));
        }
        if ($addr2) {
            $params[] = new StringParameter('ADDR');
            $params[] = new AddressParameter($addr2);
        }
        if (!is_null($skipme)) {
            $params[] = new StringParameter('SKIPME');
            $params[] = new AddressParameter($skipme ? 'yes' : 'no');
        }
        return $this->returnCommand(
            new Command('CLIENT KILL', $params)
        );
    }

    /**
     * CLIENT LIST
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     *
     * @return string
     */
    public function clientList() {
        return $this->returnCommand(
            new Command('CLIENT LIST')
        );
    }

    /**
     * CLIENT PAUSE timeout
     * Available since 2.9.50.
     * Time complexity: O(1)
     *
     * @param int $timeout
     * @return true The command returns TRUE or an error if the timeout is invalid.
     */
    public function clientPause($timeout) {
        return $this->returnCommand(
            new Command('CLIENT PAUSE', new IntegerParameter($timeout))
        );
    }

    /**
     * CLIENT SETNAME connection-name
     * Available since 2.6.9.
     * Time complexity: O(1)
     *
     * @param string $connectionName
     * @param boolean TRUE if the connection name was successfully set.
     */
    public function clientSetname($connectionName) {
        return $this->returnCommand(
            new Command('CLIENT SETNAME', new StringParameter($connectionName))
        );
    }

    /**
     * COMMAND
     * Available since 2.8.13.
     * Time complexity: O(N) where N is the total number of Redis commands
     * @link http://redis.io/commands/command
     *
     * @return array
     */
    public function command() {
        return $this->returnCommand(
            new Command('COMMAND')
        );
    }

    /**
     * COMMAND COUNT
     * Available since 2.8.13.
     * Time complexity: O(1)
     *
     * @return int Number of commands returned by COMMAND
     */
    public function commandCount() {
        return $this->returnCommand(
            new Command('COMMAND COUNT', [])
        );
    }

    /**
     * COMMAND GETKEYS command
     * Available since 2.8.13.
     * Time complexity: O(N) where N is the number of arguments to the command
     *
     * @params Command $command
     * @return string[] List of keys from your command.
     */
    public function commandGetkeys($command) {
        return $this->returnCommand(
            new Command('COMMAND GETKEYS', new CommandParameter($command))
        );
    }

    /**
     * COMMAND INFO command-name [command-name ...]
     * Available since 2.8.13.
     * Time complexity: O(N) when N is number of commands to look up
     *
     * @params string[] $commandName
     * @return array Nested list of command details.
     */
    public function commandInfo($commandName) {
        return $this->returnCommand(
            new Command('COMMAND INFO', new StringsParameter($commandName))
        );
    }

    /**
     * CONFIG GET parameter
     * Available since 2.0.0.
     *
     * @params string|string[]
     * @return array
     */
    public function configGet($parameter) {
        return $this->returnCommand(
            new Command('CONFIG GET', new StringParameter($parameter), AssocArrayResponseParser::getInstance())
        );
    }

    /**
     * CONFIG RESETSTAT
     * Available since 2.0.0.
     * Time complexity: O(1)
     *
     * @return boolean always True
     */
    public function configResetstat() {
        return $this->returnCommand(
            new Command('CONFIG RESETSTAT')
        );
    }

    /**
     * CONFIG REWRITE
     * Available since 2.8.0.
     *
     * @return boolean True when the configuration was rewritten properly. Otherwise an error is returned.
     */
    public function configRewrite() {
        return $this->returnCommand(
            new Command('CONFIG REWRITE')
        );
    }

    /**
     * CONFIG SET parameter value
     * Available since 2.0.0.
     *
     * @return boolean True when the configuration was set properly. Otherwise an error is returned.
     */
    public function configSet($parameter, $value) {
        return $this->returnCommand(
            new Command('CONFIG SET')
        );
    }

    /**
     * DBSIZE
     * Available since 1.0.0.
     *
     * @return int The number of keys in the currently-selected database.
     */
    public function dbsize() {
        return $this->returnCommand(
            new Command('DBSIZE')
        );
    }

    /**
     * DEBUG OBJECT key
     * Available since 1.0.0.
     *
     * @params string $key
     * @return string
     */
    public function debugObject($key) {
        return $this->returnCommand(
            new Command('DEBUG OBJECT', new KeyParameter($key))
        );
    }

    /**
     * DEBUG SEGFAULT
     * Available since 1.0.0.
     *
     * @return string
     */
    public function debugSegfault() {
        return $this->returnCommand(
            new Command('DEBUG SEGFAULT')
        );
    }

    /**
     * FLUSHALL
     * Available since 1.0.0.
     *
     * @return boolean
     */
    public function flushall() {
        return $this->returnCommand(
            new Command('FLUSHALL')
        );
    }

    /**
     * FLUSHDB
     * Available since 1.0.0.
     *
     * @return boolean
     */
    public function flushdb() {
        return $this->returnCommand(
            new Command('FLUSHDB')
        );
    }

    /**
     * INFO [section]
     * Available since 1.0.0.
     *
     * @param string $section
     * @return string
     */
    public function info($section) {
        return $this->returnCommand(
            new Command('INFO', new StringParameter($section))
        );
    }

    /**
     * LASTSAVE
     * Available since 1.0.0.
     *
     * @return int an UNIX time stamp.
     */
    public function lastsave() {
        return $this->returnCommand(
            new Command('LASTSAVE')
        );
    }

    /**
     * MONITOR
     * Available since 1.0.0.
     */
    public function monitor() {
        return $this->returnCommand(
            new Command('MONITOR')
        );
    }

    /**
     * ROLE
     * Available since 2.8.12.
     *
     * @return array
     */
    public function role() {
        return $this->returnCommand(
            new Command('ROLE')
        );
    }

    /**
     * SAVE
     * Available since 1.0.0.
     *
     * @return boolean The commands returns True on success
     */
    public function save() {
        return $this->returnCommand(
            new Command('SAVE')
        );
    }

    /**
     * SHUTDOWN [NOSAVE] [SAVE]
     * Available since 1.0.0.
     *
     * @params string|null $save NOSAVE or SAVE
     */
    public function shutdown($save) {
        return $this->returnCommand(
            new Command('SHUTDOWN', new EnumParameter($save, ['', 'NOSAVE', 'SAVE'], 0))
        );
    }

    /**
     * SLAVEOF host port
     * Available since 1.0.0.
     *
     * @param string $host
     * @param string $port
     * @return boolean
     */
    public function slaveof($host, $port) {
        return $this->returnCommand(
            new Command('SLAVEOF', [
                new StringParameter($host),
                new StringParameter($port)
            ])
        );
    }

    /**
     * SLOWLOG subcommand [argument]
     * Available since 2.2.12.
     *
     * @param string $subcommand
     * @param string|null $argument
     * @return mixed
     */
    public function slowlog($subcommand, $argument = null) {
        $params = [
            new EnumParameter($subcommand, ['GET', 'LEN', 'RESET'])
        ];
        if (!is_null($argument)) {
            $params[] = new StringParameter($argument);
        }
        return $this->returnCommand(
            new Command('SLOWLOG', $params)
        );
    }

    /**
     * SYNC
     * Available since 1.0.0.
     *
     */
    public function sync() {
        return $this->returnCommand(
            new Command('SYNC')
        );
    }

    /**
     * TIME
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @return string
     */
    public function time() {
        return $this->returnCommand(
            new Command('TIME', null, TimeResponseParser::getInstance())
        );
    }

}
