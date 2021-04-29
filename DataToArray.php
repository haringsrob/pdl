<?php
/**
 * A rewritten variant of TVarDumper by:
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link https://github.com/pradosoft/prado
 * @license https://github.com/pradosoft/prado/blob/master/LICENSE
 * @package Prado\Util
 */

class DataToArray
{
    private static $_objects;
    private static $_output = [];
    private static $_depth;

    /**
     * Converts a variable into a array representation.
     * This method achieves the similar functionality as var_dump and print_r
     * but is more robust when handling complex objects such as PRADO controls.
     * @param mixed $var variable to be dumped
     * @param int $depth maximum depth that the dumper should go into the variable. Defaults to 10.
     */
    public static function dump($var, int $depth = 10): array
    {
        self::$_output = [];
        self::$_objects = [];
        self::$_depth = $depth;
        self::dumpInternal($var, 0, self::$_output);
        return self::$_output;
    }

    private static function dumpInternal($var, $level, array &$putIn)
    {
        $type = gettype($var);
        switch ($type) {
            case 'boolean':
                $putIn[$type] = $var ? 'true' : 'false';
                break;
            case 'integer':
                $putIn[$type] = "$var";
                break;
            case 'double':
                $putIn[$type] = "$var";
                break;
            case 'string':
                $putIn[$type] = "'$var'";
                break;
            case 'resource':
                $putIn[$type] = '{resource}';
                break;
            case 'NULL':
                $putIn[$type] = "null";
                break;
            case 'unknown type':
                $putIn[$type] = '{unknown}';
                break;
            case 'array':
                if (self::$_depth <= $level) {
                    $putIn[$type] = 'array(...)';
                } elseif (empty($var)) {
                    $putIn[$type] = 'array(empty)';
                } else {
                    $keys = array_keys($var);
                    foreach ($keys as $key) {
                        $putIn[$type][$key] = [];
                        self::dumpInternal($var[$key], $level + 1, $putIn[$type][$key]);
                    }
                }
                break;
            case 'object':
                $putIn[$type] = [];
                if (($id = array_search($var, self::$_objects, true)) !== false) {
                    $putIn[$type] = get_class($var) . '#' . ($id + 1) . '(...)';
                } elseif (self::$_depth <= $level) {
                    $putIn[$type] = get_class($var) . '(...)';
                } else {
                    $id = array_push(self::$_objects, $var);
                    $className = get_class($var);
                    $members = (array)$var;
                    $keys = array_keys($members);
                    $putIn[$type]["$className#$id"] = [];
                    foreach ($keys as $key) {
                        $keyDisplay = strtr(trim($key), ["\0" => ':']);
                        $putIn[$type]["$className#$id"][$keyDisplay] = [];
                        self::dumpInternal($members[$key], $level + 1, $putIn[$type]["$className#$id"][$keyDisplay]);
                    }
                }
                break;
        }
    }
}
