<?php

    use Src\PremierLeagueSimulation;

    require_once '../vendor/autoload.php';
    $storage = PremierLeagueSimulation::getStorage('premierLeague');

    /** @var string $displayOption */
    $displayOption = $_POST['displayOption'] ?? '1';
    $premierLeague = null;
    if (!isset($_POST['NewSimulation'])) {
        $premierLeague = $storage->load();
    }

    if (!$premierLeague) {
        $premierLeague = PremierLeagueSimulation::createTournament4Team();
    }

    if (isset($_POST['PlayAll'])) {
        $premierLeague->playAll();
    } elseif (isset($_POST['NextWeek'])) {
        $premierLeague->nextRound();
    }

    $finalTable = $premierLeague->getFinalTable()->getResults();
    $lastRoundResult = $premierLeague->getLastRound();
    $allRounds = $premierLeague->getAllRounds();

    $storage->save($premierLeague);
    $isFinished = $premierLeague->isFinished();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        table {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        #matchResults.hidden {
            display: none;
        }
        #fullHistory.hidden {
            display: none;
        }
    </style>
</head>
<body>

    <h2>League Table</h2>
    <table>
        <thead>
        <tr>
            <th></th>
            <th>Teams</th>
            <th title="Points">PTS</th>
            <th title="Played Games">P</th>
            <th title="Won Games">W</th>
            <th title="Dawn Games">D</th>
            <th title="Lost Games">L</th>
            <th title="Goal Difference">GD</th>
        </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($finalTable as $team) {
            $i++;
            $statistic = $team[1]->toArray();
            echo "<tr>
                <td>$i</td>
                <td>$team[0]</td>
                <td>$statistic[points]</td>
                <td>$statistic[playedGames]</td>
                <td>$statistic[wonGames]</td>
                <td>$statistic[drawnGames]</td>
                <td>$statistic[lostGames]</td>
                <td>$statistic[goalsDifference]</td>
            </tr>";
        }
        ?>
    </table>
    <?php
        function showRoundInfo(?\Src\Tournaments\RoundResult $lastRoundResult): void
        {
            echo '<thead>';
            echo '<tr><th colspan="2">' . ($lastRoundResult->roundNum ?? 0) . 'th Week Match Result</th></tr>';
            echo '</thead><tbody>';
            foreach ($lastRoundResult->plays ?? [] as $match) {
                $title = $match->matchPair->team1->getName() . ' vs ' . $match->matchPair->team2->getName();
                $text = $match->goals[0] . '-' . $match->goals[1];
                echo "<tr><td>$title</td><td style='width: 60px'>$text</td></tr>";
            }
            echo '</tbody>';
        }

        echo '<div id="matchResults"><h2>Match Results</h2>';
        echo '<table id="fullHistory">';
        foreach ($allRounds as $round) {
            if ($round->roundNum !== $lastRoundResult?->roundNum) {
                showRoundInfo($round);
            }
        }
        echo '</table>';

        echo '<table>';
        showRoundInfo($lastRoundResult);
        echo '</table>';
    ?>
    </div>
        <?php
        if (!$isFinished) {
            $chanceToWin = $premierLeague->getChanceToWinTable();

            echo '<h2>Chance to Win</h2>';
            echo '<table><thead><tr><th colspan="2">' . (!is_null($lastRoundResult) ? $lastRoundResult->roundNum : 0) . 'h Week Prediction of Championship</th>';
            echo '</tr></thead><tbody>';
            foreach ($chanceToWin as $player) {
                $title = $player[0];
                $text = (int)round($player[1] * 100) . '%';
                echo "<tr><td>$title</td><td>$text</td></tr>";
            }
            echo '</tbody></table>';
        }
        ?>
    <form action="" method="POST">
        <div class="button-container">
            <button type="submit" name="NewSimulation">New Simulation</button>
            <button type="submit" name="PlayAll" <?=$isFinished ? 'disabled' : ''?>>Play All</button>
            <button type="submit" name="NextWeek" <?=$isFinished ? 'disabled' : ''?>>Next Week</button>
        </div>

        <div class="dropdown">
            <h3 onclick="toggleOptions()">Display Options &#9662;</h3>
            <div id="optionsBox">
                <?php
                    function showRadioButton(string $id, string $value, string $selected): string
                    {
                        /** @var array<string,string> $text */
                        static $text = [
                            'hideHistory' => 'Hide Match History',
                            'showLastWeek' => 'Show Last Week',
                            'showAllHistory' => 'Show Full History',
                        ];

                        $selected = ($value == $selected) ? 'checked="checked"' : '';
                        return "<input type='radio' id='$id' name='displayOption' value='$value' onclick='toggleMatchResults()' $selected>"
                            . "<label for='$id'>" . ($text[$id] ?? '') . "</label><br>";
                    }
                    echo showRadioButton('hideHistory', '0', $displayOption);
                    echo showRadioButton('showLastWeek', '1', $displayOption);
                    echo showRadioButton('showAllHistory', '10', $displayOption);
                ?>
            </div>
        </div>
    </form>

    <script>
        function toggleOptions() {
            let optionsBox = document.getElementById("optionsBox");
            if (optionsBox.style.display === "none") {
                optionsBox.style.display = "block";
            } else {
                optionsBox.style.display = "none";
            }
        }

        function toggleMatchResults() {
            let matchResults = document.getElementById("matchResults");
            let hideHistory = document.getElementById("hideHistory").checked;

            if (hideHistory) {
                matchResults.classList.add("hidden");
            } else {
                matchResults.classList.remove("hidden");

                let fullHistory = document.getElementById("fullHistory");
                let showAllHistory = document.getElementById("showAllHistory").checked;

                if (showAllHistory) {
                    fullHistory.classList.remove("hidden");
                } else {
                    fullHistory.classList.add("hidden");
                }
            }
        }
        toggleMatchResults();
    </script>

</body>
</html>
