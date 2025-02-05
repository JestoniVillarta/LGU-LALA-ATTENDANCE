const randomNumber = Math.floor(Math.random() * 100) + 1;
let attempts = 0;

function guessNumber(userGuess) {
    attempts++;
    if (userGuess < randomNumber) {
        console.log("Too low! Try again.");
    } else if (userGuess > randomNumber) {
        console.log("Too high! Try again.");
    } else {
        console.log(`Congratulations! You guessed it in ${attempts} attempts.`);
    }
}

// Example usage:
guessNumber(50);
