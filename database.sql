/*
 * ==========================================================
 * Roam Rajasthan - UPGRADED DATABASE SCHEMA
 * ==========================================================
 * This file REPLACES the old database.sql.
 * It includes all tables for social features.
 */

CREATE DATABASE IF NOT EXISTS roam_rajasthan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE roam_rajasthan_db;

-- 1. Users Table (Upgraded)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    profile_image_url VARCHAR(255) DEFAULT 'images/default_profile.png', -- ADDED
    bio TEXT, -- ADDED
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Follows Table (NEW)
-- Stores follower/following relationships
CREATE TABLE user_follows (
    follow_id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL, -- The user who is doing the following
    following_id INT NOT NULL, -- The user who is being followed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY (follower_id, following_id) -- Prevents duplicate follows
);

-- 3. Posts Table (NEW)
-- For a user's own profile posts (e.g., "Just had a great trip!")
CREATE TABLE user_posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_text TEXT NOT NULL,
    post_image_url VARCHAR(255), -- Optional image for the post
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 4. Reviews Table (Upgraded)
-- This is now the main reviews table
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    place_id VARCHAR(10) NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    -- We link place_id later in the app, as it's from a non-relational CSV.
    -- In a full build, we'd add: FOREIGN KEY (place_id) REFERENCES places(place_id)
);

-- 5. Review Photos Table (NEW)
-- Stores one or more photos for a single review
CREATE TABLE review_photos (
    photo_id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (review_id) REFERENCES reviews(review_id) ON DELETE CASCADE
);

-- 6. Review Replies Table (NEW)
-- Stores replies to a specific review
CREATE TABLE review_replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    user_id INT NOT NULL,
    reply_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(review_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

/*
 * ==========================================================
 * ORIGINAL DATA TABLES (from your CSVs)
 * ==========================================================
 * (These are unchanged)
 */

CREATE TABLE cities (
    city_id VARCHAR(10) PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL,
    name VARCHAR(100),
    description TEXT,
    best_time VARCHAR(100),
    air TEXT,
    train TEXT,
    road TEXT,
    map_link VARCHAR(255),
    image_url VARCHAR(255)
);

CREATE TABLE places (
    place_id VARCHAR(10) PRIMARY KEY,
    city_id VARCHAR(10),
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50),
    description TEXT,
    timing VARCHAR(100),
    entry_fee VARCHAR(100),
    map_link VARCHAR(255),
    image_url VARCHAR(255)
);

CREATE TABLE food (
    food_id VARCHAR(10) PRIMARY KEY,
    city_id VARCHAR(10),
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(255),
    map_link VARCHAR(255),
    description TEXT,
    image_url VARCHAR(255)
);

CREATE TABLE shopping (
    shop_id VARCHAR(10) PRIMARY KEY,
    city_id VARCHAR(10),
    name VARCHAR(100),
    map_link VARCHAR(255),
    famous_for VARCHAR(255),
    best_time VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255)
);

CREATE TABLE nature (
    nature_id VARCHAR(10) PRIMARY KEY,
    city_id VARCHAR(10),
    name VARCHAR(100),
    activity VARCHAR(100),
    best_time VARCHAR(100),
    map_link VARCHAR(255),
    description TEXT,
    image_url VARCHAR(255)
);

CREATE TABLE itineraries (
    itinerary_id VARCHAR(10) PRIMARY KEY,
    city_id VARCHAR(10),
    duration VARCHAR(50),
    morning TEXT,
    afternoon TEXT,
    evening TEXT,
    notes TEXT
);

