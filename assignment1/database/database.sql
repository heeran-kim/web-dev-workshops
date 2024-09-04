DROP TABLE IF EXISTS Owners;
DROP TABLE IF EXISTS Listings;
DROP TABLE IF EXISTS Images;
DROP TABLE IF EXISTS Reviews;

CREATE TABLE IF NOT EXISTS Owners (
    id                  INTEGER         PRIMARY KEY,
    name                VARCHAR(20)     NOT NULL    UNIQUE
);

CREATE TABLE IF NOT EXISTS Listings (
    id                  INTEGER     PRIMARY KEY,
    title               VARCHAR(20)     NOT NULL,
    street              VARCHAR(50),
    city                VARCHAR(20)     NOT NULL,
    state               VARCHAR(10)     NOT NULL,
    rent                INTEGER         NOT NULL,
    available_date      DATE,
    is_furnished        BOOLEAN,
    is_bill_included    BOOLEAN,
    description         TEXT,
    owner_name          INTEGER         NOT NULL    REFERENCES Owners(name),
    average_rating      DECIMAL(2,1),
    review_count        INTEGER         DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Reviews (
    id                  INTEGER         PRIMARY KEY,
    user_name           VARCHAR(20)     NOT NULL,
    rating              INTEGER         NOT NULL    CHECK(Rating IN (1, 2, 3, 4, 5)),
    date                DATETIME,
    review              TEXT,
    listing_id          INTEGER         NOT NULL    REFERENCES Listings(Id)
);

INSERT INTO Owners(name)
    VALUES
    ("Heeran"),
    ("Jinwoo"),
    ("John Doe");

INSERT INTO Listings (title, street, city, state, rent, available_date, is_furnished, is_bill_included, description, owner_name)
    VALUES 
    ('Cozy Studio',
    '123 Elm Street', 'Sydney', 'NSW',
    300, '2024-09-01', 1, 1, 
    'This cozy studio apartment is located in the heart of Sydney. It offers a comfortable living space perfect for singles or couples. The apartment is fully furnished and includes all essential utilities. Enjoy the convenience of city living with easy access to shopping, dining, and entertainment.', 
    'Heeran'),

    ('Spacious House',
    '456 Oak Avenue', 'Melbourne', 'VIC',
    600, '2024-09-15', 1, 0, 
    'This spacious 3-bedroom house in Melbourne is perfect for families. The property features a large backyard, ideal for outdoor activities and gatherings. The house is fully furnished with modern amenities, offering both comfort and style. Located in a quiet neighborhood, yet close to local schools, parks, and shopping centers.',
    'Heeran'),

    ('Modern Flat',
    '789 Pine Road', 'Brisbane', 'QLD',
    400, '2024-10-01', 0, 1, 
    'This modern flat in Brisbane is designed with contemporary living in mind. The open-plan layout creates a spacious and airy atmosphere. The flat comes with all the latest amenities, including a fully equipped kitchen and high-speed internet. It is situated in a vibrant area, close to cafes, restaurants, and public transport.',
    'Jinwoo'),
    
    ('Luxury Apartment',
    '101 Maple Street', 'Perth',
    'WA', 800, '2024-11-01', 1, 1, 
    'This luxury apartment in Perth offers premium living with top-notch amenities. The apartment is fully furnished and includes all utilities, making it ideal for professionals. Located in an upscale area, it is close to fine dining, shopping, and cultural attractions. The building features a gym, pool, and 24-hour security.',
    'John Doe');

-- INSERT INTO Reviews(rating, date, review, listing_id, user_id)
--     VALUES
--     (4, '2024-08-15', 'Great location and cozy space. Highly recommend!',               1, 2),
--     (3, '2024-08-20', 'Spacious house but had some issues with the heating.',           1, 2),
--     (5, '2024-09-01', 'Absolutely loved the modern flat! Everything was perfect.',      2, 2),
--     (4, '2024-10-30', 'Beautiful view and close to the beach. Would stay again.',       2, 2),
--     (3, '2024-11-10', 'Nice loft but a bit noisy at night. Great access to downtown.',  3, 1);
