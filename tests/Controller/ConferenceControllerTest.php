<?php


namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class ConferenceControllerTest extends PantherTestCase
{
    public function testIndex()
    {
        $client = static::createPantherClient();
        $client->request('GET', $_SERVER['SYMFONY_DEFAULT_ROUTE_URL']);

        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testCommentSubmission()
    {
        $client = static::createPantherClient();
        $client->request('GET', $_SERVER['SYMFONY_DEFAULT_ROUTE_URL'].'conference/amsterdam-2019');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Fabien',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'me@automat.ed',
            'comment_form[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);
        $this->assertSelectorTextContains('.total__comments', 'There are 2 comments');
    }

    public function testConferencePage()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', $_SERVER['SYMFONY_DEFAULT_ROUTE_URL']);

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Amsterdam');
        $this->assertSelectorTextContains('h2', 'Amsterdam 2019');
        $this->assertSelectorTextContains('.total__comments','There are 2 comments.');
    }
}