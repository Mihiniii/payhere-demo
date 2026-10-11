CREATE DATABASE IF NOT EXISTS payhere_demo;
USE payhere_demo;

CREATE TABLE IF NOT EXISTS orders (
  order_id    VARCHAR(50) PRIMARY KEY,
  item        VARCHAR(100),
  amount      DECIMAL(10,2),
  currency    VARCHAR(3) DEFAULT 'LKR',
  status      VARCHAR(20) DEFAULT 'PENDING',
  payment_id  VARCHAR(50),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);