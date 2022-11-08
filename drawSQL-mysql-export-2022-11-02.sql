
CREATE TABLE `Tasks`(
    `TaskID` VARCHAR(255) NOT NULL,
    PRIMARY KEY(`TaskID`),
    `Name` VARCHAR(255) NOT NULL COMMENT 'task name',
    `Description` VARCHAR(255) NOT NULL COMMENT 'task descriptio,',
    `Difficulty` INT NOT NULL COMMENT 'difficulty (1: easy, 2: medium, 3:hard)',
    `TimeSpan` VARCHAR(255) NOT NULL COMMENT 'timespan (daily, weekly, monthly)',
    `CreationDate` DATETIME NOT NULL COMMENT 'creation date, use for time span calculation',
    `CategoryID` VARCHAR(255) NOT NULL COMMENT 'tasks category ID'
);
CREATE TABLE `Groups`(
    `ID` VARCHAR(255) NOT NULL COMMENT 'groupe ID',
    PRIMARY KEY(`ID`),
    `Score` INT NOT NULL COMMENT 'groupe score',
    `Members` VARCHAR(255) NOT NULL COMMENT 'group members (form : ID,ID,ID)',
    `GroupCreator` VARCHAR(255) NOT NULL COMMENT 'groupe creator ID'
);
CREATE TABLE `Users`(
    `ID` VARCHAR(255) NOT NULL COMMENT 'User ID',
     PRIMARY KEY(`ID`),
    `Username` VARCHAR(255) NOT NULL COMMENT 'user username',
    UNIQUE(`Username`),
    `Password` VARCHAR(255) NOT NULL COMMENT 'User hashed password',
    `Email` VARCHAR(255) NOT NULL COMMENT 'user email',
     `IDTasks` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`IDTasks`) REFERENCES `Tasks`(`TaskID`) ON DELETE CASCADE,
    `GroupID` VARCHAR(255) NULL COMMENT 'groupeID of users group',
    FOREIGN KEY (`GroupID`) REFERENCES `Groups`(`ID`) ON DELETE CASCADE,
    `LastConnexion`DATETIME NOT NULL COMMENT 'last user connexion date'
);
CREATE TABLE `TasksCategories`(
    `ID`VARCHAR(255) NOT NULL COMMENT 'Category ID',
    PRIMARY KEY(`ID`),
    `Name`VARCHAR(255) NOT NULL COMMENT 'category name',
    `Color`VARCHAR(255) NOT NULL COMMENT 'category color (format: #eeeee)',
    `CreatorID`VARCHAR(255) NOT NULL COMMENT 'creator ID'
);