<?php
namespace Model;

class WordService
{
    private $words  = array();
    private $wordFileContent;

    function __construct()
    {
        $this->loadWordsFileContents();

    }

    private function loadWordsFileContents()
    {

        $handle = fopen(getcwd()."/src/app/Dictionary/words.english", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $this->words[]  = trim($line);
            }

            fclose($handle);
        } else {
            // error opening the file.
        }
    }

    public function getRandomWord()
    {
        return $this->words[rand(0,123456)];
    }

}