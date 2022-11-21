<?php
require_once "dbSetting.php";
session_start();
class Routine extends DBHandler
{
    private $difficultyScore = array(
        "easy" => 100,
        "normal" => 150,
        "medium" => 200,
        "hard" => 250
    );
    public function isLimitOff($date)
    {
        $today = date_format(date_create("now"), "Y-m-d");
        if ($date < $today) {
            return true;
        }
        return false;
    }

    public function setTaskDate()
    {
        $allTasks = $this->getEverythingByParam('Tasks', 'creatorID', $_SESSION["userID"]);
        foreach ($allTasks as $task) {
            if ($task["Recurrence"] == "daily") {
                $date = date_create($task["LimitDate"]);
                if ($this->isLimitOff(date_format($date, "Y-m-d"))) {
                    $this->updateTaskDate($task["ID"], 0);
                    $this->updateBooleanState("Tasks", "Complete", $task["ID"], 0);
                }
            } else if ($task["Recurrence"] == "weekly") {
                $date = date_create($task["LimitDate"]);
                if ($this->isLimitOff(date_format($date, "Y-m-d"))) {
                    $this->updateTaskDate($task["ID"], 6);
                    $this->updateBooleanState("Tasks", "Complete", $task["ID"], 0);
                }
            }
        }
    }

    public function refreshGroupScore()
    {
        $groupScore = 0;
        $userGroup = $this->getFromDbByParam('Users', 'ID', $_SESSION["userID"])['GroupID'];
        $groupScore = $this->getFromDbByParam('Groups', 'ID', $userGroup)["Score"];
        if ($groupScore < 0) {
            $this->deleteByParam("Groups", "ID", $userGroup);
            die("The group has been delete because it's score was below 0");
        }
        if ($userGroup != NULL) {
            $groupMembers = $this->getEveryThingByParam("Users", "GroupID", $userGroup);
            foreach ($groupMembers as $member) {
                $memberTasks = $this->getEveryThingByParam("Tasks", "CreatorID", $member["ID"]);
                $playerScore = $this->setPlayerScore($memberTasks);
                $this->updateScore("Users", $playerScore, $member["ID"]);
                $groupScore += $playerScore;
            }
            $this->updateScore("Groups", $groupScore, $userGroup);
        } else {
            die("You're not in a group yet, join one !");
        }
    }

    public function setPlayerScore($tasks)
    {
        $score = 0;
        foreach ($tasks as $task) {
            $date = date_create($task["LimitDate"]);
            if ($task["Recurrence"] == "daily") {
                if (!$this->isLimitOff(date_format($date, "Y-m-d")) && $task["Complete"] == 1) {
                    $score += $this->difficultyScore[$task["Difficulty"]];
                } else {
                    $score -= $this->difficultyScore[$task["Difficulty"]];
                }
            } else {
                if ($this->isLimitOff(date_format($date, "Y-m-d")) && $task["Complete"] == 1) {
                    $score += $this->difficultyScore[$task["Difficulty"]];
                } else if ($this->isLimitOff(date_format($date, "Y-m-d")) && $task["Complete"] == 0) {
                    $score -= $this->difficultyScore[$task["Difficulty"]];
                }
            }
        }
        return $score;
    }

    public function canCreateTask()
    {
        $lastLogDate = $this->getFromDbByParam('Users', 'ID', $_SESSION["userID"])['LogDate'];
        $dayLastLogDate = date_format(date_create($lastLogDate), "d/m/Y");
        if ($this->isLimitOff($dayLastLogDate)) {
            $this->updateBooleanState("Users", "CanCreate", $_SESSION["userID"], 1);
        }
    }

    public function logRoutine()
    {
        $this->refreshGroupScore();
        $this->canCreateTask();
        $this->setTaskDate();
        $this->userLoginDateUpdate($_SESSION["userID"]);
    }
}
