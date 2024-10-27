### Assumptions
The task was completed with the assumptions that the principles of SOLID, OO Design, and TDD MUST be applied, while also following the KISS principle.

PHP version ^8.1 is required to run the application. Composer dependencies should be installed with the command: `composer install`

The code is located in the `./src` directory. Tests are located in the ./tests directory and utilize PHPUnit.

Tests can be run with the following command: `./vendor/bin/phpunit`

### Naming Conventions
The term Game is used instead of Match because Match is a reserved word in PHP 8.

### Components
1. Scoreboard - A facade providing an interface for our library.
2. Game - A class representing a game. It includes information about the teams, score, and game duration. It implements the GameInterface, GameScoreManagementInterface, and GameStateInterface interfaces. Three interfaces were created in line with the Interface Segregation Principle. The term Game is used instead of Match to avoid conflict with the reserved word in PHP 8. For simplicity, Game objects have setters; however, in a real-world scenario, making Game objects completely immutable could be considered.
3. Team - A class representing a team. It contains information about the team’s name and roster. The team’s name serves as its only identifier. In a real-world scenario, a unique team identifier would be available, which would impact the final shape of the implementation.

### Documentation
Documentation has been created in the PHPDoc format. In a real-world project, eliminating comments entirely could be considered, as the code is self-documenting.