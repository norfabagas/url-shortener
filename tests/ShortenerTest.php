<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;

class ShortenerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * Create shortener
     */
    public function testCreateUpdateDeleteShortener()
    {
        // create shortener
        $createdData = $this->createData();

        $this->seeInDatabase('shorteners', ['url' => $createdData['url'], 'code' => $createdData['code']]);

        // update created shortener
        $randomString = Helper::generateRandomString();
        $url = 'https://' . $randomString . '.xyz';

        DB::table('shorteners')
            ->where('url', $createdData['url'])
            ->where('code', $createdData['code'])
            ->update([
                'url' => $url,
                'code' => $randomString
            ]);

        $this->missingFromDatabase('shorteners', ['url' => $createdData['url'], 'code' => $createdData['code']]);
        $this->seeInDatabase('shorteners', ['url' => $url, 'code' => $randomString]);

        // delete created shortener
        DB::table('shorteners')
            ->where('url', $url)
            ->where('code', $randomString)
            ->delete();

        $this->notSeeInDatabase('shorteners', ['url' => $url, 'code' => $randomString]);
    }

    /**
     * create data in shorteners table
     * 
     * @return array $array
     */
    protected function createData()
    {
        $randomString = Helper::generateRandomString();
        $url = 'https://' . $randomString . '.com';

        $array = [
            'url' => $url,
            'code' => $randomString
        ];

        DB::table('shorteners')->insert($array);

        return $array;
    }
}