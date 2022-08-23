<?php

namespace PHPAuth;

use Exception;
use stdClass;

class EMailValidator implements EmailValidatorInterface
{
    /**
     * @var array
     */
    public static $cache = [];

    /**
     * @var stdClass
     */
    public $result;

    /**
     * Validate email using blacklist/*.json
     *
     * @param string $email
     * @return bool
     */
    public static function isValid(string $email): bool
    {
        return (bool)(self::check($email))->isValid;
    }

    /**
     * Extended validation email. Return stdClass instance with { isValid: bool, state: string } fields
     *
     * @param string $email
     * @return stdClass
     */
    public static function check(string $email): stdClass
    {
        $result = new stdClass();
        $result->isValid = false;
        $result->state = '';

        try {
            if (false === $email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('FILTER_VALIDATE_EMAIL: invalid');
            }

            $pattern = sprintf('/^(?<local>%s)@(?<domain>%s)$/iD', self::EMAIL_REGEX_LOCAL, self::EMAIL_REGEX_DOMAIN);

            if (!preg_match($pattern, $email, $parts)) {
                throw new Exception("{$email}: can't split to E-Mail parts");
            }

            $local = strtolower($parts['local']);
            $domain = strtolower($parts['domain']);

            if (empty($domain)) {
                throw new Exception("{$email} have empty domain part");
            }

            $first = substr($domain, 0, 1);

            if (!array_key_exists($first, self::$cache)) {
                $fn = __DIR__ . "/blacklist/{$first}.json";

                if (!is_readable($fn)) {
                    throw new Exception("{$first}.json NOT READABLE");
                }

                if (false === $j = file_get_contents($fn)) {
                    throw new Exception("{$first}.json is EMPTY or read error");
                }
                $j = json_decode($j);

                if (is_null($j)) {
                    throw new Exception("{$first}.json parsing error");
                }

                self::$cache[ $first ] = $j;
            } else {
                $j = self::$cache[ $first ];
            }
            $found = array_search($domain, $j);

            $result->isValid = !$found;
            $result->state = $found ? 'is in blacklist' : 'NOT in blacklist';

        } catch (Exception $e) {
            $result->state = $e->getMessage();
        }

        return $result;
    }

    private static function splitEmail(string $email):string
    {
        return substr(strrchr($email, "@"), 1);
    }


}