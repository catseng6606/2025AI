
``` sql
IF OBJECT_ID(N'Enrollments', N'U') IS NOT NULL
    DROP TABLE Enrollments;
GO
CREATE TABLE Enrollments (
    EnrollmentID INT IDENTITY(1,1) PRIMARY KEY,
    StudentID INT NOT NULL, -- 假設這裡的 StudentID 實際上就是 Borrowers.BorrowerID (為了簡化課程練習)
    CourseID NVARCHAR(50) NOT NULL,
    EnrollmentDate DATE NOT NULL,
    Grade NVARCHAR(20) NULL, -- 成績，可為空
    CONSTRAINT UQ_Enrollments_Student_Course UNIQUE (StudentID, CourseID) -- 確保一個學生不能重複選同一門課
);
GO
```


