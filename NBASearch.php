<?php

use Sunra\PhpSimple\HtmlDomParser;

class NBASearch
{
    private $contents = '';
    private $pttId;
    private $counter = 0;

    public function __construct($pttId)
    {
        $this->pttId = $pttId;
    }

    public function run()
    {
        $this->getMain(DEFAULT_URL);
    }

    public function result()
    {
        if ($this->contents === '') {
            return 'no data';
        }

        return $this->contents;
    }

    private function getMain($url)
    {
        $html = HtmlDomParser::file_get_html($url);
        $titles = $html->find('.title');

        foreach ($titles as $title) {
            $titleString = trim($title->plaintext);

            if ($title->find('a', 0)->href) {
                $href = URL_HEAD.$title->find('a', 0)->href;
                $subContent = $this->getSubContent($href);

                if ($subContent !== '') {
                    $this->contents .= "<h3>{$titleString}/</h3>";
                    $this->contents .= "<a href={$href} target=\"_blank\">{$href}</a><br><br>";
                    $this->contents .= $subContent;
                    $this->contents .= '<hr>';
                }
            }
        }

        $lastPage = URL_HEAD.$html->find('.btn-group-paging', 0)->find('a.btn', 1)->href;
        ++$this->counter;

        $html->clear();

        if ($this->counter < PAGES) {
            $this->getMain($lastPage);
        }
    }

    private function getSubContent($url)
    {
        $contents = '';
        $html = HtmlDomParser::file_get_html($url);
        $pushes = $html->find('div.push');

        foreach ($pushes as $push) {
            $pttId = trim($push->find('span', 1)->plaintext);

            if (strtolower($pttId) === strtolower($this->pttId)) {
                $contents .= $push->outertext;
            }
        }

        $html->clear();

        return $contents;
    }
}
