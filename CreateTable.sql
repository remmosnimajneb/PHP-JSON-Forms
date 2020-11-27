CREATE TABLE `Submissions` (
  `SubmissionID` int(9) NOT NULL,
  `FormName` varchar(60) NOT NULL,
  `SubmissionContent` longtext DEFAULT NULL,
  `Date` datetime DEFAULT current_timestamp(),
  `IPAddress` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `submissions`
  ADD PRIMARY KEY (`SubmissionID`);
ALTER TABLE `submissions`
  MODIFY `SubmissionID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;