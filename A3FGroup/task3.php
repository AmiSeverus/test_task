<?php

class Parser {

    private $url;

    private $pattern = '/<[a-z]+[ >]+/';

    public $tags = [];

    private $text = 0;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function set_pattern($pattern)
    {
        $this->pattern = $pattern;
    }

    private function get_text()
    {
        $this->text = file_get_contents($this->url);
    }

    private function get_tags()
    {
        preg_match_all($this->pattern, $this->text, $matches);
        if(!is_array($matches) || !isset($matches[0]))
        {
            return;
        }
        foreach($matches[0] as $match)
        {
            $match = trim($match,">< ");
            if(isset($this->tags[$match]))
            {
                $this->tags[$match]++;
            } else {
                $this->tags[$match] = 1;
            }
        }
        ksort($this->tags);
    }

    public function execute()
    {
        $this->get_text();
        $this->get_tags();
        return $this->tags;
    }
}

$parser = new Parser('https://www.google.com/');
print_r($parser->execute());