DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Listings;
DROP TABLE IF EXISTS Images;
DROP TABLE IF EXISTS Reviews;

CREATE TABLE IF NOT EXISTS Users (
    Id              INTEGER     PRIMARY KEY,
    Name            VARCHAR(20) NOT NULL,
    Email           VARCHAR(30) NOT NULL    UNIQUE,
    Password        VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS Listings (
    Id              INTEGER     PRIMARY KEY,
    Title           VARCHAR(20) NOT NULL,
    Street          VARCHAR(50),
    City            VARCHAR(50) NOT NULL,
    State           VARCHAR(30) NOT NULL,
    Rent            INTEGER     NOT NULL,
    AvailableDate   DATE,
    IsFurnished     BOOLEAN,
    IsBillIncluded  BOOLEAN,
    Description     TEXT,
    UserId          INTEGER     NOT NULL    REFERENCES Users(Id)
);

CREATE TABLE IF NOT EXISTS Reviews (
    Id              INTEGER     PRIMARY KEY,
    Rating          INTEGER     NOT NULL    CHECK(Rating IN (1, 2, 3, 4, 5)),
    Date            DATE,
    Review          TEXT,
    ListingId       INTEGER     NOT NULL    REFERENCES Listings(Id),
    UserId          INTEGER     NOT NULL    REFERENCES Users(Id)
);

INSERT INTO Users(Name, Email, Password)
    VALUES
    ("Heeran",      "heerankim@gmail.com",  "password"),
    ("Jinwoo",      "jinwookim@gmail.com",  "password"),
    ("John Doe",    "johndoe@gmail.com",    "password");

INSERT INTO Listings (Title, Street, City, State, Rent, AvailableDate, IsFurnished, IsBillIncluded, Description, UserId)
    VALUES 
    ('Cozy Studio',
    '123 Elm Street', 'Sydney', 'NSW',
    300, '2024-09-01', 1, 1, 
    'This cozy studio apartment is located in the heart of Sydney. It offers a comfortable living space perfect for singles or couples. The apartment is fully furnished and includes all essential utilities. Enjoy the convenience of city living with easy access to shopping, dining, and entertainment.', 
    1),

    ('Spacious House',
    '456 Oak Avenue', 'Melbourne', 'VIC',
    600, '2024-09-15', 1, 0, 
    'This spacious 3-bedroom house in Melbourne is perfect for families. The property features a large backyard, ideal for outdoor activities and gatherings. The house is fully furnished with modern amenities, offering both comfort and style. Located in a quiet neighborhood, yet close to local schools, parks, and shopping centers.',
    1),

    ('Modern Flat',
    '789 Pine Road', 'Brisbane', 'QLD',
    400, '2024-10-01', 0, 1, 
    'This modern flat in Brisbane is designed with contemporary living in mind. The open-plan layout creates a spacious and airy atmosphere. The flat comes with all the latest amenities, including a fully equipped kitchen and high-speed internet. It is situated in a vibrant area, close to cafes, restaurants, and public transport.',
    2),
    
    ('Luxury Apartment',
    '101 Maple Street', 'Perth',
    'WA', 800, '2024-11-01', 1, 1, 
    'This luxury apartment in Perth offers premium living with top-notch amenities. The apartment is fully furnished and includes all utilities, making it ideal for professionals. Located in an upscale area, it is close to fine dining, shopping, and cultural attractions. The building features a gym, pool, and 24-hour security.',
    3);

INSERT INTO Reviews(Rating, Date, Review, ListingId, UserId)
    VALUES
    (4, '2024-08-15', 'Great location and cozy space. Highly recommend!',               1, 2),
    (3, '2024-08-20', 'Spacious house but had some issues with the heating.',           1, 2),
    (5, '2024-09-01', 'Absolutely loved the modern flat! Everything was perfect.',      2, 2),
    (4, '2024-10-30', 'Beautiful view and close to the beach. Would stay again.',       2, 2),
    (3, '2024-11-10', 'Nice loft but a bit noisy at night. Great access to downtown.',  3, 1);
