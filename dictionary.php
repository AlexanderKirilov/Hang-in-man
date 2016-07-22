<?php
//Very (!) simple Dictionary class.
//Uses an external txt file, which contains 1 word per line
//Wrapper of fopen/fget/fclose cycle
//Verry basic error handle
//Uses P=1/n for the random word generationg , AKA even distribution

class Dictionary{
	
	private $fileHandler = NULL;

	/* for some MOST BIZARE reason turns out you CANNOT use variables/consts with fopen ON WINDOWS

	!! BUT works if I was running linx ???!??? what ? Took a night to figure out


	const FILESRC = 'resources/dictionary.txt';

	//Just in case I want to extend class to write dictionary;
	const FILEMODE = 'r';
	*/
	
	const MAXLINES = 1024;

	protected function openFile(){
		if(!isset($this->fileHandler) || $this->fileHandler == FALSE) $this->fileHandler = fopen('resources/dictionary.txt', 'r'); 
	}
	
	public function getRandWord(){
		$this->openFile();

		$count = 0;
		$line = NULL;
		$randomLine = NULL;

		while( $line = fgets($this->fileHandler,self::MAXLINES)){
			$count++;

			if(rand() % $count == 0 ){
				$randomLine = $line;
			}
		}
		
		//some very basic error handling;
		if (!feof($this->fileHandler)) {
            echo "Error: unexpected fgets() fail\n";
            fclose($this->fileHandler);
            return NULL;
        } else {
            fclose($this->fileHandler);
        }

        return trim($randomLine);
	}
}
class Word{

	private $Word;

	private $Dictionary;

	private $wordLen;
	/**
	 * Class Constructor
	 * @param    $Word   
	 */
	public function __construct()
	{
		$this->Dictionary = new Dictionary();

		$this->Word = $this->Dictionary->getRandWord();
		
		$this->WordLen = mb_strlen($this->Word);
	}


    /**
     * Gets the value of Word.
     *
     * @return mixed
     */
    public function getWord()
    {
        return $this->Word;
    }

    /**
     * Sets the value of Word.
     * And returns it <--
     *
     * @param mixed $Word the word
     *
     * @return self
     */
    public function setNewWord(){
        
        $this->Word = $this->Dictionary->getRandWord();

        $this->WordLen = mb_strlen($this->Word);

        return $this->Word;
    }

    /**
     * Gets the value of wordLen.
     *
     * @return string
     */
    public function getWordLen()
    {
        return $this->WordLen;
    }

    /**
     * Gets the value of wordLen.
     *
     * @return string
     */
    public function getLetterAt($at)
    {
    	if($at <= $this->WordLen){
        	return $this->Word{$at};
    	}
    	return -1;
    }
}
