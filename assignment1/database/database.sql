DROP TABLE IF EXISTS Owners;
DROP TABLE IF EXISTS Listings;
DROP TABLE IF EXISTS Reviews;

CREATE TABLE IF NOT EXISTS Owners (
    id                  INTEGER         PRIMARY KEY,
    name                VARCHAR(20)     NOT NULL    UNIQUE
);

CREATE TABLE IF NOT EXISTS Listings (
    id                  INTEGER         PRIMARY KEY,
    title               VARCHAR(20)     NOT NULL,
    street              VARCHAR(50),
    city                VARCHAR(20)     NOT NULL,
    state               VARCHAR(10)     NOT NULL,
    rent                INTEGER         NOT NULL,
    available_date      DATE,
    is_furnished        BOOLEAN,
    is_bill_included    BOOLEAN,
    description         TEXT,
    average_rating      DECIMAL(2,1),
    review_count        INTEGER         DEFAULT 0,
    owner_name          INTEGER         NOT NULL    REFERENCES Owners(name)
);

CREATE TABLE IF NOT EXISTS Reviews (
    id                  INTEGER         PRIMARY KEY,
    user_name           VARCHAR(20)     NOT NULL,
    rating              INTEGER         NOT NULL    CHECK(Rating IN (1, 2, 3, 4, 5)),
    created_at          DATETIME,
    review_text         TEXT,
    listing_id          INTEGER         NOT NULL    REFERENCES Listings(Id)
);

INSERT INTO Owners (name)
    VALUES 
        ('Alice Johnson'),
        ('Bob Smith'),
        ('Charlie Davis'),
        ('Dana White'),
        ('Eli Brown'),
        ('Fiona Clark'),
        ('George Evans'),
        ('Hannah Wilson'),
        ('Ivy Taylor'),
        ('Jack Morgan');

INSERT INTO Listings (title, street, city, state, rent, available_date, is_furnished, is_bill_included, description, owner_name)
VALUES
    -- Alice Johnson (2 listings)
    ('Cozy Apartment', '123 Elm St', 'Sydney', 'NSW', 150, '2024-10-01', 1, 0, 'A cozy one-bedroom apartment.', 'Alice Johnson'),
    ('Modern Flat', '456 Pine St', 'Sydney', 'NSW', 200, '2024-09-15', 1, 1, 'A modern flat with city views.', 'Alice Johnson'),

    -- Bob Smith (5 listings)
    ('Spacious House', '789 Oak St', 'Melbourne', 'VIC', 250, '2024-09-10', 1, 0, 'Spacious house with a garden.', 'Bob Smith'),
    ('Studio', '101 Maple St', 'Melbourne', 'VIC', 120, '2024-09-20', 0, 1, 'Compact studio for single professionals.', 'Bob Smith'),
    ('Luxury Apartment', '102 Birch St', 'Melbourne', 'VIC', 300, '2024-09-30', 1, 1, 'Luxury apartment with a pool.', 'Bob Smith'),
    ('Cottage', '202 Willow St', 'Melbourne', 'VIC', 180, '2024-10-05', 1, 0, 'Charming cottage near the beach.', 'Bob Smith'),
    ('Suburban Home', '303 Cedar St', 'Melbourne', 'VIC', 220, '2024-11-01', 0, 0, 'Family home in a quiet suburb.', 'Bob Smith'),

    -- Charlie Davis (0 listings)
    -- No listings for Charlie Davis

    -- Dana White (4 listings)
    ('Penthouse Suite', '123 King St', 'Brisbane', 'QLD', 350, '2024-10-10', 1, 1, 'Penthouse with rooftop access.', 'Dana White'),
    ('Beach House', '789 Ocean Ave', 'Brisbane', 'QLD', 280, '2024-09-25', 0, 0, 'Beachfront house with stunning views.', 'Dana White'),
    ('City Loft', '456 Queen St', 'Brisbane', 'QLD', 220, '2024-09-12', 1, 1, 'Loft apartment in the heart of the city.', 'Dana White'),
    ('Suburban Villa', '321 Palm St', 'Brisbane', 'QLD', 260, '2024-09-15', 1, 1, 'Luxury villa with a private pool.', 'Dana White'),

    -- Eli Brown (3 listings)
    ('Townhouse', '654 Elm St', 'Adelaide', 'SA', 200, '2024-09-18', 0, 1, 'Modern townhouse with two bedrooms.', 'Eli Brown'),
    ('Garden Cottage', '789 Rose St', 'Adelaide', 'SA', 170, '2024-09-28', 1, 0, 'Charming garden cottage.', 'Eli Brown'),
    ('Apartment', '101 River St', 'Adelaide', 'SA', 130, '2024-10-01', 1, 1, 'Cozy apartment near the park.', 'Eli Brown'),

    -- Fiona Clark (1 listing)
    ('Downtown Apartment', '123 Market St', 'Perth', 'WA', 220, '2024-09-05', 1, 1, 'Apartment in the downtown area.', 'Fiona Clark'),

    -- George Evans (7 listings)
    ('Family Home', '456 Oak Ave', 'Canberra', 'ACT', 240, '2024-09-01', 1, 0, 'Spacious family home in a quiet area.', 'George Evans'),
    ('Luxury Villa', '789 Pine Ave', 'Canberra', 'ACT', 320, '2024-09-25', 1, 1, 'Luxury villa with private garden.', 'George Evans'),
    ('City Apartment', '101 Spruce St', 'Canberra', 'ACT', 180, '2024-09-12', 0, 1, 'Apartment with great city views.', 'George Evans'),
    ('Cottage', '234 Maple Ave', 'Canberra', 'ACT', 200, '2024-10-02', 1, 0, 'Small cottage near the city center.', 'George Evans'),
    ('Suburban House', '567 Oak Ln', 'Canberra', 'ACT', 190, '2024-09-15', 1, 1, 'House in a family-friendly suburb.', 'George Evans'),
    ('Beach House', '891 Sea View Rd', 'Canberra', 'ACT', 250, '2024-09-20', 1, 1, 'House near the beach with ocean views.', 'George Evans'),
    ('Penthouse', '345 Mountain St', 'Canberra', 'ACT', 350, '2024-10-10', 1, 1, 'Luxury penthouse with panoramic views.', 'George Evans'),

    -- Hannah Wilson (6 listings)
    ('Urban Loft', '456 Cherry St', 'Hobart', 'TAS', 160, '2024-09-10', 0, 1, 'Loft apartment in an urban setting.', 'Hannah Wilson'),
    ('Suburban Townhouse', '789 Willow Ln', 'Hobart', 'TAS', 180, '2024-09-05', 1, 0, 'Townhouse in a quiet neighborhood.', 'Hannah Wilson'),
    ('Lakeview Villa', '101 River St', 'Hobart', 'TAS', 250, '2024-09-15', 1, 1, 'Villa with a view of the lake.', 'Hannah Wilson'),
    ('City Center Apartment', '123 Park St', 'Hobart', 'TAS', 210, '2024-09-20', 1, 1, 'Apartment in the city center.', 'Hannah Wilson'),
    ('Mountain Cabin', '456 Peak Ave', 'Hobart', 'TAS', 190, '2024-09-30', 1, 0, 'Cabin with mountain views.', 'Hannah Wilson'),
    ('Downtown Condo', '789 Elm St', 'Hobart', 'TAS', 230, '2024-10-05', 1, 1, 'Condo in the downtown area.', 'Hannah Wilson'),

    -- Ivy Taylor (10 listings)
    ('Seaside Villa', '123 Ocean Blvd', 'Darwin', 'NT', 270, '2024-09-15', 1, 1, 'Villa with a view of the sea.', 'Ivy Taylor'),
    ('Country House', '456 Maple St', 'Darwin', 'NT', 220, '2024-09-20', 0, 1, 'House in the countryside.', 'Ivy Taylor'),
    ('Urban Studio', '789 Pine St', 'Darwin', 'NT', 140, '2024-09-25', 1, 0, 'Studio in an urban area.', 'Ivy Taylor'),
    ('Luxury Penthouse', '101 Oak Ln', 'Darwin', 'NT', 300, '2024-10-05', 1, 1, 'Luxury penthouse with city views.', 'Ivy Taylor'),
    ('Cottage', '123 Birch St', 'Darwin', 'NT', 180, '2024-09-30', 0, 0, 'Cottage near the lake.', 'Ivy Taylor'),
    ('City Apartment', '456 Spruce St', 'Darwin', 'NT', 190, '2024-09-18', 1, 1, 'Apartment in the heart of the city.', 'Ivy Taylor'),
    ('Beach Cottage', '789 Palm St', 'Darwin', 'NT', 230, '2024-10-01', 1, 0, 'Cottage near the beach.', 'Ivy Taylor'),
    ('Suburban Home', '101 Willow St', 'Darwin', 'NT', 210, '2024-10-10', 0, 1, 'Home in a suburban area.', 'Ivy Taylor'),
    ('Cabin', '123 River St', 'Darwin', 'NT', 160, '2024-10-15', 0, 0, 'Cabin in a rural setting.', 'Ivy Taylor'),
    ('Downtown Loft', '456 Market St', 'Darwin', 'NT', 250, '2024-10-20', 1, 1, 'Loft in the downtown area.', 'Ivy Taylor'),

    -- Jack Morgan (2 listings)
    ('City Condo', '789 Birch Ln', 'Gold Coast', 'QLD', 200, '2024-09-05', 1, 1, 'Condo in the city center.', 'Jack Morgan'),
    ('Suburban House', '101 Cedar St', 'Gold Coast', 'QLD', 180, '2024-09-10', 1, 0, 'House in a quiet suburban area.', 'Jack Morgan');

INSERT INTO Reviews (user_name, rating, created_at, review_text, listing_id)
VALUES
    -- Reviews for listing 1 (2 reviews)
    ('Emily Brown', 5, '2023-08-15', 'Absolutely loved this place! It was cozy and had everything I needed.', 1),
    ('John Doe', 4, '2023-08-20', 'Great apartment, but the area can get a bit noisy at night.', 1),

    -- Reviews for listing 2 (3 reviews)
    ('Sophia White', 5, '2023-09-01', 'Amazing views and a modern design. Would stay here again!', 2),
    ('Chris Green', 3, '2023-09-05', 'The flat was nice, but it was smaller than expected.', 2),
    ('Emma Watson', 4, '2023-09-10', 'Very clean and well-kept, but a bit pricey for what you get.', 2),

    -- Reviews for listing 3 (5 reviews)
    ('Liam Smith', 4, '2023-09-01', 'Great house with a lot of space. The backyard was a huge plus.', 3),
    ('Olivia Davis', 5, '2023-09-07', 'Perfect for families. We had a great time!', 3),
    ('Ava Johnson', 2, '2023-09-12', 'The house was spacious but felt outdated and could use some repairs.', 3),
    ('Noah Williams', 3, '2023-09-15', 'Good location, but the house wasn’t as clean as I expected.', 3),
    ('Isabella Brown', 4, '2023-09-18', 'Nice home, but the kitchen could have been better equipped.', 3),

    -- Reviews for listing 4 (4 reviews)
    ('Ethan Wilson', 3, '2023-09-02', 'Basic but functional. It worked for my short stay.', 4),
    ('Mia Anderson', 2, '2023-09-09', 'Too small for the price. Not worth it.', 4),
    ('Lucas Thomas', 4, '2023-09-16', 'Compact but well-designed studio. Would recommend.', 4),
    ('Amelia Martinez', 5, '2023-09-20', 'Fantastic! The perfect space for a single traveler.', 4),

    -- Reviews for listing 5 (3 reviews)
    ('Oliver Jackson', 5, '2023-09-03', 'Incredible luxury apartment with stunning views!', 5),
    ('Charlotte Harris', 4, '2023-09-11', 'Very elegant and modern. Loved the amenities.', 5),
    ('Mason Clark', 3, '2023-09-15', 'Beautiful but felt overpriced for what was offered.', 5),

    -- Reviews for listing 6 (0 reviews)
    -- No reviews for listing 6

    -- Reviews for listing 7 (7 reviews)
    ('Aiden Lewis', 5, '2023-09-02', 'Charming cottage in a perfect location!', 7),
    ('Abigail Walker', 4, '2023-09-05', 'Nice little cottage, though the beach was further than expected.', 7),
    ('Michael Hall', 2, '2023-09-08', 'House was cute, but not as close to amenities as advertised.', 7),
    ('Emily Harris', 3, '2023-09-10', 'Good for a short getaway, but there were issues with plumbing.', 7),
    ('Lucas Johnson', 5, '2023-09-13', 'Absolutely loved this place! We will definitely return.', 7),
    ('Madison Robinson', 1, '2023-09-16', 'Very disappointed. The house was in poor condition.', 7),
    ('Ella Lopez', 4, '2023-09-18', 'Great stay overall, but the backyard could use some work.', 7),

    -- Reviews for listing 8 (6 reviews)
    ('James Young', 5, '2023-09-01', 'Perfect home for our family. Spacious and cozy.', 8),
    ('Isla King', 4, '2023-09-05', 'Lovely house, but a bit far from the city center.', 8),
    ('Jacob Wright', 3, '2023-09-09', 'Good house, but could use some updates in the kitchen.', 8),
    ('Grace Scott', 2, '2023-09-12', 'Not as advertised. Several issues with cleanliness.', 8),
    ('Henry Allen', 5, '2023-09-15', 'Loved it! Great value for the price.', 8),
    ('Sofia Perez', 4, '2023-09-18', 'Very comfortable and well-maintained.', 8),

    -- Reviews for listing 9 (2 reviews)
    ('Alexander Carter', 4, '2023-09-02', 'Nice apartment with easy access to public transport.', 9),
    ('Lily Reed', 3, '2023-09-08', 'Good location, but apartment was smaller than expected.', 9),

    -- Reviews for listing 10 (1 review)
    ('Chloe Howard', 5, '2023-09-05', 'Incredible penthouse with amazing views!', 10),

    -- Reviews for listing 11 (8 reviews)
    ('David Nelson', 4, '2023-09-01', 'Lovely place with great views, but a bit noisy.', 11),
    ('Penelope Foster', 3, '2023-09-06', 'Good location, but the space was smaller than advertised.', 11),
    ('Benjamin Gonzalez', 5, '2023-09-12', 'Perfect for a family vacation. We had a wonderful time.', 11),
    ('Zoey Ross', 1, '2023-09-17', 'Terrible experience. The house was dirty, and the owner was unresponsive.', 11),
    ('Carter Hernandez', 2, '2023-09-21', 'Not worth the money. The house was in bad shape.', 11),
    ('Scarlett Perry', 4, '2023-09-23', 'Nice house, but the bathroom needed updating.', 11),
    ('Owen Cooper', 5, '2023-09-25', 'Fantastic! Would definitely stay again.', 11),
    ('Layla Bailey', 3, '2023-09-28', 'Decent place, but not very close to the beach.', 11),

    -- Reviews for listing 12 (0 reviews)
    -- No reviews for listing 12

    -- Reviews for listing 13 (9 reviews)
    ('Evelyn Murphy', 5, '2023-09-01', 'Lovely cabin with beautiful mountain views.', 13),
    ('Jack Sanders', 3, '2023-09-04', 'Decent cabin, but there were issues with the heating.', 13),
    ('Daniel Long', 2, '2023-09-07', 'Too cold for our stay, and the amenities were basic.', 13),
    ('Aurora Hughes', 4, '2023-09-10', 'Great cabin for the price. Could use better heating.', 13),
    ('Sebastian Cook', 5, '2023-09-12', 'Loved it! Great place for a quiet getaway.', 13),
    ('Levi Morgan', 4, '2023-09-14', 'Cozy cabin with all the essentials.', 13),
    ('Victoria Parker', 1, '2023-09-18', 'Very disappointed. The cabin was dirty, and the heating was insufficient.', 13),
    ('Zoey Sanders', 5, '2023-09-20', 'Wonderful stay! Everything was perfect.', 13),
    ('Thomas Barnes', 3, '2023-09-22', 'Good cabin, but it needed some maintenance.', 13),

    -- Reviews for listing 14 (10 reviews)
    ('Natalie Wood', 5, '2023-09-02', 'Perfect location near the beach. We loved our stay.', 14),
    ('Leo Brooks', 4, '2023-09-05', 'Great house, but parking was an issue.', 14),
    ('Hazel Bell', 3, '2023-09-08', 'Nice home, but not as clean as expected.', 14),
    ('Matthew Barnes', 2, '2023-09-12', 'House was fine, but the owner was not responsive.', 14),
    ('Julian Ross', 1, '2023-09-14', 'Terrible experience. The house was in bad condition.', 14),
    ('Ellie Ward', 4, '2023-09-16', 'Good value for the price. Close to the beach.', 14),
    ('Grace Carter', 3, '2023-09-18', 'Decent stay, but there were issues with the plumbing.', 14),
    ('Mila Wilson', 5, '2023-09-20', 'Amazing home! We had a great time.', 14),
    ('Caleb Griffin', 5, '2023-09-23', 'Fantastic! Perfect for a family vacation.', 14),
    ('Savannah Wright', 2, '2023-09-26', 'Disappointed with the cleanliness of the house.', 14),

    -- Reviews for listing 15 (6 reviews)
    ('Elijah Torres', 5, '2023-09-01', 'Lovely home with a fantastic view. Everything was perfect.', 15),
    ('Luna Scott', 4, '2023-09-05', 'Great stay, but the kitchen could use a few more utensils.', 15),
    ('Anthony Edwards', 3, '2023-09-10', 'Good home, but the location was further from the city center.', 15),
    ('Aria Sanders', 2, '2023-09-12', 'House was in okay condition, but needed a thorough cleaning.', 15),
    ('Landon Martinez', 4, '2023-09-15', 'Cozy place, but the WiFi was very slow.', 15),
    ('Zoe Peterson', 5, '2023-09-18', 'Perfect for a family vacation! We will definitely return.', 15),

    -- Reviews for listing 16 (4 reviews)
    ('Isaac Taylor', 4, '2023-09-02', 'Nice villa, but could use more parking space.', 16),
    ('Samantha Reed', 5, '2023-09-06', 'Incredible stay. The garden was absolutely stunning.', 16),
    ('Victoria Evans', 3, '2023-09-11', 'Good villa, but not as luxurious as the photos suggested.', 16),
    ('Hudson Rivera', 2, '2023-09-14', 'The house was nice, but it had some maintenance issues.', 16),

    -- Reviews for listing 17 (0 reviews)
    -- No reviews for listing 17

    -- Reviews for listing 18 (5 reviews)
    ('Charlotte Cooper', 5, '2023-09-01', 'Beautiful home with an amazing backyard. Perfect for families.', 18),
    ('Aiden Brooks', 3, '2023-09-05', 'Nice house, but the location wasn’t very convenient.', 18),
    ('Audrey Murphy', 4, '2023-09-10', 'Great stay, but it’s a bit far from shops and restaurants.', 18),
    ('Henry Foster', 1, '2023-09-12', 'Very disappointed. The house was in bad shape.', 18),
    ('Lila Cook', 4, '2023-09-15', 'Good value for money. The neighborhood was quiet.', 18),

    -- Reviews for listing 19 (3 reviews)
    ('Oliver Stewart', 3, '2023-09-03', 'Nice place, but there were issues with the plumbing.', 19),
    ('Amelia Sanchez', 5, '2023-09-07', 'Fantastic home! Everything was clean and well-maintained.', 19),
    ('Gabriel Perry', 2, '2023-09-12', 'The home was nice, but too many maintenance problems.', 19),

    -- Reviews for listing 20 (1 review)
    ('Ella Hernandez', 1, '2023-09-06', 'Amazing house! We had a wonderful time.', 20),

    -- Reviews for listing 21 (8 reviews)
    ('Cameron James', 5, '2023-09-02', 'Perfect for our family trip. The house was spacious and cozy.', 21),
    ('Chloe Brooks', 3, '2023-09-08', 'Good location, but the house needed some upgrades.', 21),
    ('Eleanor Ramirez', 4, '2023-09-11', 'Comfortable and well-furnished. Would stay here again.', 21),
    ('Luke Torres', 2, '2023-09-14', 'House was outdated and didn’t match the listing photos.', 21),
    ('Hazel Kelly', 5, '2023-09-16', 'Beautiful home with great amenities. Highly recommend!', 21),
    ('Sebastian Cox', 4, '2023-09-19', 'Nice place, but parking was an issue.', 21),
    ('Harper Bailey', 3, '2023-09-22', 'Good stay, but the neighborhood was too quiet for my taste.', 21),
    ('Isaiah Diaz', 1, '2023-09-24', 'Terrible experience. The house was dirty and poorly maintained.', 21),

    -- Reviews for listing 22 (3 reviews)
    ('Sophia Flores', 4, '2023-09-03', 'Nice apartment, but the bathroom could have been cleaner.', 22),
    ('Evelyn Sanders', 5, '2023-09-09', 'Loved the location and the apartment itself was very cozy.', 22),
    ('Jackson Clark', 3, '2023-09-13', 'Good stay, but a bit overpriced for the size.', 22),

    -- Reviews for listing 23 (0 reviews)
    -- No reviews for listing 23

    -- Reviews for listing 24 (2 reviews)
    ('Liam Torres', 4, '2023-09-06', 'Great apartment in a convenient location. Loved the modern design.', 24),
    ('Harper Johnson', 5, '2023-09-10', 'Perfect apartment with stunning views. Will stay again!', 24),

    -- Reviews for listing 25 (7 reviews)
    ('Levi Scott', 5, '2023-09-01', 'Fantastic stay! Everything was perfect.', 25),
    ('Emma Murphy', 4, '2023-09-05', 'Nice home, but the WiFi was a bit slow.', 25),
    ('Emily Rivera', 3, '2023-09-09', 'Good stay overall, but the kitchen could use more supplies.', 25),
    ('Henry Sanders', 2, '2023-09-12', 'House was fine, but the area was noisy at night.', 25),
    ('Noah Perry', 5, '2023-09-15', 'Amazing home with a great view! Highly recommend.', 25),
    ('Grace Mitchell', 4, '2023-09-18', 'Lovely home, but it could use some upgrades.', 25),
    ('Alexander Gonzalez', 3, '2023-09-21', 'Good value for money, but the place was a bit outdated.', 25),

    -- Reviews for listing 26 (4 reviews)
    ('Isabella Wright', 5, '2023-09-02', 'Absolutely loved it! The best stay I’ve had.', 26),
    ('Eli Martinez', 4, '2023-09-06', 'Nice stay, but a bit expensive for what it offered.', 26),
    ('Mason Rogers', 3, '2023-09-10', 'Good apartment, but the area was a bit sketchy at night.', 26),
    ('Lucas Bailey', 2, '2023-09-12', 'The apartment was okay, but it could have been cleaner.', 26),

    -- Reviews for listing 27 (3 reviews)
    ('Samuel Long', 4, '2023-09-03', 'Nice villa with great amenities. Perfect for a weekend stay.', 27),
    ('Zoe Ward', 5, '2023-09-08', 'Amazing stay! We had a fantastic time.', 27),
    ('Lily Howard', 3, '2023-09-11', 'Good villa, but there were issues with the heating.', 27),

    -- Reviews for listing 28 (0 reviews)
    -- No reviews for listing 28

    -- Reviews for listing 29 (1 review)
    ('Oliver Martinez', 5, '2023-09-05', 'Perfect location! Close to everything we needed.', 29),

    -- Reviews for listing 30 (10 reviews)
    ('Eleanor Harris', 5, '2023-09-02', 'Fantastic stay! Beautiful views and spacious rooms.', 30),
    ('Mia Allen', 4, '2023-09-06', 'Great apartment, but the WiFi was slow.', 30),
    ('Elijah Scott', 3, '2023-09-09', 'Nice place, but parking was difficult.', 30),
    ('Aria Perez', 5, '2023-09-12', 'Absolutely amazing! Would stay here again.', 30),
    ('Jackson Walker', 2, '2023-09-14', 'Not as advertised. The apartment was smaller than expected.', 30),
    ('Benjamin Reed', 4, '2023-09-16', 'Good apartment in a central location. Very convenient.', 30),
    ('Madison Flores', 3, '2023-09-18', 'Decent stay, but the apartment could have been cleaner.', 30),
    ('Ella Hall', 1, '2023-09-21', 'Terrible experience. The apartment was dirty and noisy.', 30),
    ('James Bailey', 5, '2023-09-23', 'Perfect! We had a great time. Highly recommend.', 30),
    ('Harper Wright', 3, '2023-09-26', 'Good stay overall, but the bed was uncomfortable.', 30);

UPDATE Listings 
SET average_rating = (
                        SELECT AVG(rating)
                        FROM Reviews
                        WHERE listing_id = Listings.id
                        ), 
    review_count = (
                        SELECT COUNT(*)
                        FROM Reviews
                        WHERE listing_id = Listings.id
                        );


-- FOR DEMONSTRATION
-- SELECT COUNT(*)
-- FROM Listings;

-- SELECT COUNT(*)
-- FROM Reviews
-- WHERE listing_id = 30;

-- SELECT *
-- FROM Reviews
-- WHERE listing_id = 40;

-- I recently stayed at this wonderful property for three months, and I have to say, it exceeded all of my expectations. From the moment I walked in, I was immediately struck by how spacious and well-maintained everything was. The living room was enormous and filled with natural light thanks to the floor-to-ceiling windows. Not only was the furniture modern and stylish, but it was also extremely comfortable, making it easy to relax after a long day.
-- One of the standout features of the apartment was the kitchen. As someone who loves to cook, I was thrilled with how well-equipped it was. There was a full set of stainless steel appliances, including a large fridge, oven, and even a dishwasher, which was a huge time-saver. The countertop space was more than enough for me to prepare elaborate meals, and I loved the open-plan design, which allowed me to interact with friends and family while cooking.
-- The bedrooms were another highlight of the property. The master bedroom, in particular, was luxurious, with a large king-sized bed, plenty of closet space, and even a private balcony with a stunning view of the city skyline. I found the mattress to be incredibly comfortable, and I always had a good night’s sleep. The second bedroom was perfect for guests, and everyone who stayed with me commented on how cozy and inviting it was.
