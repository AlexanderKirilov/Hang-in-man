<?php

/**
 *  - main Game Class
 *
 * stores:
 * 		@param   $word - Object of class Word , holds the word and helper functions (dictionary.php)
 *      @param   $wordLen - Integer, holds word length
 * 		@param   $usedLetters - Array holding already used letters;
 *      @param   $correct - Integer, correctly guessed letters - used for win-condition checking
 *      @param   $triesLeft - Integer, tracker for tries before game lose condition
 *
 * 		@param   $guessedLettersFlag - Array of 0 & 1 , used as a mask for which letters to be displayed
 *
 * has methods:
 * 		void registerInput(@param $letter - String) - Accepts input, takes care of input process, checks win condition (!) TODO: move to seperate method;
 *
 * 		!! USES UTILITY
 *   	void private searchLetterOcuranceInWord(@param $letter - String) - Performs the actual letter in word check
 *   		@return $pos - Array of positions of ocurrances
 *   		        FALSE - 0 otherwise
 *
 * 		void renderLetter(@param $pos - Integer) - Checks against the array mask and determines Letter rendering at $pos
 * 			@return Letter if already guessed
 * 			        '_' otherwise
 *      
 *      void private reset() - resets game and session 
 */		

class Game{
	private $word;

	public $usedLetters = [];

	//Using an array as a mask for which letters to display
	//0 for _
	//1 for letter at that position
	public $guessedLettersFlag = [];
	public $wordLen;

	private $correct;

	private $triesLeft;

	/**
	 * Class Constructor
	 * @param    $word
	 */
	public function __construct()
	{

		$this->word = new Word();

		$this->reset();
	}

	public function registerInput($letter){
		$letter = mb_strtolower($letter);
		
		//trim every input bigger then 1 char ??
		//$letter = $letter[0];
		
		$lettersGuessed = $this->searchLetterOcuranceInWord($letter);
		if($lettersGuessed != 0){

			$this->correct += count($lettersGuessed);
			foreach($lettersGuessed as $value){
				$this->guessedLettersFlag[$value] = 1;
			}

		}else{
			$this->triesLeft--;
		}

		$this->usedLetters[] = $letter;

		if($this->correct >= $this->wordLen){
			echo "<h1>WIN</h1>";
			session_destroy();
		}else if($this->triesLeft <= 0){
			echo "<h1>LOSE</h1>";
			session_destroy();
		}
	}

	//USES UTILITYS
	private function searchLetterOcuranceInWord($letter){
		return mb_stripos_all($this->word->getWord(), $letter);
	}


	public function renderLetter($pos){
		if($this->guessedLettersFlag[$pos] == 1){
			return $this->word->getLetterAt($pos);
		}

		return '_';
	}

	public function reset(){
		//Reset game
		$this->triesLeft = 9;
		$this->correct = 0;
		$this->usedLetters = [];

		//Reset word
		$this->word->setNewWord();
		$this->wordLen = $this->word->getWordLen();	
		
		//Reset word mask
		for($x=0;$x < $this->wordLen;$x++){
			$this->guessedLettersFlag[$x] = 0;
		}
	}

    /**
     * Gets the value of triesLeft.
     *
     * @return integer
     */
    public function getTriesLeft()
    {
        return $this->triesLeft;
    }

    /**
     * Gets the value of word.
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word->getWord();
    }
}
?>