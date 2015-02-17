# hangman
Simple Hangman Game .Based Slim framework, Doctrine, Respect validation, PHPUnit.

Installation: 
1.Download the source
2. Make sure you have composer installed
3. Run composer install from the root directory.
4. Import the sql found in the repository.

Rules:
1. Guess a letter
2. if you guessed correctly , underscores will be replaced by the correct guessed characters.
3. Maximum 11 guesses allowed.
4. Correct guess don't reduce the remaining tries.


API usage: (Replace localhost with your own hostname)
Create new game[POST]:
http://localhost:8080/hangman/game

Guess[PUT]:
http://localhost:8080/hangman/game/{id}
Put params: {"char":"n"}

Get all the available games:
http://localhost:8080/hangman/game

