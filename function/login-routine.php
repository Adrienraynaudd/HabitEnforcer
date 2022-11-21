<?php
require_once "dbSetting.php";
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
        $allTasks = $this->getEverythingByParam('Tasks', 'creatorID', $_SESSION["userID"]);
        foreach ($allTasks as $task) {
            if ($task["Recurrence"] == "daily") {
                if ($this->isDifferentDay($task["LimitDate"])) {
                    $this->updateTaskDate($task["ID"], 0);
                    $this->updateBooleanState("Tasks", "Complete", $task["ID"], 0);
                }
            } else if ($task["Recurrence"] == "weekly") {
                if ($this->isDifferentDay($task["LimitDate"])) {
                    $this->updateTaskDate($task["ID"], 6);
                    $this->updateBooleanState("Tasks", "Complete", $task["ID"], 0);
                }
            }
        }
    }

    public function canCreateTask()
    {
        $lastLogDate = $this->getFromDbByParam('Users', 'ID', $_SESSION["userID"])['LogDate'];
        $dayLastLogDate = date_format(date_create($lastLogDate), "d/m/Y");
        if ($this->isDifferentDay($dayLastLogDate)) {
            $this->updateBooleanState("Users", "CanCreate", $_SESSION["userID"], 1);
        }
    }

    public function logRoutine()
    {
        $this->canCreateTask();
        $this->setTaskDate();
        $this->userLoginDateUpdate($_SESSION["userID"]);
    }
}
