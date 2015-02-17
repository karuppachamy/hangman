<?php
namespace Model;

class WordService
{
    /**
     * @var array
     */
    private $words  = array();

    /**
     * Constructor.
     */
    public  function __construct()
    {
        $this->loadWordsFileContents();

    }

    /**
     * Load the words from the dictionary provided.
     */
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

    /**
     * Get a single word from the dictionary at a tiem.
     *
     * @return string
     */
    public function getRandomWord()
    {
        return $this->words[rand(0, 123456)];
    }
}
