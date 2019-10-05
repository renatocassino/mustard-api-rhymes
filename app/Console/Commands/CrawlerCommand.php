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
        $cssQuery = 'td[align="right"] > font';
        $client = new Client();

        for($letter = 'aaot'; strlen($letter) <= 4; $letter++) {
            try {
                echo "Requesting letter: $letter \r";
                $url = "http://poetavadio.com/rhymedic_search.php?word=$letter&terml=2&nres=30000";
                $res = $client->request('GET', $url);
                $body = $res->getBody();

                $dom = HtmlDomParser::str_get_html( $body );
                $words = $dom->find($cssQuery)[0]->innerText();
                // $words = utf8_encode($words);
                $words = preg_replace("/\<p\>.*\<\/p\>/", '', $words);
                $words = explode("<br>", $words);

                if (count($words) < 2) continue;

                foreach($words as $w) {
                    file_put_contents('database/seeds/words/pt-br.txt', $w . PHP_EOL, FILE_APPEND);
                }

                $this->organize();
            }
            catch (Exception $error) {
                echo "\n\nError in letter $letter: {$error->getMessage()}\n\n";
            }
        }

        // $lettersWithError = [];

        // for($letter = 'a'; $letter !== 'aa'; $letter++) {
        //     try {
        //         $url = "https://www.dicio.com.br/palavras-terminam-$letter/";
        //         echo "Requesting $url ...";
        //         $res = $client->request('GET', $url);
        //         $body = $res->getBody();

        //         $dom = HtmlDomParser::str_get_html( $body );
        //         $words = explode("<br />", utf8_encode($dom->find($cssQuery)[1]->innerText()));
        //         foreach ($words as $word) {
        //             file_put_contents('database/seeds/words/pt-br.txt', $word . PHP_EOL, FILE_APPEND);
        //         }
        //         echo $words;
        //     } catch (Exception $e) {
        //         $lettersWithError[] = $letter;
        //     }
        // }

        // echo "Letters with error";
        // var_dump($lettersWithError);
    }

    public function organize()
    {
        $words = file_get_contents('database/seeds/words/pt-br.txt');
        $words = explode("\n", $words);
        $words = array_unique($words);
        sort($words);
        file_put_contents('database/seeds/words/pt-br.txt', implode("\n", $words) . "\n");
    }
}
