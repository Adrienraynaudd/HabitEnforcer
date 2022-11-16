<?php
require "dbSetting.php";
session_start();
class Routine extends DBHandler
{
    public function isDifferentDay($date)
    {
        $today = date_format(date_create("now"), "Y-m-d h:m:s");
        if ($date < $today) {
            return true;
        }
        return false;
    }

    public function setTaskDate()
    {
        $allTasks = $this->getEverythingByParam('Tasks', 'creatorID', $this->getIDwithName('Users', 'test'));
        foreach ($allTasks as $task) {
            if ($task["Recurrence"] == "daily") {
                if ($this->isDifferentDay($task["LimitDate"])) {
                    $this->updateTaskDate($task["ID"], 0);
                }
            } else if ($task["Recurrence"] == "weekly") {
                if ($this->isDifferentDay($task["LimitDate"])) {
                    $this->updateTaskDate($task["ID"], 6);
                }
            }
        }
    }

    public function canCreateTask()
    {
        $lastLogDate = $this->getFromDbByParam('Users', 'ID', $this->getIDwithName('Users', 'test'))['LogDate'];
        $dayLastLogDate = date_format(date_create($lastLogDate), "d/m/Y");
        if ($this->isDifferentDay($dayLastLogDate)) {
            $this->updateBooleanState("Users", "CanCreate", $this->getIDwithName('Users', 'test'), 1);
        }
    }

    public function logRoutine()
    {
        $this->userLoginDateUpdate($_SESSION['userID']);
        $this->canCreateTask();
        $this->setTaskDate();
    }
}

$routine = new Routine;
$routine->setTaskDate();
