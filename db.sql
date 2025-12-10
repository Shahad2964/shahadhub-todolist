-- Insecure Database (for demonstration)

-- Create database with weak charset and collation
CREATE DATABASE IF NOT EXISTS notsafe
  CHARACTER SET latin1
  COLLATE latin1_swedish_ci;

USE notsafe;

-- Insecure users table
CREATE TABLE users (
  id INT PRIMARY KEY,
  username VARCHAR(255),        -- No UNIQUE constraint
  email VARCHAR(255),           -- No UNIQUE constraint
  password TEXT,                -- Plain text password (no hashing)
  created_at TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;  -- MyISAM = no foreign keys, no row-level locking

-- Insecure feedback table
CREATE TABLE feedback (
  id INT PRIMARY KEY,
  user_id INT,                  -- No FK constraint
  name TEXT,                    -- Unrestricted length
  email TEXT,
  subject TEXT,
  message TEXT,
  submitted_at TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE todo_tasks (
    id INT PRIMARY KEY,
    user_id INT,                -- No FK constraint
    task TEXT,                  -- No limit on input size
    completed INT,              -- No constraint (can be 5, -1, etc.)
    created_at TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
