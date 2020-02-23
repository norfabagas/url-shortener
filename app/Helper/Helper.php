<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class Helper
{    
    private static $acceptedCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private static $regex = '([a-zA-Z0-9])';
    private static $stringLength = 8;

    /**
     * Generate random string based on regular expression
     * 
     * @param int $stringCount
     * 
     * @return string $randomString
     */
    public static function generateRandomString()
    {
        $codes = DB::table('shorteners')
            ->select('code')
            ->pluck('code')
            ->toArray();

            
        $randomString = '';
            
        array_push($codes, $randomString);

        while (in_array($randomString, $codes)) {
            $randomString = self::randomString();
        }
        
        return $randomString;
    }

    /**
     * Check if code passes regex [a-zA-Z0-9]
     * 
     * @param string $code
     * 
     * @return boolean
     */
    public static function checkCodeRegex(string $code)
    {
        if (strlen($code) >= self::$stringLength) {
            return false;
        } else {
            $result = preg_match(self::$regex, $code);
            switch ($result) {
                case 1:
                    return true;
                case 0:
                    return false;
                default:
                    return null;
            }
        }
    }

    /**
     * Generate random string
     * 
     * @return string $randomString
     */
    protected static function randomString()
    {
        $randomString = '';
        $stringLength = strlen(self::$acceptedCharacters);

        for ($i = 0; $i < self::$stringLength; $i++) {
            $randomString .= self::$acceptedCharacters[rand(0, $stringLength - 1)];
        }

        return $randomString;
    }
}
