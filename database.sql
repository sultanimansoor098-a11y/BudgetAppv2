CREATE DATABASE budgetapp CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci;
USE budgetapp;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,   -- Unikt ID som automatiskt ökar (1,2,3...)
  email VARCHAR(255) UNIQUE NOT NULL, --- Får inte ha samma email
  password VARCHAR(255) NOT NULL,      ----- Måste va hashat
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP --- sparas när konto skapas
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY, 
  user_id INT NOT NULL,  --till vem transaktionen tillhör
  type ENUM('income','expense') NOT NULL, --typ av transaktionen och enum begränsar till dessa två
  amount INT NOT NULL, -- sparas som heltal
  category VARCHAR(100) NOT NULL, --kategori mat,lön osv
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   -- När transaktionen skapades
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE --  -- Om en användare tas bort raderas alla deras transaktioner automatiskt

);

CREATE TABLE goals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  goal_amount INT NOT NULL DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE rewards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  points INT DEFAULT 0,
  streak INT DEFAULT 0,
  last_action_date DATE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
