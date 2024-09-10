# Premier League (Tournament) Simulator

## License

This project is licensed under the [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html) (or later). See the LICENSE file for more details.

## Project Setup

To run the project, follow these steps:

1. Make sure you have Docker and Docker Compose installed on your machine.
2. Clone the repository to your local system.
3. In the project root, run the following command to start the simulator:

    ```bash
    docker-compose up
    ```

4. Once it's up and running, you can access the application at `http://127.0.0.1:<port>`, where `<port>` (8084 by default) is the one specified in the `docker-compose.yaml` file.

## Code Design 

### Decision Explanation

The project was designed with simplicity in mind. Key choices include:

- **No database**: Instead of a database, all data (such as teams, matches, and results) is serialized and saved to a file. This decision avoids unnecessary complexity since we’re simulating a single tournament with small data amount.

- **No frameworks**: No need for frameworks, as the project doesn't require a database and do not provide an API. This keeps the project lightweight and minimizes overhead.

### Extension Points

The code is modular and designed to be extensible. However, certain features are implemented in a basic way, with potential for further development:

- **Teams**: Each team can be extended with additional characteristics to influence the match results. For now, match outcomes are generated randomly.

- **Match pairing algorithm**: Currently, the algorithm supports only four teams and generates matches in a basic round-robin format. The system can be expanded to accommodate more teams and different tournament formats.

## Testing and Static Analysis

This project uses the following tools for code quality and verification:

- **PHPUnit**: Used for unit testing. Run the tests with:

    ```bash
    vendor/bin/phpunit --color
    ```

- **Psalm**: A static analysis tool that ensures code quality at the highest level (Level 1). To run Psalm, use:

    ```bash
    vendor/bin/psalm.phar
    ```

Both PHPUnit and Psalm configuration files are included in the project.
