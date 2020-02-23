<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;

class StatisticTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * Create and Update created statistic
     */
    public function testCreateUpdateDeleteStatistic()
    {
        // create shortener
        $shortenerId = $this->createShortener();
        $this->seeInDatabase('shorteners', ['id' => $shortenerId]);

        // create statistic
        $statistic = [
            'shortener_id' => $shortenerId,
            'ip_address' => $this->generateIpAddress()
        ];

        $statisticId = DB::table('statistics')->insertGetId($statistic);

        $this->seeInDatabase('statistics', ['id' => $statisticId]);

        // update created statistic
        $updatedIpAddress = $this->generateIpAddress();

        DB::table('statistics')
            ->where('statistics.id', $statisticId)
            ->update([
                'ip_address' => $updatedIpAddress
            ]);

        $this->missingFromDatabase('statistics', $statistic);

        $statistic['ip_address'] = $updatedIpAddress;

        $this->seeInDatabase('statistics', $statistic);

        // delete created statistic
        DB::table('statistics')
            ->where('statistics.id', $statisticId)
            ->delete();
        
        $this->notSeeInDatabase('statistics', ['id' => $statisticId]);
    }

    /**
     * Generate shortener data
     * 
     * @return int $id
     */
    protected function createShortener()
    {
        $code = Helper::generateRandomString();
        $url = 'http://' . $code . '.com';

        $id = DB::table('shorteners')->insert([
            'url' => $url,
            'code' => $code
        ]);

        return $id;
    }

    /**
     * Generate IP Address
     * 
     * @return string $ip
     */
    protected function generateIpAddress()
    {
        return long2ip(mt_rand(0, PHP_INT_MAX));
    }
}