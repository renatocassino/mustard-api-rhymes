<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;

use App\Post;

use Exception;
use Illuminate\Console\Command;



/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class CrawlerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "crawler:run";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Start crawler";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cssQuery = '.list-title+p+p';
        $client = new Client();

        $lettersWithError = [];

        for($letter = 'a'; $letter !== 'aa'; $letter++) {
            try {
                $url = "https://www.dicio.com.br/palavras-terminam-$letter/";
                echo "Requesting $url ...";
                $res = $client->request('GET', $url);
                $body = $res->getBody();

                $dom = HtmlDomParser::str_get_html( $body );
                $words = explode("<br />", utf8_encode($dom->find($cssQuery)[1]->innerText()));
                foreach ($words as $word) {
                    $words = file_put_contents('database/seeds/words/pt-br.txt', $word . PHP_EOL, FILE_APPEND);
                }
                echo $words;
            } catch (Exception $e) {
                $lettersWithError[] = $letter;
            }
        }

        echo "Letters with error";
        var_dump($lettersWithError);
    }
}
