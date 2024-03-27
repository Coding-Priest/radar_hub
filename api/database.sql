-- Users Table
CREATE TABLE Users (
    User_id int PRIMARY KEY,
    Age int,
    Email varchar(255),
    Password varchar(255)
);

-- Article Topics Table
CREATE TABLE Article_Topics (
    Topic_ID int PRIMARY KEY,
    Topics varchar(255)
);

-- Article Types Table
CREATE TABLE Article_Types (
    Type_ID int PRIMARY KEY,
    Type varchar(255)
);

-- Websites Table
CREATE TABLE Websites (
    Website_ID int PRIMARY KEY,
    Website_Name varchar(255),
    Website_URL varchar(255)
);

-- Articles Table
CREATE TABLE Articles (
    Article_ID int PRIMARY KEY,
    Website_ID int,
    Topic_ID int,
    Type_ID int,
    FOREIGN KEY (Website_ID) REFERENCES Websites(Website_ID),
    FOREIGN KEY (Topic_ID) REFERENCES Article_Topics(Topic_ID),
    FOREIGN KEY (Type_ID) REFERENCES Article_Types(Type_ID)
);

-- User Type Weights Table
CREATE TABLE User_Type_Weights (
    User_ID int,
    Type_ID int,
    Type_Weight decimal(10,2),
    PRIMARY KEY (User_ID, Type_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id),
    FOREIGN KEY (Type_ID) REFERENCES Article_Types(Type_ID)
);

-- User Website Weights Table
CREATE TABLE User_Website_Weights (
    User_ID int,
    Website_ID int,
    Website_Weight decimal(10,2),
    PRIMARY KEY (User_ID, Website_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id),
    FOREIGN KEY (Website_ID) REFERENCES Websites(Website_ID)
);

-- User Topic Weights Table
CREATE TABLE User_Topic_Weights (
    User_ID int,
    Topic_ID int,
    Topic_Weight decimal(10,2),
    PRIMARY KEY (User_ID, Topic_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id),
    FOREIGN KEY (Topic_ID) REFERENCES Article_Topics(Topic_ID)
);

-- User Article Metrics Table
CREATE TABLE User_Article_Metrics (
    Article_ID int,
    User_ID int,
    Liked boolean,
    Clicked boolean,
    PRIMARY KEY (Article_ID, User_ID),
    FOREIGN KEY (Article_ID) REFERENCES Articles(Article_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id)
);

-- User Article Rating Table
CREATE TABLE User_Article_Rating (
    Article_ID int,
    User_ID int,
    Article_Rating int,
    PRIMARY KEY (Article_ID, User_ID),
    FOREIGN KEY (Article_ID) REFERENCES Articles(Article_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id)
);

-- Article Read Table
CREATE TABLE Article_Read (
    Article_ID int,
    User_ID int,
    Bool_Read boolean,
    PRIMARY KEY (Article_ID, User_ID),
    FOREIGN KEY (Article_ID) REFERENCES Articles(Article_ID),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id)
);

-- Recommendation Score Table
CREATE TABLE Recommendation_Score (
    Recommendation_ID int PRIMARY KEY,
    User_ID int,
    Article_ID int,
    Rec_Score decimal(10,2),
    FOREIGN KEY (User_ID) REFERENCES Users(User_id),
    FOREIGN KEY (Article_ID) REFERENCES Articles(Article_ID)
);
